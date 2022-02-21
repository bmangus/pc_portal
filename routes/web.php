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

Route::get('/atWorkflowApproval/{id}/{status}', 'ATWorkflowController@approveFromEmail');

Route::get('/22/workflowApproval/{app}/{token}/{status}', 'Workflow22Controller@approveFromEmail');

Route::get('/22/atWorkflowApproval/{id}/{status}', 'ATWorkflow22Controller@approveFromEmail');

Route::get('/public/extendedcare', 'ExtendedCare@index');

Route::post('/ExtendedCareRegistration', 'ExtendedCare@submit');


Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
]);

Route::middleware(['auth'])->prefix('/staff')->group(function() {

    Route::get('/', 'DashboardController@index');

    Route::get('/workflowBackendCounts/{username}', 'WorkflowController@getCounts');
    Route::get('/workflowBackendCounts', 'WorkflowController@getCounts');
    Route::get('/workflow', 'WorkflowController@index');
    Route::get('/workflowBackend/{app}', 'WorkflowController@requisitionsByApprover');
    Route::get('/btWorkflowBackend/{status}/{username}', 'WorkflowController@requisitionsByUserStatus');
    Route::get('/btWorkflowBackend/{status}', 'WorkflowController@requisitionsByUserStatus');
    Route::get('/workflowBackend/{app}/{status}', 'WorkflowController@requisitionsByStatus');
    Route::get('/workflowBackend/{app}/user/{username}', 'WorkflowController@requisitionsByApprover');
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
    Route::get('/workflowBackendComments/getCurrentPositionComment/{id}', 'WorkflowController@getCurrentPositionComment');
    Route::get('/workflowBackendComments/getCurrentPositionComment/{id}/{username}', 'WorkflowController@getCurrentPositionComment');

    //BT 2022
    Route::get('/22/workflowBackendCounts/{username}', 'Workflow22Controller@getCounts');
    Route::get('/22/workflowBackendCounts', 'Workflow22Controller@getCounts');
    Route::get('/22/workflow', 'Workflow22Controller@index');
    Route::get('/22/workflowBackend/{app}', 'Workflow22Controller@requisitionsByApprover');
    Route::get('/22/btWorkflowBackend/{status}/{username}', 'Workflow22Controller@requisitionsByUserStatus');
    Route::get('/22/btWorkflowBackend/{status}', 'Workflow22Controller@requisitionsByUserStatus');
    Route::get('/22/workflowBackend/{app}/{status}', 'Workflow22Controller@requisitionsByStatus');
    Route::get('/22/workflowBackend/{app}/user/{username}', 'Workflow22Controller@requisitionsByApprover');
    Route::get('/22/workflowBackend/{app}/user/{username}/test', 'Workflow22Controller@requisitionsByApprover');
    Route::get('/22/workflowBackendSync', 'Workflow22Controller@manualSync');
    Route::get('/22/workflowBackend/{app}/{id}/{status}', 'Workflow22Controller@requisitionAction');
    Route::get('/22/workflowBackend/{app}/{id}/{status}/{username}', 'Workflow22Controller@requisitionAction');
    Route::get('/22/workflowApi/getComments/{id}', 'Workflow22Controller@getComments');
    Route::post('/22/workflowApi/addComment/{id}', 'Workflow22Controller@postComment');
    Route::get('/22/workflowApproval/{app}/{token}/{status}', 'Workflow22Controller@approveFromEmail');
    Route::post('/22/workflowPDF/forward', 'Workflow22Controller@forwardPDF');
    Route::get('/22/workflowPDF/download/{id}', 'Workflow22Controller@viewPDF');
    Route::get('/22/workflowApprovers', 'Workflow22Controller@getApproverList');
    Route::post('/22/workflowReassign/{id}', 'Workflow22Controller@reassign');
    Route::get('/22/workflowBackendComments/getCurrentPositionComment/{id}', 'Workflow22Controller@getCurrentPositionComment');
    Route::get('/22/workflowBackendComments/getCurrentPositionComment/{id}/{username}', 'Workflow22Controller@getCurrentPositionComment');

    //AT
    Route::get('/ATworkflow', 'ATWorkflowController@index');
    Route::get('/workflowATBackendSync', 'ATWorkflowController@manualSync');
    Route::get('/atWorkflowBackend', 'ATWorkflowController@requisitionsByApprover');
    Route::get('/atWorkflowBackend/user/{username}','ATWorkflowController@requisitionsByApprover');
    Route::get('/atWorkflowBackend/status/{status}', 'ATWorkflowController@requisitionsByStatus');
    Route::get('/atWorkflowBackend/{id}/{status}', 'ATWorkflowController@requisitionAction');
    Route::get('/atWorkflowBackend/{id}/{status}/{username}', 'ATWorkflowController@requisitionAction');
    Route::post('/atWorkflowPDF/forward', 'ATWorkflowController@forwardPDF');
    Route::get('/atWorkflowPDF/download/{id}', 'ATWorkflowController@viewPDF');
    Route::get('/atWorkflowApproval/{id}/{status}', 'ATWorkflowController@approveFromEmail');
    Route::post('/atWorkflowApi/addComment/{id}', 'ATWorkflowController@postComment');

    //AT 2022
    Route::get('/22/ATworkflow', 'ATWorkflow22Controller@index');
    Route::get('/22/workflowATBackendSync', 'ATWorkflow22Controller@manualSync');
    Route::get('/22/atWorkflowBackend', 'ATWorkflow22Controller@requisitionsByApprover');
    Route::get('/22/atWorkflowBackend/user/{username}','ATWorkflow22Controller@requisitionsByApprover');
    Route::get('/22/atWorkflowBackend/status/{status}', 'ATWorkflow22Controller@requisitionsByStatus');
    Route::get('/22/atWorkflowBackend/{id}/{status}', 'ATWorkflow22Controller@requisitionAction');
    Route::get('/22/atWorkflowBackend/{id}/{status}/{username}', 'ATWorkflow22Controller@requisitionAction');
    Route::post('/22/atWorkflowPDF/forward', 'ATWorkflow22Controller@forwardPDF');
    Route::get('/22/atWorkflowPDF/download/{id}', 'ATWorkflow22Controller@viewPDF');
    Route::get('/22/atWorkflowApproval/{id}/{status}', 'ATWorkflow22Controller@approveFromEmail');
    Route::post('/22/atWorkflowApi/addComment/{id}', 'ATWorkflow22Controller@postComment');

    Route::get('/rehire', 'RehireController@index');
    Route::get('/fwo', 'WorkordersController@fwoIndex');
    Route::get('/fwo/manualSync', 'WorkordersController@sync');
});

Route::get('/test', 'WorkflowController@test');

