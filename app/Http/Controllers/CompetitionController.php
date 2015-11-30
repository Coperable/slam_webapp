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
use Slam\Model\UserMedia;
use Slam\Model\Location;
use Slam\Model\UserCompetition;

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
        $competition->mentions;
        $competition->cups;
        foreach($competition->videos as $video) {
            $video->users;
        }

        $competition->location;
        $competition->users->each(function($participant) {
            $participant->medias;
            $participant->competitions;
        });
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
            
            $competition->users_limit = $request->has('users_limit') ? $request->input('users_limit') : false;
            $competition->users_amount = $request->input('users_amount');
            $competition->place = $request->input('place');
            $competition->hashtag = $request->input('hashtag');
            $competition->facebook = $request->input('facebook');
            $competition->twitter = $request->input('twitter');
            $competition->instagram = $request->input('instagram');
            $competition->youtube = $request->input('youtube');
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
            if($request->has('location')) {
                $pregeo = $request->input('location');
                if(isset($pregeo['address_components'])) {
                    $geo = $this->processGeoValue($pregeo);
                    $location = Location::firstOrCreate($geo);
                    $location->save();
                    $competition->location_id = $location->id;
                }
            }
            $competition->region_id = $request->input('region_id');
            $competition->title = $request->input('title');
            $competition->description = $request->input('description');
            $competition->cover_photo = $request->input('cover_photo');
            $competition->users_limit = $request->has('users_limit') ? $request->input('users_limit') : false;
            $competition->users_amount = $request->input('users_amount');
            $competition->rules = $request->input('rules');
            $arr = explode(".", $request->input('event_date'), 2);
            $event_date = str_replace("T", " ", $arr[0]);
            $competition->event_date = Carbon::createFromFormat('Y-m-d H:i:s', $event_date);
            $competition->active = true;
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
            $video->region_id = $competition->region->id;
            $video->title = $request->input('title');
            $video->description = $request->input('description');
            $video->thumb_path = $request->input('thumb_path');
            $video->bucket = 'youtube';

            Log::info($video);

            $video ->save();


        });
 
        return Media::where('competition_id', $competition->id)->where('type', 'VIDEO')->get();

    }

	public function participate(Request $request, $competitionId) {
        $user = User::find($request['user']['sub']);
        $competition = Competition::find($competitionId);
        DB::transaction(function() use ($request, $competition, $user) {
            $userCompetition =  UserCompetition::firstOrCreate(array(
                'user_id' => $user->id,
                'competition_id' => $competition->id
            ));

        });
 
        return response()->json(['message' => 'Usuario participando'], 200);

    }

    public function removeVideo(Request $request, $competitionId, $videoId) {

        DB::transaction(function() use ($request, $competitionId, $videoId) {

            $video = Media::find($videoId);
            $video->competition_id = null;
            $video->region_id = null;
            $video->save();

        });

        return Media::where('competition_id', $competitionId)->where('type', 'VIDEO')->get();
    }


	public function addVideoParticipant(Request $request, $competitionId, $videoId, $participantId) {
        $user = User::find($request['user']['sub']);
        $competition = Competition::find($competitionId);
        DB::transaction(function() use ($request, $competition, $videoId, $participantId) {
            $video = UserMedia::create([
                'media_id' => $videoId,
                'user_id' => $participantId
            ]);
        });
 
        $media = Media::find($videoId);
        $media->users;
        return $media;

    }

    public function removeVideoParticipant(Request $request, $competitionId, $videoId, $participantId) {
        DB::transaction(function() use ($request, $participantId, $videoId) {
            UserMedia::where('media_id', $videoId)->where('user_id', $participantId)->delete();
        });
        $media = Media::find($videoId);
        $media->users;
    
        return $media;
    }


}

