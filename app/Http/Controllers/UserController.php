<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Log;
use DB;
use JWT;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\UserRole;

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

    public function assignRole(Request $request) {
        $user = User::find($request['user']['sub']);
        if($user->hasRole('crud_user')) {
            DB::transaction(function() use ($request) {
                
                $roles = $request->input('roles');
                $user_id = $request->input('userId');
                foreach($roles as $role) {
                    $userRole =  UserRole::findOrCreate(array(
                        'user_id' => $userId,
                        'role_id' => $role
                    ));
                }
            });
        } else {
            return response()->json(['message' => 'No tienes permisos para esta operacion'], 401);
        }
        return response()->json(['message' => 'Permiso asignado'], 200);

    }
}

