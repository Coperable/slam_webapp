<?php

namespace Slam\Http\Controllers\Auth;

use Config;
use Hash;
use Log;
use JWT;
use Validator;

use Socialite;

use Slam\User;
use Slam\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Slam\Jobs\UpdateProfilePicture;
use Storage;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    /*

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    */

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
/*
    public function __construct() {
        $this->middleware('guest', ['except' => 'getLogout']);
    }
*/
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
/*
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }
*/

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
/*
    protected function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

*/
//Start

    protected function createToken($user) {
        $payload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return JWT::encode($payload, Config::get('app.token_secret'));
    }


    public function unlink(Request $request, $provider) {
        $user = User::find($request['user']['sub']);

        if (!$user) {
            return response()->json(['message' => 'User not found']);
        }

        $user->$provider = '';
        $user->save();
        
        return response()->json(array('token' => $this->createToken($user)));
    }

    public function login(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', '=', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Wrong email and/or password'], 401);
        }

        if (Hash::check($password, $user->password)) {
            unset($user->password);


            if(!isset($user->photo)) {
                //$this->dispatch(new UpdateProfilePicture($user));
                $gravatar = md5(strtolower(trim($user->email)));
                $user->photo = $gravatar;
                $user->save();
                Storage::disk('s3-slam')->put('/slam/profiles/' . $gravatar, file_get_contents('http://www.gravatar.com/avatar/'.$gravatar.'?d=identicon&s=150'), 'public');
            }
 

            return response()->json([
                'token' => $this->createToken($user),
                'select_profile' => ($user->profile_type == null) 
            ]);
        } else {
            return response()->json(['message' => 'Wrong email and/or password'], 401);
        }
    }

    public function signup(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 400);
        }

        $user = new User;
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->participant = 1;
        $user->save();

        //$this->dispatch(new UpdateProfilePicture($user));
        $gravatar = md5(strtolower(trim($user->email)));
        $user->photo = $gravatar;
        $user->save();
        Storage::disk('s3-slam')->put('/slam/profiles/' . $gravatar, file_get_contents('http://www.gravatar.com/avatar/'.$gravatar.'?d=identicon&s=150'), 'public');


        return response()->json(['token' => $this->createToken($user)]);
    }

    public function doSocial(Request $request, $name) {

        if ($request->has('redirectUri')) {
            config()->set("services.{$name}.redirect", $request->get('redirectUri'));
        }

        $provider = Socialite::driver($name);

        //$provider->stateless();

        Log::info('first step');
        $profile = $provider->user();
        Log::info('second step');


        if ($request->header('Authorization')) {
            $user = User::where($name, '=', $profile['sub']);

            if ($user->first()) {
                return response()->json(['message' => 'There is already a '.$name.' account that belongs to you'], 409);
            }

            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));

            $user = User::find($payload['sub']);
            $user->google = $profile['sub'];
            $user->username = $user->username || $profile['name'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        } else {
            $user = User::where($name, '=', $profile['sub']);

            if ($user->first()) {
                return response()->json(['token' => $this->createToken($user->first())]);
            }

            $user = new User;
            
            switch($name) {

                case 'google':
                    $user->google = $profile->getId();
                    break;
                case 'facebook':
                    $user->facebook = $profile->getId();
                    break;
                case 'twitter':
                    $user->twitter = $profile->getId();
                    break;

            }

            $user->username = $profile->getNickname();
            $user->name = $profile->getName();
            $user->email = $profile->getEmail();
            $user->photo = $profile->getAvatar();
            $user->participant = 1;

            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }

    }


    public function facebook(Request $request) {
        $accessTokenUrl = 'https://graph.facebook.com/v2.3/oauth/access_token';
        $graphApiUrl = 'https://graph.facebook.com/v2.3/me';

        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'redirect_uri' => $request->input('redirectUri'),
            'client_secret' => Config::get('app.facebook_secret')
        ];

        $client = new GuzzleHttp\Client();

        // Step 1. Exchange authorization code for access token.
        $accessToken = $client->get($accessTokenUrl, ['query' => $params])->json();

        // Step 2. Retrieve profile information about the current user.
        $profile = $client->get($graphApiUrl, ['query' => $accessToken])->json();


        // Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization'))
        {
            $user = User::where('facebook', '=', $profile['id']);

            if ($user->first())
            {
                return response()->json(['message' => 'There is already a Facebook account that belongs to you'], 409);
            }

            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));

            $user = User::find($payload['sub']);
            $user->facebook = $profile['id'];
            $user->username = $user->username || $profile['name'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
        // Step 3b. Create a new user account or return an existing one.
        else
        {
            $user = User::where('facebook', '=', $profile['id']);

            if ($user->first())
            {
                return response()->json(['token' => $this->createToken($user->first())]);
            }

            $user = new User;
            $user->facebook = $profile['id'];
            $user->username = $profile['name'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
    }

    public function google(Request $request) {
        $accessTokenUrl = 'https://accounts.google.com/o/oauth2/token';
        $peopleApiUrl = 'https://www.googleapis.com/plus/v1/people/me/openIdConnect';

        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'client_secret' => Config::get('app.google_secret'),
            'redirect_uri' => $request->input('redirectUri'),
            'grant_type' => 'authorization_code',
        ];

        $client = new GuzzleHttp\Client();

        // Step 1. Exchange authorization code for access token.
        $accessTokenResponse = $client->post($accessTokenUrl, ['body' => $params]);
        $accessToken = $accessTokenResponse->json()['access_token'];

        $headers = array('Authorization' => 'Bearer ' . $accessToken);

        // Step 2. Retrieve profile information about the current user.
        $profileResponse = $client->get($peopleApiUrl, ['headers' => $headers]);

        $profile = $profileResponse->json();

        // Step 3a. If user is already signed in then link accounts.
        if ($request->header('Authorization'))
        {
            $user = User::where('google', '=', $profile['sub']);

            if ($user->first())
            {
                return response()->json(['message' => 'There is already a Google account that belongs to you'], 409);
            }

            $token = explode(' ', $request->header('Authorization'))[1];
            $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));

            $user = User::find($payload['sub']);
            $user->google = $profile['sub'];
            $user->username = $user->username || $profile['name'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
        // Step 3b. Create a new user account or return an existing one.
        else
        {
            $user = User::where('google', '=', $profile['sub']);

            if ($user->first())
            {
                return response()->json(['token' => $this->createToken($user->first())]);
            }

            $user = new User;
            $user->google = $profile['sub'];
            $user->username = $profile['name'];
            $user->save();

            return response()->json(['token' => $this->createToken($user)]);
        }
    }

    public function twitter(Request $request) {
        $requestTokenUrl = 'https://api.twitter.com/oauth/request_token';
        $accessTokenUrl = 'https://api.twitter.com/oauth/access_token';
        $profileUrl = 'https://api.twitter.com/1.1/users/show.json?screen_name=';

        $client = new GuzzleHttp\Client();

        // Part 1 of 2: Initial request from Satellizer.
        if (!$request->input('oauth_token') || !$request->input('oauth_verifier'))
        {
            $requestTokenOauth = new Oauth1([
              'consumer_key' => Config::get('app.twitter_key'),
              'consumer_secret' => Config::get('app.twitter_secret'),
              'callback' => Config::get('app.twitter_callback')
            ]);

            $client->getEmitter()->attach($requestTokenOauth);

            // Step 1. Obtain request token for the authorization popup.
            $requestTokenResponse = $client->post($requestTokenUrl, ['auth' => 'oauth']);

            $oauthToken = array();
            parse_str($requestTokenResponse->getBody(), $oauthToken);

            // Step 2. Send OAuth token back to open the authorization screen.
            return response()->json($oauthToken);

        }
        // Part 2 of 2: Second request after Authorize app is clicked.
        else
        {
            $accessTokenOauth = new Oauth1([
                'consumer_key' => Config::get('app.twitter_key'),
                'consumer_secret' => Config::get('app.twitter_secret'),
                'token' => $request->input('oauth_token'),
                'verifier' => $request->input('oauth_verifier')
            ]);

            $client->getEmitter()->attach($accessTokenOauth);

            // Step 3. Exchange oauth token and oauth verifier for access token.
            $accessTokenResponse = $client->post($accessTokenUrl, ['auth' => 'oauth'])->getBody();

            $accessToken = array();
            parse_str($accessTokenResponse, $accessToken);

            $profileOauth = new Oauth1([
                'consumer_key' => Config::get('app.twitter_key'),
                'consumer_secret' => Config::get('app.twitter_secret'),
                'oauth_token' => $accessToken['oauth_token']
            ]);

            $client->getEmitter()->attach($profileOauth);

            // Step 4. Retrieve profile information about the current user.
            $profile = $client->get($profileUrl . $accessToken['screen_name'], ['auth' => 'oauth'])->json();


            // Step 5a. Link user accounts.
            if ($request->header('Authorization'))
            {
                $user = User::where('twitter', '=', $profile['id']);
                if ($user->first())
                {
                    return response()->json(['message' => 'There is already a Twitter account that belongs to you'], 409);
                }

                $token = explode(' ', $request->header('Authorization'))[1];
                $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));

                $user = User::find($payload['sub']);
                $user->twitter = $profile['id'];
                $user->username = $user->username || $profile['screen_name'];
                $user->save();

                return response()->json(['token' => $this->createToken($user)]);
            }
            // Step 5b. Create a new user account or return an existing one.
            else
            {
                $user = User::where('twitter', '=', $profile['id']);

                if ($user->first())
                {
                    return response()->json(['token' => $this->createToken($user->first())]);
                }

                $user = new User;
                $user->twitter = $profile['id'];
                $user->username = $profile['screen_name'];
                $user->save();

                return response()->json(['token' => $this->createToken($user)]);
            }
        }
    }


}

