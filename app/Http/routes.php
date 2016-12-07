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

//Revizar

Route::get('list_challengues','EducationController@list_challengues');

Route::get('begin_challengues/{id}','ChallengueBuilder@generate_challengues');

//Logros

Route::post('set_score','QualificationController@get_score');

Route::get('list_achivements','EducationController@list_achivements');

//Graficas

Route::get('general_score_chart','ChartController@chart_gene_scores');

Route::get('general_attemps','ChartController@chart_gene_attemps');

//Rule of 3

Route::get('generate_rule3question','Rule3Builder@generate_question');