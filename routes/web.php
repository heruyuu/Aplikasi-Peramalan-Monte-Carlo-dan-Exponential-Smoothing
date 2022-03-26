<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'LoginController@index')->name('login');
Route::post('/login', 'LoginController@login');

Route::post('/logout', 'LoginController@logout')->name('logout');
Route::get('/', 'HomeController@index')->middleware('auth');
Route::get('/test', 'HomeController@test')->middleware('auth');

Route::prefix('/satuan')->middleware('auth')->group(function () {
    Route::get('/', 'SatuanController@index');
    Route::get('/tambah', 'SatuanController@tambah');
    Route::get('/ubah/{id}', 'SatuanController@ubah');

	Route::post('/create', 'SatuanController@create_data');
    Route::put('/update/{id}', 'SatuanController@update_data');
    Route::delete('/delete/{id}', 'SatuanController@delete_data');
});

Route::prefix('/kategori')->middleware('auth')->group(function () {
    Route::get('/', 'KategoriController@index');
    Route::get('/tambah', 'KategoriController@tambah');
    Route::get('/ubah/{id}', 'KategoriController@ubah');

	Route::post('/create', 'KategoriController@create_data');
    Route::put('/update/{id}', 'KategoriController@update_data');
    Route::delete('/delete/{id}', 'KategoriController@delete_data');
});

Route::prefix('/produk')->middleware('auth')->group(function () {
    Route::get('/', 'ProdukController@index');
    Route::get('/tambah', 'ProdukController@tambah');
    Route::get('/ubah/{id}', 'ProdukController@ubah');

	Route::post('/create', 'ProdukController@create_data');
    Route::put('/update/{id}', 'ProdukController@update_data');
    Route::delete('/delete/{id}', 'ProdukController@delete_data');
});

Route::prefix('/customer')->middleware('auth')->group(function () {
    Route::get('/', 'customerController@index');
    Route::get('/tambah', 'customerController@tambah');
    Route::get('/ubah/{id}', 'customerController@ubah');

	Route::post('/create', 'customerController@create_data');
    Route::put('/update/{id}', 'customerController@update_data');
    Route::delete('/delete/{id}', 'customerController@delete_data');
});

Route::prefix('/transaksi')->middleware('auth')->group(function () {
    Route::get('/', 'TransaksiController@index');
    Route::get('/tambah', 'TransaksiController@tambah');
    Route::get('/ubah/{id}', 'TransaksiController@ubah');

    Route::get('/show/{id}', 'TransaksiController@show_data');
	Route::post('/create', 'TransaksiController@create_data');
    Route::put('/update/{id}', 'TransaksiController@update_data');
    Route::delete('/delete/{id}', 'TransaksiController@delete_data');
});

Route::prefix('/perbandingan')->middleware('auth')->group(function () {
    Route::get('/', 'PerbandinganController@index');
    Route::post('/show', 'PerbandinganController@detail');
});

Route::prefix('/user')->middleware('auth')->group(function () {
    Route::get('/', 'UserController@index');
    Route::get('/tambah', 'UserController@tambah');
    Route::get('/ubah/{id}', 'UserController@ubah');

	Route::post('/create', 'UserController@create_data');
    Route::put('/update/{id}', 'UserController@update_data');
    Route::delete('/delete/{id}', 'UserController@delete_data');
});