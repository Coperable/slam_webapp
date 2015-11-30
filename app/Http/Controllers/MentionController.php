<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Carbon;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Mention;

class MentionController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show', 'fetchByCompetition']]);
    }

	public function index() {
        $mentions = Mention::all();
        return $mentions;
	}

	public function show($id) {
        $mention = Mention::find($id);
        return $mention;
	}

	public function fetchByCompetition($competitionId) {
        $mentions = Mention::where('competition_id', '=', $competitionId)->get();
        return $mentions;
	}

	public function store(Request $request) {
        $user = User::find($request['user']['sub']);
        $mention = new Mention;

        DB::transaction(function() use ($request, $mention, $user) {
            $mention->name = $request->input('name');
            $mention->description = $request->input('description');
            $mention->icon = $request->input('icon');
            $mention->competition_id = $request->input('competition_id');
            $mention->save();
        });

        return $mention;
	}

	public function update(Request $request, $id) {
        $user = User::find($request['user']['sub']);
        $mention = Mention::find($id);
        DB::transaction(function() use ($request, $mention, $user) {
            $mention->name = $request->input('name');
            $mention->description = $request->input('description');
            $mention->icon = $request->input('icon');
            $mention->competition_id = $request->input('competition_id');
            $mention->save();
        });

        return $mention;
	}


	public function destroy($id) {
        Mention::destroy($id);
	}


}





