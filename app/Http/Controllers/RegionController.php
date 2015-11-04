<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Carbon;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Region;

class RegionController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show', 'competitions', 'summary']]);
    }

	public function index() {
        $regions = Region::all();
        return $regions;
	}

	public function show($id) {
        $region = Region::find($id);
        $region->competitions;
        Log::info($region);
        return $region;
	}

	public function summary($regionId) {
        $region = Region::find($regionId);
        
        $videos = array();
        $participants = array();
        $past_competitions = new Collection();
        $next_competitions = new Collection();
        $next_competition = array();
        $region->competitions->each(function($competition) use ($region, $past_competitions, $next_competitions, $videos, $participants)  {
            array_push($participants, $competition->participants);
            array_push($videos, $competition->videos);

            $competition->location;
            
            if (Carbon::now()->gte($competition->event_date)) {
                $competition->past = true;
                $past_competitions->push($competition);
            } else {
                $competition->past = false;
                $next_competitions->push($competition);
            }
        });
        $region->next_competition = $next_competitions->first();
        $region->next_competitions = $next_competitions;
        $region->past_competitions = $past_competitions;
        $region->videos = $videos;
        $region->videos_count  = count($videos);
        $region->participants = $participants;
        $region->participants_count = count($participants);
    
        return $region;
	}


	public function store(Request $request) {
        $user = User::find($request['user']['sub']);
        $region = new Region;

        DB::transaction(function() use ($request, $region, $user) {

            $region->name = $request->input('name');
            $region->description = $request->input('description');
            $region->color = $request->input('color');
            $region->icon = $request->input('icon');
            $region->parent_id = $request->input('parent_id');
            $region->save();
                 
        });

        return $region;
	}

	public function update(Request $request, $id) {
        $region = Region::find($id);
        DB::transaction(function() use ($request, $region, $user) {
            $region->name = $request->input('name');
            $region->description = $request->input('description');
            $region->color = $request->input('color');
            $region->icon = $request->input('icon');
            $region->parent_id = $request->input('parent_id');
            $region->save();
        });

        return $region;
	}

    public function competitions(Request $request, $regionId) {
        $region = Region::find($regionId);
        return $region->competitions;
    }

	public function destroy($id) {
        Region::destroy($id);
	}


}



