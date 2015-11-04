<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Log;
use DB;
use Carbon;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Competition;
use Slam\Model\Media;
use Slam\Model\Location;

class CompetitionController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index() {
        $competitions = Competition::all();
        return $competitions;
	}

	public function show($id) {
        $competition = Competition::find($id);
        $competition->region;
        $competition->videos;
        $competition->location;
        $competition->participants;
        return $competition;
	}


	public function store(Request $request) {
        $user = User::find($request['user']['sub']);
        $competition = new Competition;

        DB::transaction(function() use ($request, $competition, $user) {
            $geo = $this->processGeoValue($request->input('location'));
            $location = Location::firstOrCreate($geo);
            $location->save();
            $competition->region_id = $request->input('region_id');
            $competition->title = $request->input('title');
            $competition->description = $request->input('description');
            $competition->cover_photo = $request->input('cover_photo');
            $competition->users_limit = $request->input('users_limit');
            $competition->users_amount = $request->input('users_amount');
            $competition->rules = $request->input('rules');
            $arr = explode(".", $request->input('event_date'), 2);
            $event_date = str_replace("T", " ", $arr[0]);
            $competition->event_date = Carbon::createFromFormat('Y-m-d H:i:s', $event_date);
            $competition->location_id = $location->id;
            $competition->active = true;
            $competition->save();
                 
        });

        return $competition;
	}

	public function update(Request $request, $id) {
        $user = User::find($request['user']['sub']);
        $competition = Competition::find($id);
        DB::transaction(function() use ($request, $competition, $user) {
            $geo = $this->processGeoValue($request->input('location'));
            $location = Location::firstOrCreate($geo);
            $location->save();
            $competition->region_id = $request->input('region_id');
            $competition->title = $request->input('title');
            $competition->description = $request->input('description');
            $competition->cover_photo = $request->input('cover_photo');
            $competition->users_limit = $request->input('users_limit');
            $competition->users_amount = $request->input('users_amount');
            $competition->rules = $request->input('rules');
            $competition->event_date = $request->input('event_date');
            $competition->location_id = $location->id;
            $competition->active = true;
            Log::info($competition);
            $competition->save();
         
        });

        return $competition;
	}

	public function destroy($id) {
        Competition::destroy($id);
	}


    public function processGeoValue($geo) {
        Log::info($geo);
        $result = array(
            'formatted_address' => $geo['formatted_address'],
            'google_id' => $geo['id'],
            'place_id' => $geo['place_id'],
            'name' => $geo['name'],
        );
        $values_allowed = array(
            'sublocality' => true,
            'locality' => true,
            'sublocality_level_1',
            'administrative_area_level_2' => true,
            'administrative_area_level_1' => true,
            'country' => true,
            'latitude ' => true,
            'longitude' => true
        );

        $address_components = $geo['address_components'];
        if(is_array($address_components)) {
            foreach($address_components as $component) {
                $key_code =  $component['types'][0];
                if(isset($values_allowed[$key_code])) {
                    if($key_code == 'sublocality_level_1') {
                        $key_code = 'sublocality';
                    }
                    $result[$key_code] = $component['short_name']; 
                }
            }
        }
    
        Log::info($result);
        return $result;
    }

	public function addVideo(Request $request, $competitionId) {
        $user = User::find($request['user']['sub']);
        $competition = Competition::find($competitionId);
        DB::transaction(function() use ($request, $competition, $user) {
            $video = Media::create([
                'name' => $request->input('name'),
                'user_id' => $user->id,
                'type' => 'VIDEO'
            ]);
            
            $video->competition_id = $competition->id;
            $video->title = $request->input('title');
            $video->description = $request->input('description');
            $video->thumb_path = $request->input('thumb_path');
            $video->bucket = 'youtube';

            Log::info($video);

            $video ->save();


        });
 
        return Media::where('competition_id', $competition->id)->where('type', 'VIDEO')->get();

    }

}
