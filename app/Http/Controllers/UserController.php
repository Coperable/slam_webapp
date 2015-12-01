<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Validator;
use Storage;
use Hash;
use Log;
use DB;
use JWT;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\UserRole;
use Slam\Model\UserRegion;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show', 'search']]);
    }

	public function index() {
        $users = User::where('participant', false)->get();
        return $users;
	}

	public function show($id) {
        $user = User::find($id);
        $user->roles;
        $user->regions;
        return $user;
	}

    protected function createToken($user) {
        $payload = [
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (2 * 7 * 24 * 60 * 60)
        ];
        return JWT::encode($payload, Config::get('app.token_secret'));
    }

    public function getUserStatus(Request $request) {
        $user = User::find($request['user']['sub']);
        return $user;
    }

    public function getUserSummary(Request $request) {
        $user = User::find($request['user']['sub']);
        $user->roles;
        $user->regions;
        return $user;
    }

    public function getUser(Request $request) {
        $user = User::find($request['user']['sub']);
        $user->roles;
        $user->regions;
        return array(
            'user' => $user
        );
    }

    public function update(Request $request) {
        $user = User::find($request['user']['sub']);

        $user->username = $request->input('username');
        $user->email = $request->input('email');

        $user->save();

        return response()->json($user);
    }


    public function updateUser(Request $request) {
        $user = User::find($request['user']['sub']);

        $user->username = $request->input('username');
        $user->email = $request->input('email');

        $current_password =  $request->input('current_password');
        $password =  $request->input('password');

        if(isset($current_password) && isset($password) ) {

            if (Hash::check($current_password, $user->password)) {
                $user->password = Hash::make($request->input('password'));
                $user->save();
            } else {
                return response()->json(['message' => 'user_current_password_wrong'], 400);
            }
        } else {
            $user->save();
        }

        $token = $this->createToken($user);

        return response()->json(['token' => $token]);
    }

	public function search(Request $request, UserRepository $userRepository) {
        $query = $request->input('q');
        return $userRepository->search($query);
    }


    public function assignRegions(Request $request) {
        $user = User::find($request['user']['sub']);
        if($user->hasRole('crud_user')) {
            DB::transaction(function() use ($request) {
                $user_id = $request->input('userId');
                $regions = $request->input('regions');

                DB::table('users_regions')->where('user_id', '=', $user_id)->delete();
               
                foreach($regions as $region) {
                    $userRole =  UserRegion::firstOrCreate(array(
                        'user_id' => $user_id,
                        'region_id' => $region['id'],
                        'admin' => true
                    ));
                }
            });
        } else {
            return response()->json(['message' => 'No tienes permisos para esta operacion'], 401);
        }
        return response()->json(['message' => 'Regiones asignadas'], 200);
    }



    public function assignRoles(Request $request) {
        $user = User::find($request['user']['sub']);
        if($user->hasRole('crud_user')) {
            DB::transaction(function() use ($request) {
                
                $roles = $request->input('roles');
                $user_id = $request->input('userId');
                DB::table('users_roles')->where('user_id', '=', $user_id)->delete();

                foreach($roles as $role) {
                    $userRole =  UserRole::firstOrCreate(array(
                        'user_id' => $user_id,
                        'role_id' => $role['id']
                    ));
                }
            });
        } else {
            return response()->json(['message' => 'No tienes permisos para esta operacion'], 401);
        }
        return response()->json(['message' => 'Permisos asignados'], 200);

    }

    public function store(Request $request) {
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
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->save();

        $gravatar = md5(strtolower(trim($user->email)));
        $user->photo = $gravatar;
        $user->save();
        Storage::disk('s3-slam')->put('/slam/profiles/' . $gravatar, file_get_contents('http://www.gravatar.com/avatar/'.$gravatar.'?d=identicon&s=150'), 'public');

        return response()->json($user);

    }


}

