<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/daftar-member', 'UserController@daftarMember');
Route::get('/member-terdaftar', 'UserController@memberTerdaftar');
Route::post('/daftar-member/proses/{id}', 'UserController@prosesMemberBaru');
Route::post('/manager-kebun/create', 'UserController@createManager');
Route::get('/manager-kebun/{id}', 'UserController@dataManager');
Route::put('/manager-kebun/update/{id}', 'UserController@updateManager');
Route::delete('/manager-kebun/delete/{id}', 'UserController@deleteManager');
Route::get('/managerkebun', 'UserController@allManagerKebun');
Route::get('/kota', 'UserController@listKota');
Route::get('/sop', 'SopController@allSop');
Route::get('/sop/{id}', 'SopController@show');
Route::get('/sop/{id}/{tahapan_id}', 'SopController@showByTahapan');


Route::post('/admin/create', 'UserController@createAdmin');
Route::post('/login', 'UserController@login');

Route::get('/project-panduan/{project_tanam_id}', 'ProjectTanamController@showSopPanduan');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', 'UserController@getUser');
    Route::post('/logout', 'UserController@logout');

    Route::get('/tahapan', 'SopController@tahapan');
    
    Route::get('/project/{id}', 'ProjectTanamController@show');
    Route::get('/project', 'ProjectTanamController@showall');
    
    Route::get('/lahan', 'ProjectTanamController@showLahan');
    Route::get('/lahan/{id}', 'ProjectTanamController@detailLahan');
    
    Route::post('/project/create', 'ManagerKebunController@createProjectTanam');
    Route::post('/project/catat-harian', 'ManagerKebunController@catatHarianSop');
    Route::post('/project/catat-harian/panen', 'ManagerKebunController@catatHarianPanen');
    
    Route::get('/laporan', 'AdminController@laporanHarian');
    Route::get('/project/catat-harian/search', 'AdminController@searchLaporan');
    Route::get('/project/catat-harian/{catat_harian_id}', 'ManagerKebunController@showCatatHarian');

    Route::get('/tipe-jawaban', 'TipeJawabanController@index');
    
    Route::post('/komoditas', 'KomoditasController@create');
    Route::put('/komoditas/{komoditas_id}', 'KomoditasController@update');
    Route::delete('/komoditas/{komoditas_id}', 'KomoditasController@delete');
    Route::post('/sop', 'SopController@create');
    Route::put('/sop/{sop_id}', 'SopController@update');
    Route::delete('/sop/{sop_id}', 'SopController@delete');
    Route::put('/sop-all/{sop_id}', 'SopController@updateAll');
    
});

Route::get('/komoditas', 'KomoditasController@getJenisKomoditas');
Route::get('/komoditas/{komoditas_id}', 'KomoditasController@getJenisKomoditasById');

Route::get('/ucup', 'SopController@testing');