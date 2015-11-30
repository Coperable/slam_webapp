<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Carbon;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Cup;

class CupController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show', 'fetchByCompetition']]);
    }

	public function index() {
        $cups = Cup::all();
        return $cups;
	}

	public function show($id) {
        $cup = Cup::find($id);
        return $cup;
	}

	public function fetchByCompetition($competitionId) {
        $cups = Cup::where('competition_id', '=', $competitionId)->get();
        return $cups;
	}

	public function store(Request $request) {
        $user = User::find($request['user']['sub']);
        $cup = new Cup;

        DB::transaction(function() use ($request, $cup, $user) {
            $cup->name = $request->input('name');
            $cup->description = $request->input('description');
            $cup->icon = $request->input('icon');
            $cup->competition_id = $request->input('competition_id');
            $cup->save();
        });

        return $cup;
	}

	public function update(Request $request, $id) {
        $user = User::find($request['user']['sub']);
        $cup = Cup::find($id);
        DB::transaction(function() use ($request, $cup, $user) {
            $cup->name = $request->input('name');
            $cup->description = $request->input('description');
            $cup->icon = $request->input('icon');
            $cup->competition_id = $request->input('competition_id');
            $cup->save();
        });

        return $cup;
	}


	public function destroy($id) {
        Cup::destroy($id);
	}


}






