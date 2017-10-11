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

Route::get('jogo/{id}', 'MegaCabanaController@getJogo');
Route::get('jogos/{tipo}', 'MegaCabanaController@getJogos');

Route::post('criarJogo', 'MegaCabanaController@criarJogo');
Route::post('addJogador', 'MegaCabanaController@addJogador');
Route::post('attJogada', 'MegaCabanaController@attJogada');
Route::post('addSorteio', 'MegaCabanaController@addSorteio');
Route::delete('deletaJogo/{id}', 'MegaCabanaController@deletaJogo');
Route::delete('deletaJogada/{id}', 'MegaCabanaController@deletaJogada');