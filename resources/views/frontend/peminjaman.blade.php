@extends('frontend.layouts', ['activeMenu' => 'PEMINJAMAN', 'activeSubMenu' => '', 'title' => 'Peminjaman'])
@section('content')
@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/dist/plugins/Magnific-Popup/magnific-popup.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="https://www.google.com/recaptcha/api.js"></script>
@stop
<div class="row justify-content-between d-flex">
    <div class="col-md-4 hide-on-print">
        <div class="card mt-5">
            <!--begin::Wrapper-->
            <div class="card-body p-lg-15">
                <!--begin::Nav-->
                <div class="stepper-nav" id="Kt_Stepper">            
                    <!--begin::Step 1-->
                    <div class="stepper-item stepper1">
                        <!--begin::Wrapper-->  
                        <div class="d-flex align-items-center">
                            <!--begin::Icon-->
                            <div class="stepper-bg d-flex flex-center w-50px h-50px rounded-3 bg-primary bg-opacity-90 me-5">
                                <i class="stepper-icon bi bi-check-lg fw-bolder fs-1 text-white d-none"></i>                            
                                <span class="stepper-number fw-bolder fs-1 text-white">1</span>            
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">
                                    Biodata Diri
                                </h3>
                                <div class="stepper-desc fw-semibold text-muted">
                                    Mengisi form biodata diri
                                </div>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Wrapper--> 
                        <!--begin::Line-->
                        {{-- <div class="border-start-dashed border-primary h-40px"></div> --}}
                        <div class="h-40px"></div>
                        <!--end::Line-->                     
                    </div>
                    <!--end::Step 1-->     
                    <!--begin::Step 2-->
                    <div class="stepper-item stepper2">
                        <!--begin::Wrapper-->  
                        <div class="d-flex align-items-center">
                            <!--begin::Icon-->
                            <div class="stepper-bg d-flex flex-center w-50px h-50px rounded-3 bg-light-primary bg-opacity-90 me-5">
                                <i class="stepper-icon bi bi-check-lg fs-2 stepper-check d-none"></i>                            
                                <span class="stepper-number fw-bolder fs-1 text-primary">2</span>            
                            </div>
                            <!--end::Icon-->
                            <!--begin::Label-->
                            <div class="stepper-label">
                                <h3 class="stepper-title">
                                    Alat Praktek
                                </h3>
                                <div class="stepper-desc fw-semibold text-muted">
                                    Memilih alat praktek yang dipinjam
                                </div>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Wrapper-->                       
                    </div>
                    <!--end::Step 2-->     
                </div>
                <!--end::Nav-->
            </div>
            <!--end::Wrapper-->
        </div>
    </div>
    <div class="col-md-8">
        <!--begin::Card Step01-->
        <div class="card mt-5 hide-on-print" id="card-step01">     
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h4 class="fs-2x text-gray-800 w-bolder">                           
                        <i class="bi bi-ui-radios fs-1 text-gray-900 me-2"></i>Form Biodata Diri            
                    </h4>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Layout-->
                <div class="d-flex flex-column flex-lg-row">
                    <!--begin::Content-->
                    <div class="flex-lg-row-fluid">           
                        <!--begin::row-->
                        <div class="row justify-content-center">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <!--begin::Form-->
                                <form id="form-peminjaman" class="form" onsubmit="return false">
                                    <input type="hidden" name="idp">
                                    <div class="row d-flex justify-content-between border-6 border-dashed rounded p-4">
                                        <!--begin::col-->
                                        <div class="col-md-6">
                                            <!--begin::form group-->
                                            <div class="form-group mb-2" id="fg-fidLab" style="overflow:hidden;">
                                                <label class="col-form-label required fs-6" for="fid_lab">Laboratorium</label>
                                                <select id="fid_lab" name="fid_lab" class="form-control selectpicker show-tick" data-container="#card-step01" data-style="btn btn-secondary text-dark" data-live-search="true" title="-- Pilih Laboratorium --"></select>
                                                <div class="form-text">*) Harus diisi</div>
                                            </div>
                                            <!--end::form group-->
                                        </div>
                                        <!--end::col-->
                                        <!--begin::col-->
                                        <div class="col-md-6">
                                            <!--begin::form group-->
                                            <div class="form-group mb-2" id="fg-statusPeminjam" style="overflow:hidden;">
                                                <label class="col-form-label required fs-6" for="status_peminjaman">Status Peminjam</label>
                                                <select id="status_peminjaman" name="status_peminjaman" class="form-control selectpicker show-tick" data-container="#card-step01" data-style="btn btn-secondary text-dark" data-live-search="true" title="-- Pilih Status Peminjam --"></select>
                                                <div class="form-text">*) Harus diisi</div>
                                            </div>
                                            <!--end::form group-->
                                        </div>
                                        <!--end::col-->
                                        <!--begin::Input group-->
                                        <div class="form-group mb-2">
                                            <label class="col-form-label required fs-6" for="nik_notar">NIK/Notar</label>
                                            <input type="text" name="nik_notar" id="nik_notar" class="form-control form-control inputmax20" maxlength="20" placeholder="Isikan nik atau notar ..." />
                                            <div class="form-text">*) Maksimal: <code>20</code> Digit</div>
                                        </div>
                                        <!--end::Input group-->
                                        <div class="row" id="notFoundData"></div>
                                        <div class="row justify-content-between" id="hideForm1" style="display: none;">
                                            <div class="col-md-6">
                                                <!--begin::Input group-->
                                                <div class="form-group mb-2">
                                                    <label class="col-form-label required fs-6" for="nama_peminjam">Nama Peminjam</label>
                                                    <input type="text" name="nama_peminjam" id="nama_peminjam" class="form-control form-control form-input-disabled" maxlength="120" placeholder="Isikan nama peminjam ..." />
                                                    <div class="form-text">*) Maksimal: <code>120</code> Karakter</div>
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <div class="col-md-6">
                                                <!--begin::Input group-->
                                                <div class="form-group mb-2">
                                                    <label class="col-form-label required fs-6" for="telepon">Telepon</label>
                                                    <input type="text" name="telepon" id="telepon" class="form-control form-control inputmax20 form-input-disabled" maxlength="120" placeholder="Isikan telepon ..."/>
                                                    <div class="form-text">*) Maksimal: <code>15</code> Digit</div>
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <div class="col-md-12">
                                                <!--begin::Input group-->
                                                <div class="form-group mb-2">
                                                    <label class="col-form-label required fs-6" for="prodi_instansi">Prodi/Instansi</label>
                                                    <input type="text" name="prodi_instansi" id="prodi_instansi" class="form-control form-control-lg " maxlength="120" placeholder="Isikan prodi atau instansi ..." />
                                                    <div class="form-text">*) Maksimal: <code>120</code> Karakter</div>
                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                        </div>
                                        <div class="row justify-content-between">
                                            <div class="col-md-6">
                                                <!--begin::Input group-->
                                                <div class="form-group mb-2">
                                                    <label class="col-form-label required fs-6" for="tgl_peminjaman">Tanggal Peminjaman</label>
                                                    <input type="text" name="tgl_peminjaman" id="tgl_peminjaman" class="form-control mb-lg-0 " maxlength="250" placeholder="Isikan tanggal peminjaman ..." />
                                                    <div class="form-text">*) Input: <code>dd/mm/yyyy</code> </div>
                                                </div>
                                                <!--begin::Input group-->
                                            </div>
                                            <div class="col-md-6">
                                                <!--begin::Input group-->
                                                <div class="form-group mb-2">
                                                    <label class="col-form-label required fs-6" for="tgl_pengembalian">Tanggal Pengembalian</label>
                                                    <input type="text" name="tgl_pengembalian" id="tgl_pengembalian" class="form-control mb-lg-0 " maxlength="250" placeholder="Isikan tanggal pengembalian ..." />
                                                    <div class="form-text">*) Input: <code>dd/mm/yyyy</code> </div>
                                                </div>
                                                <!--begin::Input group-->
                                            </div>
                                        </div>
                                        {{-- <div class="mb-3 mt-5">
                                            <div class="g-recaptcha" data-sitekey="{{ config('app.gsite_key') }}"></div>
                                        </div> --}}
                                        <!--begin::row-->
                                        <div class="row my-5">
                                            <div class="col-lg-12 d-flex justify-content-center">
                                                <button type="button" id="btn-save" class="btn btn-sm btn-primary me-2"><i class="far fa-save"></i> Simpan Biodata</button>
                                            </div>
                                        </div>
                                        <!--end::row-->
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--end::row-->               
                    </div>  
                    <!--end::Content-->   
                </div>
                <!--end::Layout-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Card Step01-->   
        <!--begin::Card Step02-->
        <div class="card mt-5 hide-on-print" id="card-step02" style="display: none;">     
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h4 class="fs-2x text-gray-800 w-bolder">                           
                        <i class="bi bi-ui-radios fs-1 text-gray-900 me-2"></i> Form Pemilihan Alat Praktek           
                    </h4>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" onclick="_stepperCheck(1);"><i class="bi bi-backspace"></i> Kembali</button>
                        <!--begin::Group actions-->
                        <div class="d-flex  align-items-center" data-kt-docs-table-toolbar="selected">
                            <button type="button" class="btn btn-sm btn-success me-2" data-bs-toggle="tooltip" title="Pilih alat yang telah dichecklist!" id="btn-approve-alat"><i class="bi bi-check-all fs-3"></i> Pilih Alat</button>
                        </div>
                        <!--end::Group actions-->
                    </div>
                </div>
            </div>
            <!--end::Card header-->
            <div class="card-body">
                <div class="notice d-flex bg-light-success rounded border-success border border-dashed mb-4 p-6">
                    <!--begin::Icon-->
                    <i class="bi bi-megaphone fs-2tx text-success me-4"></i>        
                    <!--end::Icon-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-grow-1 ">
                        <!--begin::Content-->
                        <div class=" fw-semibold">
                            <div class="fs-6 text-gray-700">#Checklist alat yang ingin dipinjam lalu klik tombol <a class="fw-bold" href="javascript:void(0);">Pilih Alat</a>. untuk memasukkan kuantitas yang ingin dipinjam.</div>
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Wrapper-->  
                </div>
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-rounded  table-hover align-middle table-row-bordered border" id="datatableAlat">
                        <thead>
                            <tr class="fw-bolder text-uppercase bg-light fs-8">
                                <th class="border-bottom-2 border-gray-200 align-middle px-2">NAMA ALAT</th>
                                <th class="border-bottom-2 border-gray-200 align-middle px-2">KODE ALAT</th>
                                <th class="border-bottom-2 border-gray-200 align-middle px-2">FOTO</th>
                            </tr>
                        </thead>
                        <tbody class="fs-8" id="dt_peminjaman_alat"></tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Card Step02-->   
        <!--begin::Card Step03-->
        <div class="card mt-5" id="card-step03" style="display: none;">     
            <div class="card-body">
                <!--begin::Alert-->
                <div class="alert alert-dismissible bg-success d-flex flex-column flex-sm-row p-5 mb-10">
                     <!--begin::Icon-->
                    <i class="bi bi-check-square-fill fs-2hx text-light me-4 mb-5 mb-sm-0 hide-on-print"></i>
                    <!--end::Icon-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                        <!--begin::Title-->
                        <h4 class="mb-2 text-white hide-on-print">Selesai</h4>
                        <!--end::Title-->
                        <!--begin::Content-->
                        <span class="hide-on-print">Pengajuan peminjaman alat praktek berhasil, Mohon tunggu pengelola akan meninjau kembali pengajuan anda!</span>
                        <!--end::Content-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Alert-->
                <!--begin::Title-->
                <div class="card-header hide-on-print">
                    <div class="card-title hide-on-print">
                        <h3 class="text-gray-900 fw-bolder mb-3 hide-on-print">
                            Berikut Adalah Infomasi Peminjaman Anda :
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-danger me-2 hide-on-print" onclick="printContent()"><i class="bi bi-printer fs-3"></i> Print</button>
                    </div>
                </div>
                <!--end::Title-->
                <div class="row p-5 border rounded" id="detailPeminjaman">
                    <div class="mb-5">
                        <!--begin::Title-->
                        <h3 class="text-gray-900 fw-bolder mb-3">
                            <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Detail peminjaman
                        </h3>
                        <!--end::Title-->
                        <div class="row">
                            <table class="table table-rounded table-row-bordered border">
                                <tbody>
                                    <tr>
                                        <td style="width: 50px">Laboratorium</td>
                                        <td style="width: 200px">LABORATORIUM MANAJEMEN TRANSOPRTASI JALAN</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50px">Status Peminjam</td>
                                        <td style="width: 200px">INSTRUKTUR</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50px">Nama Peminjam</td>
                                        <td style="width: 200px">SUGISMAN PUTU DEV</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50px">Nik/Notar</td>
                                        <td style="width: 200px">2023062708211253008</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50px">Nomor Peminjaman</td>
                                        <td style="width: 200px">20231025180626</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50px">Tanggal Peminjaman</td>
                                        <td style="width: 200px">12-03-2024</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50px">Tanggal Pengembalian</td>
                                        <td style="width: 200px">12-03-2024</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!--begin::Title-->
                        <h3 class="text-gray-900 fw-bolder mb-3">
                            <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Alat yang dipinjam
                        </h3>
                        <!--end::Title-->
                        <div class="row">
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-rounded  table-hover align-middle table-row-bordered border" id="datatableAlat">
                                    <thead>
                                        <tr class="fw-bolder text-uppercase bg-light fs-8">
                                            <th class="border-bottom-2 border-gray-200 align-middle px-2">NAMA ALAT</th>
                                            <th class="border-bottom-2 border-gray-200 align-middle px-2">KODE ALAT</th>
                                            <th class="border-bottom-2 border-gray-200 align-middle px-2">JUMLAH</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-8">

                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Card Step03-->  
    </div>
