<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Icon;

class IconController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index() {
        $icons = Icon::all();
        return $icons;
	}

	public function show($id) {
        $icon = Icon::find($id);
        return $icon;
	}

	public function store(Request $request) {
        $icon = new Icon;

        DB::transaction(function() use ($request, $icon) {
            $icon->code = $request->input('code');
            $icon->save();
        });

        return $icon;
	}

	public function update(Request $request, $id) {
        $icon = Icon::find($id);
        DB::transaction(function() use ($request, $icon) {
            $icon->code = $request->input('code');
            $icon->save();
        });

        return $icon;
	}


	public function destroy($id) {
        Icon::destroy($id);
	}


}






