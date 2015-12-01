<?php

Route::post('auth/twitter', 'Auth\AuthController@twitter');
Route::post('auth/facebook', 'Auth\AuthController@facebook');
Route::post('auth/google', 'Auth\AuthController@google');
Route::post('auth/social/{name}', 'Auth\AuthController@doSocial');

Route::post('auth/login', 'Auth\AuthController@login');
Route::post('auth/signup', 'Auth\AuthController@signup');

Route::get('api/profile', ['middleware' => 'auth', 'uses' => 'ParticipantController@getProfile']);

Route::get('api/me', ['middleware' => 'auth', 'uses' => 'UserController@getUser']);
Route::put('api/me', ['middleware' => 'auth', 'uses' => 'UserController@updateUser']);

Route::get('api/region/{regionId}/summary', ['uses' => 'RegionController@summary']);

Route::post('api/users/assign/roles',  ['middleware' => 'auth', 'uses' => 'UserController@assignRoles']);
Route::post('api/users/assign/regions',  ['middleware' => 'auth', 'uses' => 'UserController@assignRegions']);

Route::resource('api/users', 'UserController');

Route::resource('api/participants', 'ParticipantController');
Route::resource('api/competitions', 'CompetitionController');
Route::resource('api/regions', 'RegionController');
Route::resource('api/roles', 'RoleController');
Route::resource('api/colors', 'ColorController');
Route::resource('api/icons', 'IconController');
Route::resource('api/sliders', 'SliderController');
Route::resource('api/cups', 'CupController');
Route::resource('api/mentions', 'MentionController');

Route::get('api/regions/{regionId}/competitions', ['middleware' => 'auth', 'uses' => 'RegionController@competitions']);
Route::post('api/competition/{competitionId}/video', ['middleware' => 'auth', 'uses' => 'CompetitionController@addVideo']);
Route::post('api/competition/{competitionId}/remove/video/{videoId}', ['middleware' => 'auth', 'uses' => 'CompetitionController@removeVideo']);
Route::post('api/competition/{competitionId}/video/{videoId}/participant/{participantId}', ['middleware' => 'auth', 'uses' => 'CompetitionController@addVideoParticipant']);
Route::post('api/competition/{competitionId}/video/{videoId}/remove/participant/{participantId}', ['middleware' => 'auth', 'uses' => 'CompetitionController@removeVideoParticipant']);

Route::post('api/competition/{competitionId}/participate', ['middleware' => 'auth', 'uses' => 'CompetitionController@participate']);

Route::post('api/media/upload', ['middleware' => 'auth', 'uses' => 'MediaController@storeImage']);
Route::post('api/media/slider/upload', ['middleware' => 'auth', 'uses' => 'MediaController@storeImageSlider']);
Route::post('api/media/videos/search', ['uses' => 'MediaController@videosSearch']);
Route::get('api/media/videos/search', ['uses' => 'MediaController@videosSearch']);
Route::get('api/media/videos/view/{youtubeId}', ['uses' => 'MediaController@parseVideoId']);


Route::get('/', function () {
    return view('welcome');
});
