<?php

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\InformasiController;
use App\Http\Controllers\Login\LoginAdminController;
use App\Http\Controllers\SelectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::controller(FrontendController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/profile', 'profile');
    Route::get('/faq', 'faq');
    Route::get('/buku-tamu', 'bukuTamu');
    Route::get('/view/{kode}', 'view');

    // ajax request for frontend page
    Route::get('/ajax_load_koleksi_buku', 'loadKoleksiBuku');
    Route::get('/ajax_load_detail_laboratorium', 'detailLaboratorium');
    Route::post('/ajax_load_alat_peraga', 'loadAlatPeraga');
    Route::get('/ajax_load_side_widget_buku', 'sideWidgetBuku');
    Route::get('/ajax_load_site_info', 'loadProfileApp');
    Route::get('/ajax_load_kebijakan_aplikasi', 'loadKebijakanAplikasi');
    Route::get('/ajax_load_profile', 'loadProfile');
    Route::get('/ajax_load_faq', 'loadFaq');
    Route::post('/ajax_save_jadwal_praktek', 'saveJadwalPraktek');
    Route::get('/ajax_get_data_pegawai', 'getDataPegawai');
    Route::post('/ajax_save_buku_tamu', 'saveBukuTamu');
});

Route::controller(InformasiController::class)->group(function(){
    Route::get('/jadwal_praktek/information', 'index');
    Route::get('/ajax/load_system_information', 'systemInformation');
    Route::post('/ajax/load_list_jadwal_praktek_now', 'listDataPraktekNow');
    Route::post('/ajax/load_list_jadwal_praktek_schedule', 'listDataPraktekSchedule');
});
Route::controller(LoginAdminController::class)->group(function(){
    Route::get('/login', 'index');
    Route::post('/ajax/request_login', 'login');
    Route::get('/ajax/load_system_login', 'laodSystemLogin');
});
Route::get('/reloadcaptcha', function () {
	return captcha_img();
});
Route::get('/logout', function () {
    Session::flush();
    return redirect('/'); 
});

//  ===========>> SELECT 2 START <<============== //
Route::group(['prefix' => 'select'], function () {
    Route::get('/ajax_getsatuan', [SelectController::class, 'satuan']);
    Route::get('/ajax_getlaboratorium', [SelectController::class, 'laboratorium']);
    Route::get('/ajax_getlokasi', [SelectController::class, 'lokasi']);
    Route::get('/ajax_getprodi', [SelectController::class, 'prodi']);
    Route::get('/ajax_getalatperaga', [SelectController::class, 'alatPeraga']);
    Route::get('/ajax_getstatuspeminjaman', [SelectController::class, 'statusPeminjaman']);
});
//  ===========>> SELECT 2 END <<============== //

// App Admin
Route::group(['prefix' => 'app_admin'], function () {
    require base_path('routes/common.php');
    Route::group(['middleware' => 'checkRole:silab-administrator'], function() {
        require base_path('routes/admin.php');
    });
    Route::group(['middleware' => 'checkRole:silab-kepala-unit-laboratorium'], function() {
        require base_path('routes/kanit.php');
    });
});
