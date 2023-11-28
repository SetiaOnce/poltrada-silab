<?php

use App\Http\Controllers\Backend\Common\CommonController;
use App\Http\Controllers\Backend\Common\DataBukuTamuController;
use App\Http\Controllers\Backend\Common\LaporanAlatPeragaController;
use App\Http\Controllers\Backend\Common\LaporanJadwalPraktekController;
use App\Http\Controllers\Backend\Common\LaporanPemeriksaanController;
use App\Http\Controllers\Backend\Common\LaporanPeminjamanController;
use App\Http\Controllers\Backend\Common\LaporanPerawatanController;
use App\Http\Controllers\Backend\Common\ProfileUserController;
use Illuminate\Support\Facades\Route;

// for redirect page
Route::get('/dashboard', function () {
    if(!session()->get('login_akses')) { 
        return redirect('/login'); 
    } 
    $data['header_title'] = 'Dashboard';
    return view('backend.common.dashboard', $data);
});

Route::get('/profile_user', [ProfileUserController::class, 'index']);
Route::get('/laporan_transaksi', [LaporanTransaksiController::class, 'index']);

// for redirect alat laporan
Route::group(['prefix' => 'laporan'], function () {
    Route::get('/buku_tamu', [DataBukuTamuController::class, 'index']);
    Route::get('/data_alat_peraga', [LaporanAlatPeragaController::class, 'index']);
    Route::get('/perawatan', [LaporanPerawatanController::class, 'index']);
    Route::get('/pemeriksaan', [LaporanPemeriksaanController::class, 'index']);
    Route::get('/peminjaman', [LaporanPeminjamanController::class, 'index']);
    Route::get('/jadwal_praktek', [LaporanJadwalPraktekController::class, 'index']);
});
// for handle CommonController
Route::get('/load_user_profile', [CommonController::class, 'loaduserProfile']);
Route::get('/load_profile', [CommonController::class, 'loadProfile']);
Route::get('/load_app_profile_site', [CommonController::class, 'loadProfileApp']);
Route::post('/ajax_upload_imgeditor', [CommonController::class, 'upload_imgeditor'])->name('upload_imgeditor');
Route::get('/load_widget_dashboard', [CommonController::class, 'loadWidgetDashboard']);
Route::get('/load_transaksi_jatuh_tempo', [CommonController::class, 'loadTransaksiJatuhTempo']);
Route::post('/load_table_transaksi_jatuh_tempo', [CommonController::class, 'tableTransaksiJatuhTempo']);
Route::get('/load_trend_peminjaman_alat', [CommonController::class, 'trendPeminjamanAlat']);
Route::get('/ajax/get_data_alat_on_select', [CommonController::class, 'getDetailAlat']);
Route::get('/ajax/load_notification', [CommonController::class, 'loadNotification']);

// for handle buku tamu
Route::controller(DataBukuTamuController::class)->group(function(){
    Route::post('/ajax/load_data_buku_tamu', 'data');
});
// for handle laporan alat peraga
Route::controller(LaporanAlatPeragaController::class)->group(function(){
    Route::post('/ajax/load_laporan_alat_peraga', 'data');
    Route::get('/ajax/load_detail_alat_peraga', 'detailAlat');
});
// for handle laporan perawatan
Route::controller(LaporanPerawatanController::class)->group(function(){
    Route::post('/ajax/load_laporan_perawatan', 'data');
});
// for handle laporan pemeriksaan
Route::controller(LaporanPemeriksaanController::class)->group(function(){
    Route::post('/ajax/load_laporan_pemeriksaan', 'data');
});
// for handle laporan peminjaman
Route::controller(LaporanPeminjamanController::class)->group(function(){
    Route::post('/ajax/load_laporan_peminjaman', 'data');
    Route::get('/ajax/load_laporan_alat_pinjaman', 'modalAlatPinjaman');
});
// for handle laporan jadwal praktek
Route::controller(LaporanJadwalPraktekController::class)->group(function(){
    Route::post('/ajax/load_laporan_jadwal_praktek', 'data');
});