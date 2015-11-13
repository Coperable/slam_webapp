<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
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

    public function updateUser(Request $request) {
        $user = User::find($request['user']['sub']);

        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->save();

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
}

