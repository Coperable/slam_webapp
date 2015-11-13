<?php namespace Slam\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Log;
use DB;
use Illuminate\Support\Collection;
use Slam\User;
use Slam\Model\Color;

class ColorController extends Controller {

    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index() {
        $colors = Color::all();
        return $colors;
	}

	public function show($id) {
        $color = Color::find($id);
        return $color;
	}

	public function store(Request $request) {
        $color = new Color;

        DB::transaction(function() use ($request, $color) {
            $color->code = $request->input('code');
            $color->save();
        });

        return $color;
	}

	public function update(Request $request, $id) {
        $color = Color::find($id);
        DB::transaction(function() use ($request, $color) {
            $color->code = $request->input('code');
            $color->save();
        });

        return $color;
	}


	public function destroy($id) {
        Color::destroy($id);
	}


}

