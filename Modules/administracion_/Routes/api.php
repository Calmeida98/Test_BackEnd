<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

       Route::group(['prefix'=>'administracion_','middleware' => []],function () {
               Route::get('/', function (){
                   return ["message"=>"administracion_"];
               });


        /*Persona*/
               Route::post('persona/validate', 'PersonaController@validate_model');
               Route::post('persona/update_multiple', 'PersonaController@update_multiple');
               Route::delete('persona/delete_by_id', 'PersonaController@deletebyid');
               Route::resource('persona', 'PersonaController');


    });
