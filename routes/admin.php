<?php

use App\Http\Controllers\Backend\Admin\BannersController;
use App\Http\Controllers\Backend\Admin\FaqController;
use App\Http\Controllers\Backend\Admin\KebijakanAplikasiController;
use App\Http\Controllers\Backend\Admin\LinkTerkaitController;
use App\Http\Controllers\Backend\Admin\ProfileAppController;
use App\Http\Controllers\Backend\Admin\ProfileLaboratoriumController;
use App\Http\Controllers\Backend\Admin\LokasiLabController;
use App\Http\Controllers\Backend\Admin\NamaLabController;
use App\Http\Controllers\Backend\Admin\DataAlatPeragaController;
use App\Http\Controllers\Backend\Admin\JadwalPraktekController;
use App\Http\Controllers\Backend\Admin\PemeriksaanController;
use App\Http\Controllers\Backend\Admin\PeminjamanController;
use App\Http\Controllers\Backend\Admin\PerawatanController;
use App\Http\Controllers\Backend\Admin\SatuanController;
use Illuminate\Support\Facades\Route;

// for redirect page lokasi laboratorium
Route::get('/lokasi_laboratorium', [LokasiLabController::class, 'index']);
// for redirect page nama laboratorium
Route::get('/nama_laboratorium', [NamaLabController::class, 'index']);
// for redirect peminjaman
Route::get('/peminjaman', [PeminjamanController::class, 'index']);
// for redirect jadwal praktek
Route::get('/jadwal_praktek', [JadwalPraktekController::class, 'index']);
// for redirect alat peraga
Route::group(['prefix' => 'alat_peraga'], function () {
    Route::get('/data_alat_peraga', [DataAlatPeragaController::class, 'index']);
    Route::get('/perawatan', [PerawatanController::class, 'index']);
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'index']);
});
// for redirect page management app
Route::group(['prefix' => 'management_app'], function () {
    Route::get('/profiles_app', [ProfileAppController::class, 'index']);
    Route::get('/profile_laboratorium', [ProfileLaboratoriumController::class, 'index']);
    Route::get('/banners', [BannersController::class, 'index']);
    Route::get('/faq', [FaqController::class, 'index']);
    Route::get('/kebijakan_aplikasi', [KebijakanAplikasiController::class, 'index']);
    Route::get('/link_terkait', [LinkTerkaitController::class, 'index']);
    Route::get('/satuan', [SatuanController::class, 'index']);
});

// for handle profile app
Route::get('/ajax/load_profile_app', [ProfileAppController::class, 'loadProfileApp']);
Route::post('/ajax/profile_app_update', [ProfileAppController::class, 'profileAppUpdate']);
// for handle profile laboratorium
Route::get('/ajax/load_profile_laboratorium', [ProfileLaboratoriumController::class, 'loadProfileLaboratorium']);
Route::post('/ajax/profile_laboratorium_update', [ProfileLaboratoriumController::class, 'profileLaboratoriumUpdate']);
// for handle kebijakan aplikasi
Route::get('/ajax/load_kebijakan_aplikasi', [KebijakanAplikasiController::class, 'loadKebijakanAplikasi']);
Route::post('/ajax/kebijakan_aplikasi_update', [KebijakanAplikasiController::class, 'kebijakanAplikasiUpdate']);

