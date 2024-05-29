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

//Route::get('/', 'WelcomeController@index');

//Route::get('home', 'HomeController@index');

Route::get('/', function () {
    return view('index'); // welcome
});

// Routes for REST version
Route::get('settings', 'StoreSettingsRestController@index');
Route::get('settings/findgrid', 'StoreSettingsRestController@findGrid');
Route::get('settings/find', 'StoreSettingsRestController@find');
Route::get('settings/create', 'StoreSettingsRestController@create');
Route::post('settings', 'StoreSettingsRestController@store');
Route::get('settings/{id}', 'StoreSettingsRestController@show');
Route::get('settings/{id}/edit', 'StoreSettingsRestController@edit');
Route::put('settings/{id}', 'StoreSettingsRestController@update');
Route::delete('settings/{id}', 'StoreSettingsRestController@destroy');

Route::get('lines', 'LineRestController@index');
Route::get('lines/findgrid', 'LineRestController@findGrid');
Route::get('lines/find', 'LineRestController@find');
Route::get('lines/create', 'LineRestController@create');
Route::post('lines', 'LineRestController@store');
Route::get('lines/{id}', 'LineRestController@show');
Route::get('lines/{id}/edit', 'LineRestController@edit');
Route::put('lines/{id}', 'LineRestController@update');
Route::delete('lines/{id}', 'LineRestController@destroy');

Route::get('groups', 'GroupRestController@index');
Route::get('groups/findgrid', 'GroupRestController@findGrid');
Route::get('groups/find', 'GroupRestController@find');
Route::get('groups/create', 'GroupRestController@create');
Route::post('groups', 'GroupRestController@store');
Route::get('groups/{id}', 'GroupRestController@show');
Route::get('groups/{id}/edit', 'GroupRestController@edit');
Route::put('groups/{id}', 'GroupRestController@update');
Route::delete('groups/{id}', 'GroupRestController@destroy');

Route::get('materials', 'MaterialRestController@index');
Route::get('materials/findgrid', 'MaterialRestController@findGrid');
Route::get('materials/find', 'MaterialRestController@find');
Route::get('materials/create', 'MaterialRestController@create');
Route::post('materials', 'MaterialRestController@store');
Route::get('materials/{id}', 'MaterialRestController@show');
Route::get('materials/{id}/edit', 'MaterialRestController@edit');
Route::put('materials/{id}', 'MaterialRestController@update');
Route::delete('materials/{id}', 'MaterialRestController@destroy');

Route::get('deliveries', 'DeliveryRestController@index');
Route::get('deliveries/findgrid', 'DeliveryRestController@findGrid');
Route::get('deliveries/find', 'DeliveryRestController@find');
Route::get('deliveries/create', 'DeliveryRestController@create');
Route::post('deliveries', 'DeliveryRestController@store');
Route::get('deliveries/{id}', 'DeliveryRestController@show');
Route::get('deliveries/{id}/edit', 'DeliveryRestController@edit');
Route::put('deliveries/{id}', 'DeliveryRestController@update');
Route::delete('deliveries/{id}', 'DeliveryRestController@destroy');

Route::get('deliv-items', 'DeliveryItemRestController@index');
Route::get('deliv-items/findgrid', 'DeliveryItemRestController@findGrid');
Route::get('deliv-items/find', 'DeliveryItemRestController@find');
Route::get('deliv-items/create', 'DeliveryItemRestController@create');
Route::post('deliv-items', 'DeliveryItemRestController@store');
Route::get('deliv-items/{id}', 'DeliveryItemRestController@show');
Route::get('deliv-items/{id}/edit', 'DeliveryItemRestController@edit');
Route::put('deliv-items/{id}', 'DeliveryItemRestController@update');
Route::delete('deliv-items/{id}', 'DeliveryItemRestController@destroy');

Route::get('machines', 'MachineRestController@index');
Route::get('machines/export', 'MachineRestController@export');
Route::get('machines/findgrid', 'MachineRestController@findGrid');
Route::get('machines/find', 'MachineRestController@find');
Route::get('machines/create', 'MachineRestController@create');
Route::post('machines', 'MachineRestController@store');
Route::get('machines/{id}', 'MachineRestController@show');
Route::get('machines/{id}/edit', 'MachineRestController@edit');
Route::put('machines/{id}', 'MachineRestController@update');
Route::delete('machines/{id}', 'MachineRestController@destroy');

Route::get('machine-work', 'MachineWorkRestController@index');
Route::get('machine-work/findgrid', 'MachineWorkRestController@findGrid');
Route::get('machine-work/find', 'MachineWorkRestController@find');
Route::get('machine-work/create', 'MachineWorkRestController@create');
Route::post('machine-work', 'MachineWorkRestController@store');
Route::get('machine-work/{id}', 'MachineWorkRestController@show');
Route::get('machine-work/{id}/edit', 'MachineWorkRestController@edit');
Route::put('machine-work/{id}', 'MachineWorkRestController@update');
Route::delete('machine-work/{id}', 'MachineWorkRestController@destroy');

