<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
    //echo "hola mundo";
});

Route::get('/test', function () {
    echo "test";
    //echo "hola mundo";
});

Route::get('/User','UserController@index');

Route::resource('User','UserController');

Route::post('StoreTema','EducationController@create_Tema');

Route::get('liststema','EducationController@list_Tema');

Route::post('StoreHabilidad','EducationController@create_Habilidad');

Route::get('listshabilidad','EducationController@list_Habilidad');

Route::post('StoreFormulacion','EducationController@create_Formulacion');

Route::get('searchFormulacion/{id}','AskfactoryController@search_Formulacion');

Route::post('StoreUser','UserController@store');

Route::post('log','UserController@loggin');

Route::post('fileUpload','UserController@upload');

Route::get('credentials','UserController@credentials');

Route::get('LogOut','UserController@logout');

Route::get('list_challengues','EducationController@list_challengues');