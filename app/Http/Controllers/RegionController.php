<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Carbon;
use Log;
use DB;
use JWT;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Region;
use Slam\Model\Competition;

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
        return $region;
	}

	public function summary(Request $request, $regionId) {
        $region = Region::find($regionId);

        $user = false;
        $token = $request->header('Authorization');
		if ( $token )  {
            if(isset($token[1])) {
                $token = explode(' ', $request->header('Authorization'))[1];
                $payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));
                $user = User::find($payload['sub']);
            }
        }



        
        $participants = new Collection();
        $past_competitions = new Collection();
        $next_competitions = new Collection();
        $next_competition = array();
        $competitions = array();

        if($regionId == 1) {
            $competitions = Competition::all();
            $videos = DB::table('medias')->where('region_id', '<>', $region->id)->get();
            $region->competitions = $competitions;
        } else {
            $competitions = $region->competitions;
            $videos = DB::table('medias')->where('region_id', '=', $region->id)->get();
        }
        $competitions->each(function($competition) use ($past_competitions, $next_competitions, $participants, $user)  {
            $competition->users->each(function($participant) use ($participants, $competition, $user) {
                if($user && $user->id == $participant->id) {
                    $competition->already_participating = true;
                }
                $participant->medias;
                $participant->competitions;
                $participants->push($participant);
            });
            $competition->location;
            $competition->videos;
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
        $region->competitions_count = count($competitions);
        $region->participants = $participants->unique();
        $region->participants_count = count($region->participants);
    
        return $region;
	}


	public function store(Request $request) {
        $user = User::find($request['user']['sub']);
        $region = new Region;

        DB::transaction(function() use ($request, $region, $user) {

            $region->name = $request->input('name');
            $region->description = $request->input('description');
            $region->color = $request->input('color')['code'];
            $region->icon = $request->input('icon')['code'];
            $region->parent_id = $request->input('parent_id');
            $region->save();
                 
        });

        return $region;
	}

	public function update(Request $request, $id) {
        $user = User::find($request['user']['sub']);
        $region = Region::find($id);

        DB::transaction(function() use ($request, $region, $user) {
            $region->name = $request->input('name');
            $region->description = $request->input('description');
            $region->color = $request->input('color')['code'];
            $region->icon = $request->input('icon')['code'];
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



