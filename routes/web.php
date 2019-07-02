<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api/v1/document', 'namespace' => 'Api'], function () {
	Route::group(['namespace' => 'V1'], function() {
		// создаем черновик документа
		Route::post('/', ['uses' => 'DocumentsController@draft', function(\App\Http\Requests\DocumentRequest $request){
			dd($request->all());
		}]); 
		// редактировать документ
		Route::patch('/{id}', ['uses' => 'DocumentsController@patch', function(\App\Http\Requests\DocumentRequest $request){
			dd($request->all());
		}]); 
		// опубликовать документ
		Route::post('/{id}/publish', ['uses' => 'DocumentsController@publish', function(\App\Http\Requests\DocumentRequest $request){
			dd($request->all());
		}]);
		// получить список документов с пагинацией
		Route::get('/', ['uses' => 'DocumentsController@index', function(\App\Http\Requests\DocumentRequest $request){
		
		}]);
		// получить документ по id
		Route::get('/{id}', 'DocumentsController@show'); 
	});
});