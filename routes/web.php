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

Route::get('/workflowApproval/{app}/{token}/{status}', 'WorkflowController@approveFromEmail');

Route::get('/public/extendedcare', 'ExtendedCare@index');

Route::post('/ExtendedCareRegistration', 'ExtendedCare@submit');


Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
]);

Route::middleware(['auth'])->prefix('/staff')->group(function() {
    Route::get('/workflow', 'WorkflowController@index');
    Route::get('/workflowBackend/{app}', 'WorkflowController@requisitionsByApprover');
    Route::get('/workflowBackend/{app}/{status}', 'WorkflowController@requisitionsByStatus');
    Route::get('/workflowBackend/{app}/user/{username}', 'WorkflowController@requisitionsByStatus');
    Route::get('/workflowBackend/{app}/user/{username}/test', 'WorkflowController@requisitionsByApprover');
    Route::get('/workflowBackendSync', 'WorkflowController@manualSync');
    Route::get('/workflowBackend/{app}/{id}/{status}', 'WorkflowController@requisitionAction');
    Route::get('/workflowBackend/{app}/{id}/{status}/{username}', 'WorkflowController@requisitionAction');
    Route::get('/workflowApi/getComments/{id}', 'WorkflowController@getComments');
    Route::post('/workflowApi/addComment/{id}', 'WorkflowController@postComment');
    Route::get('/workflowApproval/{app}/{token}/{status}', 'WorkflowController@approveFromEmail');
    Route::post('/workflowPDF/forward', 'WorkflowController@forwardPDF');
    Route::get('/workflowPDF/download/{id}', 'WorkflowController@viewPDF');
    Route::get('/workflowApprovers', 'WorkflowController@getApproverList');
    Route::post('/workflowReassign/{id}', 'WorkflowController@reassign');
    //AT
    Route::get('/ATworkflow', 'ATWorkflowController@index');
    Route::get('/workflowATBackendSync', 'ATWorkflowController@manualSync');
    Route::get('/atWorkflowBackend', 'ATWorkflowController@requisitionsByApprover');
    Route::get('/atWorkflowBackend/user/{username}','ATWorkflowController@requisitionsByApprover');
    Route::get('/atWorkflowBackend/status/{status}', 'ATWorkflowController@requisitionsByStatus');

});

Route::get('/test', 'WorkflowController@test');

