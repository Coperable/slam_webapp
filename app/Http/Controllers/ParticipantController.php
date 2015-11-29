<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;
use GuzzleHttp;
use GuzzleHttp\Client;

class ParticipantController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index() {
        $participants = User::participants()->get();
        return $participants;
	}

	public function show($id) {
        $participant = User::find($id);
        $participant->competitions;
        $participant->competitions->each(function($competition) {
            $competition->location;
            $competition->videos;
            $competition->users;
        });
        $participant->videos;
        //$this->discourse_get_user_info($participant);
        $this->discourse_get_posts($participant);
        return $participant;
	}


	public function discourse_get_user_info($user) {
        $url_request_user_info = Config::get('app.discourse_host').'/users/'.$user->username.'.json';

        $client = new GuzzleHttp\Client();
        $user_info = json_decode($client->get($url_request_user_info, [
            'query' => [
                'api_key' => Config::get('app.discourse_api_key'),
                'api_username' => Config::get('app.discourse_api_username')
            ],
            'http_errors' => false
        ])->getBody(), true);

        if(isset($user_info) && isset($user_info['user'])) {
            $user->post_count = $user_info['user']['post_count'];
        }
    }

	public function discourse_get_posts($user) {
        $url_request_user_info = Config::get('app.discourse_host').'/user_actions.json';

        $client = new GuzzleHttp\Client();
        $posts = json_decode($client->get($url_request_user_info, [
            'query' => [
                'api_key' => Config::get('app.discourse_api_key'),
                'api_username' => Config::get('app.discourse_api_username'),
                'username' => strtolower($user->username),
                'filter' => 5

            ],
            'http_errors' => false
        ])->getBody(), true);

        if(isset($posts) && isset($posts['user_actions'])) {
            $user->posts = $posts['user_actions'];
        }
    }


	public function store(Request $request) {
        $user = User::find($request['user']['sub']);
        $participant = new User;

        DB::transaction(function() use ($request, $participant, $user) {

            $participant->username = $request->input('name');
            $participant->save();
                 
        });

        return $participant;
	}

	public function update(Request $request, $id) {
        $participant = User::find($id);
        DB::transaction(function() use ($request, $participant, $user) {
            $participant->username = $request->input('name');
            $participant->save();
        });

        return $participant;
	}

	public function destroy($id) {
        User::destroy($id);
	}

    public function getProfile(Request $request) {
        $user = User::find($request['user']['sub']);
        $user->roles;
        $user->videos;
        $user->competitions;
        $user->regions;
        return array(
            'user' => $user
        );
    }

}
