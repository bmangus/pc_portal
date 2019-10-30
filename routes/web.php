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

Route::redirect('/', '/public/extendedcare');

Route::get('/public/extendedcare', 'ExtendedCare@index');

Route::post('/ExtendedCareRegistration', 'ExtendedCare@submit');

Route::get('/testEndpoint', function(){
   return view('workflow.index');
});



Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->prefix('/staff')->group(function() {

    Route::get('/workflow', 'WorkflowController@index');
    Route::get('/workflowBackend/{app}', 'WorkflowController@requisitionsByApprover');
    Route::get('/workflowBackend/{app}/{status}', 'WorkflowController@requisitionsByStatus');
    Route::get('/workflowBackend/{app}/user/{username}', 'WorkflowController@requisitionsByStatus');
    Route::get('/workflowBackendSync', 'WorkflowController@manualSync');
    Route::get('/workflowBackend/{app}/{id}/{status}', 'WorkflowController@requisitionAction');
    Route::get('/workflowBackend/{app}/{id}/{status}/{username}', 'WorkflowController@requisitionAction');

});
