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


Route::get('/', 'TemplateController@index');

/*Route::get('storage/{filename}', function($filename = null)
{
    $path = storage_path().'/'.'app'.'/public/'.$filename;
    
    if (file_exists($path)) {
        return Response::download($path);
    }
})->where('filename', '[A-Za-z0-9\-\_\./]+');*/

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('cart/{id}', 'TemplateController@view');

Route::post('like/{id}', 'TemplateController@like');

Route::get('images-upload', 'HomeController@imagesUpload');

Route::post('images-upload', 'HomeController@imagesUploadPost')->name('images.upload');

Route::group(['prefix' => 'admin'], function () {
	Voyager::routes();
    $namespacePrefix = '\\'.config('voyager.controllers.namespace').'\\';

    Route::get('{table}/{id}/create', ['uses' => 'FrameController@createnew', 'as' => 'createnew']);
    Route::post('frame/{id}/update', ['uses' => 'FrameController@update', 'as' => 'update']);
    Route::post('cardtemplate/{id}/update', ['uses' => 'CardtemplateController@update', 'as' => 'update']);
    Route::delete('cardtemplate/{id}/deleteframe', ['uses' => 'CardtemplateController@deleteframe', 'as' => 'deleteframe']);
//    Route::get('cardtemplate/{id}/deleteframe', ['uses' => 'CardtemplateController@deleteframe', 'as' => 'deleteframe']);
    Route::get('cardtemplate/{id}/duplicate', ['uses' => 'CardtemplateController@duplicate', 'as' => 'duplicate']);
});


Route::get('personal/new/{id}', 'PersonalController@newuser');
Route::post('personal/new/{id}', 'PersonalController@newuser');
Route::get('personal/login', 'PersonalController@login')->name('login');
Route::post('personal/login', 'PersonalController@login');
Route::get('personal/cards', 'PersonalController@cards')->middleware(['auth', 'App\Http\Middleware\RegularUser'])->name('personal.cards');
Route::get('personal/logout', 'PersonalController@logout')->middleware(['auth', 'App\Http\Middleware\RegularUser'])->name('logout');
Route::get('personal/edit/{id}', 'PersonalController@editcard')->middleware(['auth', 'App\Http\Middleware\RegularUser'])->name('personal.edit');
Route::post('personal/edit/{id}', 'PersonalController@editcard')->middleware(['auth', 'App\Http\Middleware\RegularUser']);

Route::get('{alias}', 'TemplateController@viewalias');
Route::get('{alias}/{subalias}', 'TemplateController@viewsubalias');
Route::get('cart/{id}/{subalias}', 'TemplateController@viewsubalias');

Route::post('paypal', 'PaymentController@payWithpaypal')->name('paypal');
Route::get('status', 'PaymentController@getPaymentStatus')->name('status');