Route::get('machine-work-items', 'MachineWorkItemRestController@index');
Route::get('machine-work-items/findgrid', 'MachineWorkItemRestController@findGrid');
Route::get('machine-work-items/find', 'MachineWorkItemRestController@find');
Route::get('machine-work-items/create', 'MachineWorkItemRestController@create');
Route::post('machine-work-items', 'MachineWorkItemRestController@store');
Route::get('machine-work-items/{id}', 'MachineWorkItemRestController@show');
Route::get('machine-work-items/{id}/edit', 'MachineWorkItemRestController@edit');
Route::put('machine-work-items/{id}', 'MachineWorkItemRestController@update');
Route::delete('machine-work-items/{id}', 'MachineWorkItemRestController@destroy');

Route::get('report-delivered', 'DeliveredReportRestController@index');
Route::get('report-delivered/find', 'DeliveredReportRestController@find');
Route::get('report-delivered/export', 'DeliveredReportRestController@export');

Route::get('report-work', 'WorkReportRestController@index');
Route::get('report-work/find', 'WorkReportRestController@find');
Route::get('report-work/export', 'WorkReportRestController@export');

Route::get('report-available', 'AvailableReportRestController@index');
Route::get('report-available/find', 'AvailableReportRestController@find');
Route::get('report-available/export', 'AvailableReportRestController@export');

// Routes for Lite version
Route::get('/lite/lines', 'lite\\LineController@index');
Route::match(array('GET', 'POST'), '/lite/lines/add', 'lite\\LineController@add');
Route::match(array('GET', 'POST'), '/lite/lines/edit/{id}', 'lite\\LineController@edit');
Route::match(array('GET', 'POST'), '/lite/lines/predelete/{id}', 'lite\\LineController@predelete');
Route::post('/lite/lines/reset/{id}', 'lite\\LineController@reset');
Route::post('/lite/lines/save', 'lite\\LineController@save');
Route::post('/lite/lines/delete', 'lite\\LineController@delete');
Route::post('/lite/lines/choose/{id}', 'lite\\LineController@choose');

Route::get('/lite/materials', 'lite\\MaterialController@index');
Route::match(array('GET', 'POST'), '/lite/materials/add', 'lite\\MaterialController@add');
Route::match(array('GET', 'POST'), '/lite/materials/edit/{id}', 'lite\\MaterialController@edit');
Route::match(array('GET', 'POST'), '/lite/materials/predelete/{id}', 'lite\\MaterialController@predelete');
Route::post('/lite/materials/reset/{id}', 'lite\\MaterialController@reset');
Route::post('/lite/materials/save', 'lite\\MaterialController@save');
Route::post('/lite/materials/delete', 'lite\\MaterialController@delete');
Route::post('/lite/materials/choose/{id}', 'lite\\MaterialController@choose');

Route::get('/lite/deliveries', 'lite\\DeliveryController@index');
Route::match(array('GET', 'POST'), '/lite/deliveries/add', 'lite\\DeliveryController@add');
Route::match(array('GET', 'POST'), '/lite/deliveries/edit/{id}', 'lite\\DeliveryController@edit');
Route::match(array('GET', 'POST'), '/lite/deliveries/predelete/{id}', 'lite\\DeliveryController@predelete');
Route::post('/lite/deliveries/reset/{id}', 'lite\\DeliveryController@reset');
Route::post('/lite/deliveries/save', 'lite\\DeliveryController@save');
Route::post('/lite/deliveries/delete', 'lite\\DeliveryController@delete');
Route::post('/lite/deliveries/choose/{id}', 'lite\\DeliveryController@choose');

Route::match(array('GET', 'POST'), '/lite/deliveries/di-add', 'lite\\DeliveryController@di_add');
Route::match(array('GET', 'POST'), '/lite/deliveries/di-edit/{index}', 'lite\\DeliveryController@di_edit');
Route::match(array('GET', 'POST'), '/lite/deliveries/di-predelete/{index}', 'lite\\DeliveryController@di_predelete');
//Route::post('/lite/deliveries/di-reset/{index}', 'lite\\DeliveryController@di_reset');
Route::post('/lite/deliveries/di-save', 'lite\\DeliveryController@di_save');
Route::post('/lite/deliveries/di-delete', 'lite\\DeliveryController@di_delete');


Route::get('/lite/di', 'lite\\DeliveryItemController@index');
Route::match(array('GET', 'POST'), '/lite/di/add', 'lite\\DeliveryItemController@add');
Route::match(array('GET', 'POST'), '/lite/di/edit/{id}', 'lite\\DeliveryItemController@edit');
Route::match(array('GET', 'POST'), '/lite/di/predelete/{id}', 'lite\\DeliveryItemController@predelete');
Route::post('/lite/di/reset/{id}', 'lite\\DeliveryItemController@reset');
Route::post('/lite/di/save', 'lite\\DeliveryItemController@save');
Route::post('/lite/di/delete', 'lite\\DeliveryItemController@delete');



Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