</div>

<!--begin::modal-->
<div class="modal fade" tabindex="-1" id="modalApproveAlat" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg fs-1"></i>
                </div>
                <!--end::Close-->
            </div>
            <div class="modal-body">
                <form class="form" autocomplete="off" id="form-savealat" onsubmit="false">
                    <input type="hidden" name="fid_peminjaman">
                    <input type="hidden" name="array_alat_pinjaman">
                    <div class="table-responsive">
                        <!--begin::Datatable-->
                        <table class="table table-rounded  table-hover align-middle table-row-bordered border">
                            <thead>
                                <tr class="fw-bolder text-uppercase bg-light fs-8">
                                    <th class="border-bottom-2 border-gray-200 align-middle px-2">NAMA ALAT</th>
                                    <th class="border-bottom-2 border-gray-200 align-middle px-2">KODE ALAT</th>
                                    <th class="border-bottom-2 border-gray-200 align-middle px-2">JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold" id="dt-approveAlat">
                            </tbody>
                        </table>
                        <!--end::Datatable-->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-save-alat" class="btn btn-sm btn-primary me-2"><i class="far fa-save"></i> Simpan</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fas fa-times align-middle me-1"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::modal-->

@section('js')
<script src="{{ asset('/dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('dist/js/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('/script/frontend/peminjaman.js') }}"></script>
@stop
@endsection