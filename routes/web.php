<?php

use Illuminate\Support\Facades\Route;

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
    return view('app');
});

Route::post('/create_user', 'UserController@createUser')->name('create_user');
Route::get('/retrieve_total_comments', 'CommentController@retrieveTotalComments')->name('retrieve_total_comments');
Route::post('/retrieve_comments', 'CommentController@retrieveComments')->name('retrieve_comments');
Route::post('/retrieve_child_comments', 'CommentController@retrieveChildComments')->name('retrieve_child_comments');
