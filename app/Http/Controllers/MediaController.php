<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Storage;
use File;
use Log;
use DB;
use Validator;
use Response;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Media;
use Youtube;

class MediaController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show', 'videosSearch', 'parseVideoId']]);
    }

	public function index() {
        $medias = Media::where('active', true)->get();
        return $medias;
	}

    public function show($id) {
        $media = Media::find($id);
        $file = Storage::disk('local')->get($media->name);
        return response($file, 200)->header('Content-Type', 'image/'.$media->ext);
    }


    public function storeImage(Request $request, Media $media) {

        $user = User::find($request['user']['sub']);
        Log::info($user);

        if(!$request->hasFile('file')) { 
            return Response::json(['error' => 'No File Sent']);
        }

        if(!$request->file('file')->isValid()) {
            return Response::json(['error' => 'File is not valid']);
        }

        $file = $request->file('file');

        $v = Validator::make(
            $request->all(),
            ['file' => 'required|mimes:jpeg,jpg,png|max:8000']
        );

        if($v->fails()) {
            return Response::json(['error' => $v->errors()]);
        }

        Log::info($request->file('file'));

        $image = Media::create([
            'name' => $request->file('file')->getClientOriginalName(),
            'ext' => $request->file('file')->guessExtension(),
            'user_id' => $user->id,
            'type' => 'IMAGE'
        ]);
        
        $filename = 'torneo_media_'.$image->id . '.' . $image->ext;

        $image->name = $filename;
        $image->save();

        Storage::disk('local')->put($filename,  File::get($file));
        Storage::disk('s3-slam')->put('/slam/' . $filename, file_get_contents($file), 'public');

        return Response::json(['OK' => 1, 'filename' => $filename, 'media_id' => $image->id]);
    }

    public function videosSearch(Request $request) {
        $params = array(
            'q'             => $request->input('q'),
            'type'          => 'video',
            'part'          => 'id, snippet',
            'maxResults'    => 10
        );
        return Youtube::searchAdvanced($params, true);
    }

    public function parseVideoId(Request $request, $youtubeId) {
        $result = Youtube::getVideoInfo($youtubeId);
        print_r($result);
        return $result;
    }



}

