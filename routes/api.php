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
// Route::get('/sop/{id}/{tahapan_id}', 'SopController@showByTahapan');


Route::post('/admin/create', 'UserController@createAdmin');
Route::post('/login', 'UserController@login');

Route::get('/project-panduan/{project_tanam_id}', 'ProjectTanamController@showSopPanduan');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', 'UserController@getUser');
    Route::post('/logout', 'UserController@logout');

    Route::get('/tahapan', 'SopController@tahapan');
    
    Route::get('/project/{id}', 'ProjectTanamController@show');
    
    Route::get('/lahan', 'ProjectTanamController@showLahan');
    Route::get('/lahan/{id}', 'ProjectTanamController@detailLahan');
    
    Route::post('/project/catat-harian', 'ManagerKebunController@catatHarianSop');
    Route::post('/project/catat-harian/panen', 'ManagerKebunController@catatHarianPanen');
    
    Route::get('/laporan', 'AdminController@laporanHarian');
    Route::get('/project/catat-harian/search', 'AdminController@searchLaporan');
    Route::get('/project/catat-harian/{catat_harian_id}', 'ManagerKebunController@showCatatHarian');
    
    Route::get('/tipe-jawaban', 'TipeJawabanController@index');
    
    // ---------------------------MODIFIED FEATURES-------------------------------\\
    Route::post('/project/create', 'ManagerKebunController@createProjectTanam')->middleware('role_manager');
    Route::get('/project', 'ProjectTanamController@showall')->middleware('role_manager');
    
    // -----------------------NEW FEATURES FROM HERE-------------------------------- \\
    Route::post('/komoditas', 'KomoditasController@create');
    Route::put('/komoditas/{komoditas_id}', 'KomoditasController@update');
    Route::delete('/komoditas/{komoditas_id}', 'KomoditasController@delete');
    
    Route::post('/sop', 'SopController@create');
    Route::put('/sop/{sop_id}', 'SopController@update');
    Route::delete('/sop/{sop_id}', 'SopController@delete');
    Route::put('/sop-all/{sop_id}', 'SopController@updateAll');
    
    Route::post('/sop/{sop_id}/tahapan', 'TahapanController@create');   
    Route::put('/sop/{sop_id}/tahapan/{tahapan_id}', 'TahapanController@update');   
    Route::delete('/sop/{sop_id}/tahapan/{tahapan_id}', 'TahapanController@delete');   
    
    Route::post('/sop/{sop_id}/kegiatan', 'ItemPekerjaanController@addKegiatan');   
    Route::put('/sop/{sop_id}/kegiatan/{kegiatan_id}', 'ItemPekerjaanController@updateKegiatan');   
    Route::delete('/sop/{sop_id}/kegiatan/{kegiatan_id}', 'ItemPekerjaanController@deleteKegiatan');
    
    Route::post('/kegiatan/{kegiatan_id}/indikator', 'ItemPekerjaanController@addIndikator');   
    Route::put('/kegiatan/{kegiatan_id}/indikator/{indikator_id}', 'ItemPekerjaanController@updateIndikator');   
    Route::delete('/kegiatan/{kegiatan_id}/indikator/{indikator_id}', 'ItemPekerjaanController@deleteIndikator');   
    
    Route::delete('/project/{project_id}', 'ProjectTanamController@deleteProjectTanam')->middleware('role_manager');
    
    Route::post('/project/{project_id}/blok/create', 'ProjectTanamController@addBlokLahan')->middleware('role_manager');
    Route::put('/project/{project_id}/blok/{blok_id}', 'ProjectTanamController@updateBlokLahan')->middleware('role_manager');
    Route::delete('/project/{project_id}/blok/{blok_id}', 'ProjectTanamController@deleteBlokLahan')->middleware('role_manager');
    Route::get('/project/{project_id}/blok', 'ProjectTanamController@getBlokLahan')->middleware('role_manager');
    
    Route::post('/blok/{blok_id}/catat/create', 'PencatatanController@createCatatHarian')->middleware('role_manager');
    Route::delete('/blok/{blok_id}/catat/{catat_harian_id}', 'PencatatanController@deleteCatatHarian')->middleware('role_manager');
    Route::put('/blok/{blok_id}/catat/{catat_harian_id}', 'PencatatanController@updateCatatHarian')->middleware('role_manager');

    Route::post('/catat/{catat_harian_id}/item/create', 'PencatatanController@addCatatItem')->middleware('role_manager');
    Route::put('/catat/{catat_harian_id}/item/{catat_item_id}', 'PencatatanController@updateCatatItem')->middleware('role_manager');
    Route::delete('/catat/{catat_harian_id}/item/{catat_item_id}', 'PencatatanController@deleteCatatItem')->middleware('role_manager');

    Route::put('/item/{catat_item_id}/indikator/{indikator_id}', 'PencatatanController@updateCatatIndikator')->middleware('role_manager');

    Route::get('/notifikasi', 'PencatatanController@showNotifikasi')->middleware('role_manager');
});

Route::get('/komoditas', 'KomoditasController@getJenisKomoditas');
Route::get('/komoditas/{komoditas_id}', 'KomoditasController@getJenisKomoditasById');

Route::get('/sop/{sop_id}/tahapan', 'TahapanController@getTahapanBySopId');  

Route::get('/sop/{sop_id}/kegiatan', 'ItemPekerjaanController@getKegiatanBySop');
Route::get('/sop/{sop_id}/kegiatan/{kegiatan_id}', 'ItemPekerjaanController@getKegiatanById');

Route::get('/blok/{blok_id}/catat', 'PencatatanController@getCatatanHarian');
Route::get('/blok/{blok_id}/catat/{catat_harian_id}', 'PencatatanController@getCatatanHarianById');

Route::get('/ucup', 'SopController@testing');