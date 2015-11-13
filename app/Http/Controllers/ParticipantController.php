<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;

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
        return $participant;
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
