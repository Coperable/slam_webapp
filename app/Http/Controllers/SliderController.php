<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Carbon;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Slider;

class SliderController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index() {
        $sliders = Slider::all();
        return $sliders;
	}

	public function show($id) {
        $slider = Slider::find($id);
        return $slider;
	}

	public function store(Request $request) {
        $user = User::find($request['user']['sub']);
        $slider = new Slider;

        DB::transaction(function() use ($request, $slider, $user) {
            $slider->type = $request->input('type');
            $slider->title = $request->input('title');
            $slider->subtitle = $request->input('subtitle');
            $slider->quote_author = $request->input('quote_author');

            if($request->has('event_date')) {
                $arr = explode(".", $request->input('event_date'), 2);
                $event_date = str_replace("T", " ", $arr[0]);
                $slider->event_date = Carbon::createFromFormat('Y-m-d H:i:s', $event_date);
            }

            $slider->event_place = $request->input('event_place');
            $slider->cover_photo = $request->input('cover_photo');
            $slider->signup_action = $request->input('signup_action') || false;
            $slider->schedule_action = $request->input('schedule_action') || false;
            $slider->about_action = $request->input('about_action') || false;
            $slider->players_action = $request->input('players_action') || false;
            $slider->save();
        });

        return $slider;
	}

	public function update(Request $request, $id) {
        $user = User::find($request['user']['sub']);
        $slider = Slider::find($id);
        DB::transaction(function() use ($request, $slider, $user) {
            $slider->type = $request->input('type');
            $slider->title = $request->input('title');
            $slider->subtitle = $request->input('subtitle');
            $slider->quote_author = $request->input('quote_author');
            if($request->has('event_date')) {
                $arr = explode(".", $request->input('event_date'), 2);
                $event_date = str_replace("T", " ", $arr[0]);
                $slider->event_date = Carbon::createFromFormat('Y-m-d H:i:s', $event_date);
            }
            $slider->event_place = $request->input('event_place');
            $slider->cover_photo = $request->input('cover_photo');
            $slider->signup_action = $request->input('signup_action') || false;
            $slider->schedule_action = $request->input('schedule_action') || false;
            $slider->about_action = $request->input('about_action') || false;
            $slider->players_action = $request->input('players_action') || false;
            $slider->save();
        });

        return $slider;
	}


	public function destroy($id) {
        Slider::destroy($id);
	}


}