// for handle lokasi laboratorium
Route::controller(LokasiLabController::class)->group(function(){
    Route::post('/ajax/load_lokasi_laboratorium', 'data');
    Route::get('/ajax/lokasi_laboratorium_edit', 'edit');
    Route::post('/ajax/lokasi_laboratorium_save', 'store');
    Route::post('/ajax/lokasi_laboratorium_update', 'update');
    Route::post('/ajax/lokasi_laboratorium_update_status', 'updateStatus');
});
// for handle nama laboratorium
Route::controller(NamaLabController::class)->group(function(){
    Route::post('/ajax/load_nama_laboratorium', 'data');
    Route::get('/ajax/nama_laboratorium_edit', 'edit');
    Route::post('/ajax/nama_laboratorium_save', 'store');
    Route::post('/ajax/nama_laboratorium_update', 'update');
    Route::post('/ajax/nama_laboratorium_update_status', 'updateStatus');
});
// for handle data alat peraga
Route::controller(DataAlatPeragaController::class)->group(function(){
    Route::post('/ajax/load_alat_peraga', 'data');
    Route::get('/ajax/alat_peraga_edit', 'edit');
    Route::post('/ajax/alat_peraga_save', 'store');
    Route::post('/ajax/alat_peraga_update', 'update');
    Route::post('/ajax/alat_peraga_update_status', 'updateStatus');
    Route::get('/alat_peraga_barcode/{id}/{ukuran}', 'barcode');
});
// for handle peminjaman alat
Route::controller(PeminjamanController::class)->group(function(){
    Route::post('/ajax/load_peminjaman_alat', 'data');
    Route::get('/ajax/peminjaman_alat_edit', 'edit');
    Route::post('/ajax/peminjaman_alat_save', 'store');
    Route::post('/ajax/peminjaman_alat_update', 'update');
    Route::get('/ajax/check_data_taruna_dosen', 'checkTarunaDosen');
    Route::get('/peminjaman_alat_print_pdf/{id}', 'cetakPdf');
    Route::get('/ajax/load_modal_alat_pinjaman', 'modalAlatPinjaman');
    
    // route for peminjaman alat
    Route::get('/ajax/load_header_pinjaman', 'loadHeaderPinjaman');
    Route::post('/ajax/load_alat_pinjaman', 'alatPinjaman');
    Route::post('/ajax/check_alat_approve', 'checkApproveAlat');
    Route::post('/ajax/alat_pinjaman_save', 'alatPinjamanSave');
    
    // route for pengembalian alat
    Route::get('/ajax/load_pengembalian_alat_detail', 'loadPengembalianBukuDetail');
    Route::post('/ajax/confirm_pengembalian_alat', 'confirmPengembalian');
});
// for handle data perawatan
Route::controller(PerawatanController::class)->group(function(){
    Route::post('/ajax/load_perawatan', 'data');
    Route::get('/ajax/perawatan_edit', 'edit');
    Route::post('/ajax/perawatan_save', 'store');
    Route::post('/ajax/perawatan_update', 'update');
    Route::post('/ajax/perawatan_destroy', 'destroy');
});
// for handle data pemeriksaan
Route::controller(PemeriksaanController::class)->group(function(){
    Route::post('/ajax/load_pemeriksaan', 'data');
    Route::get('/ajax/pemeriksaan_edit', 'edit');
    Route::post('/ajax/pemeriksaan_save', 'store');
    Route::post('/ajax/pemeriksaan_update', 'update');
    Route::post('/ajax/pemeriksaan_destroy', 'destroy');
});
// for handle peminjaman alat peraga
Route::controller(PeminjamanController::class)->group(function(){
    Route::post('/ajax/load_peminjaman', 'data');
    Route::get('/ajax/peminjaman_edit', 'edit');
    Route::post('/ajax/peminjaman_save', 'store');
    Route::post('/ajax/peminjaman_update', 'update');
    Route::post('/ajax/peminjaman_destroy', 'destroy');
    Route::get('/ajax/load_modal_buku_pinjaman', 'modalBukuPinjaman');

    Route::get('/ajax/load_header_pinjaman', 'loadHeaderPinjaman');
    Route::post('/ajax/load_buku_pinjaman', 'bukuPinjaman');
    Route::post('/ajax/buku_pinjaman_save', 'bukuPinjamanSave');
    Route::get('/ajax/load_pengembalian_buku_detail', 'loadPengembalianBukuDetail');
    Route::post('/ajax/confirm_pengembalian_buku', 'confirmPengembalian');
});
// for handle pengajuan jadwal praktek
Route::controller(JadwalPraktekController::class)->group(function(){
    Route::post('/ajax/load_jadwal_praktek', 'data');
    Route::post('/ajax/jadwal_praktek_send_action', 'sendAction');
});
// for setting banner sliders
Route::controller(BannersController::class)->group(function(){
    Route::post('/ajax/load_banner', 'data');
    Route::get('/ajax/banner_edit', 'edit');
    Route::post('/ajax/banner_save', 'store');
    Route::post('/ajax/banner_update', 'update');
    Route::post('/ajax/banner_update_status', 'updateStatus');
    Route::post('/ajax/banner_destroy', 'destroy');
});
// for setting faq
Route::controller(FaqController::class)->group(function(){
    Route::post('/ajax/load_faq', 'data');
    Route::get('/ajax/faq_edit', 'edit');
    Route::post('/ajax/faq_save', 'store');
    Route::post('/ajax/faq_update', 'update');
    Route::post('/ajax/faq_update_status', 'updateStatus');
    Route::post('/ajax/faq_destroy', 'destroy');
});
// for setting links terkait
Route::controller(LinkTerkaitController::class)->group(function(){
    Route::get('/ajax/load_link_terkait', 'data');
    Route::get('/ajax/link_terkait_edit', 'edit');
    Route::post('/ajax/link_terkait_save', 'store');
    Route::post('/ajax/link_terkait_update', 'update');
    Route::post('/ajax/link_terkait_update_status', 'updateStatus');
    Route::post('/ajax/link_terkait_destroy', 'destroy');
});
// for setting satuan
Route::controller(SatuanController::class)->group(function(){
    Route::post('/ajax/load_satuan', 'data');
    Route::get('/ajax/satuan_edit', 'edit');
    Route::post('/ajax/satuan_save', 'store');
    Route::post('/ajax/satuan_update', 'update');
    Route::post('/ajax/satuan_update_status', 'updateStatus');
});
