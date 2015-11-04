<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Role;

class RoleController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index() {
        $roles = Role::all();
        return $roles;
	}

	public function show($id) {
        $role = Role::find($id);
        return $role;
	}

	public function store(Request $request) {
        $role = new Role;

        DB::transaction(function() use ($request, $role) {
            $role->name = $request->input('name');
            $role->save();
        });

        return $role;
	}

	public function update(Request $request, $id) {
        $role = Role::find($id);
        DB::transaction(function() use ($request, $role) {
            $role->name = $request->input('name');
            $role->save();
        });

        return $role;
	}


	public function destroy($id) {
        Role::destroy($id);
	}


}





