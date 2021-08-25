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

Route::get('/', 'TasksController@index');

Route::resource('tasks','TasksController');

/*　自身の学習のために、以下の方法でも一旦記述

// CRUD
// タスクの個別詳細ページ表示
Route::get('tasks/{id}','TasksController@show');

// タスクの新規登録を処理（新規登録画面を表示するためのものではありません）
Route::post('tasks','TasksController@store');

// タスクの更新処理（編集画面を表示するためのものではありません）
Route::put('tasks/{id}','TasksController@update');

// タスクを削除
Route::delete('tasks/{id}','TasksController@destroy');

// index: showの補助ページ（タスク一覧を表示）
Route::get('tasks','TasksController@index')->name('tasks.index');

// create: 新規作成用のフォームページ
Route::get('tasks/create','TasksController@crate')->name('tasks.create');

// edit: 更新用のフォームページ
Route::get('tasks/{id}/edit','TasksController@edit')->neme('tasks.edit');

*/