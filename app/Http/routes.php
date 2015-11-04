<?php

Route::post('auth/twitter', 'Auth\AuthController@twitter');
Route::post('auth/facebook', 'Auth\AuthController@facebook');
Route::post('auth/google', 'Auth\AuthController@google');
Route::post('auth/login', 'Auth\AuthController@login');
Route::post('auth/signup', 'Auth\AuthController@signup');

Route::get('api/me', ['middleware' => 'auth', 'uses' => 'UserController@getUser']);
Route::put('api/me', ['middleware' => 'auth', 'uses' => 'UserController@updateUser']);

Route::get('api/region/{regionId}/summary', ['uses' => 'RegionController@summary']);

Route::post('api/users/assign/role',  ['middleware' => 'auth', 'uses' => 'UserController@assignRole']);

Route::resource('api/users', 'UserController');
Route::resource('api/participants', 'ParticipantController');
Route::resource('api/competitions', 'CompetitionController');
Route::resource('api/regions', 'RegionController');
Route::resource('api/roles', 'RoleController');

Route::get('api/regions/{regionId}/competitions', ['middleware' => 'auth', 'uses' => 'RegionController@competitions']);
Route::post('api/competition/{competitionId}/video', ['middleware' => 'auth', 'uses' => 'CompetitionController@addVideo']);

Route::post('api/media/upload', ['middleware' => 'auth', 'uses' => 'MediaController@storeImage']);
Route::post('api/media/videos/search', ['uses' => 'MediaController@videosSearch']);
Route::get('api/media/videos/search', ['uses' => 'MediaController@videosSearch']);
Route::get('api/media/videos/view/{youtubeId}', ['uses' => 'MediaController@parseVideoId']);


Route::get('/', function () {
    return view('welcome');
});
