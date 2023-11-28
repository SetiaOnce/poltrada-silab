@extends('backend.layouts', ['activeMenu' => 'DATA_ALAT_PERAGA', 'activeSubMenu' => 'alatperaga', 'title' => 'Data Alat Peraga'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('dist/plugins/bootstrap-file-input/css/fileinput.min.css') }}" rel="stylesheet">
<link href="{{ asset('dist/plugins/bootstrap-file-input/themes/explorer-fas/theme.css') }}" rel="stylesheet">
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" />  
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/Magnific-Popup/magnific-popup.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

<!--begin::Card Form-->
<div class="card" id="card-form" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeForm" onclick="_closeCard('form_alat_peraga');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form class="form" autocomplete="off" id="form-data" onsubmit="false">
        <input type="hidden" name="id"><input type="hidden" name="methodform_data">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Row-->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <!--begin::form group-->
                    <div class="form-group mb-5" id="fg-fidLab" style="overflow:hidden;">
                        <label class="col-form-label required fs-6" for="fid_lab">Laboratorium</label>
                        <select id="fid_lab" name="fid_lab" class="form-control selectpicker show-tick" data-container="#card-form" data-live-search="true" title="-- Pilih laboratorium --"></select>
                    </div>
                    <!--begin::form group-->
                    <!--begin::form group-->
                    <div class="form-group mb-5" id="fg-fidLokasi" style="overflow:hidden;">
                        <label class="col-form-label required fs-6" for="fid_lokasi">Lokasi</label>
                        <select id="fid_lokasi" name="fid_lokasi" class="form-control selectpicker show-tick" data-container="#card-form" data-live-search="true" title="-- Pilih lokasi --"></select>
                    </div>
                    <!--begin::form group-->
                    <!--begin::form group-->
                    <div class="form-group mb-5">
                        <label class="col-form-label required fs-6" for="kode_alat_peraga">Kode Alat Peraga</label>
                        <input type="text" name="kode_alat_peraga" id="kode_alat_peraga" class="form-control mb-3 mb-lg-0 " maxlength="50" placeholder="Isikan kode alat peraga ..." />
                        <div class="form-text">*) Contoh input: <code>ABCDXXX</code></div>
                    </div>
                    <!--begin::form group-->
                    <!--begin::form group-->
                    <div class="form-group mb-5">
                        <label class="col-form-label required fs-6" for="nama_alat_peraga">Nama Alat Peraga</label>
                        <input type="text" name="nama_alat_peraga" id="nama_alat_peraga" class="form-control mb-3 mb-lg-0 " maxlength="120" placeholder="Isikan nama alat peraga ..." />
                        <div class="form-text">*) Maksimal: <code>120</code> Karakter</div>
                    </div>
                    <!--begin::form group-->
                    <!--begin::form group-->
                    <div class="form-group mb-5">
                        <label class="col-form-label required fs-6" for="jumlah">Jumlah</label>
                        <input type="text" name="jumlah" id="jumlah" class="form-control mb-3 mb-lg-0 " maxlength="120" placeholder="Isikan jumlah alat..." />
                        <div class="form-text">*) Input: <code>Angka</code></div>
                    </div>
                    <!--begin::form group-->
                    <!--begin::form group-->
                    <div class="form-group mb-5" id="fg-fidSatuan" style="overflow:hidden;">
                        <label class="col-form-label required fs-6" for="fid_satuan">Satuan</label>
                        <select id="fid_satuan" name="fid_satuan" class="form-control selectpicker show-tick" data-container="#card-form" data-live-search="true" title="-- Pilih satuan --"></select>
                    </div>
                    <!--begin::form group-->
                    <!--begin::Input group-->
                    <div class="form-group mb-5">
                        <label class="col-form-label required fs-6" for="kondisi">Kondisi</label>
                        <input type="text" name="kondisi" id="kondisi" class="form-control mb-3 mb-lg-0 " maxlength="250" placeholder="Isikan kondisi alat ..." />
                        <div class="form-text">*) Maksimal: <code>250</code> Karakter</div>
                    </div>
                    <!--begin::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-foto">
                        <label class="col-form-label required fw-bold fs-6">Foto</label>
                        <input type="file" class="dropify-upl mb-3 mb-lg-0" id="foto" name="foto" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                        <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                        <div class="form-text">*) Max. size file: <code>2MB</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="form-group mt-4">
                        <label class="col-form-label required fs-6" for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control form-control-lg  mb-3 mb-lg-0" placeholder="Isikan keterangan...."></textarea>
                        <div class="form-text">*) Maksimal: <code>250</code> Karakter</div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-Status" style="display: none;">
                        <label class=" col-form-label required fw-bold fs-6" for="status">Status</label>
                        <div class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" id="status" name="status" />
                            <label class="form-check-label" for="status"></label>
                        </div>
                    </div>
                    <!--end::Input group-->
                    <div class="row mt-5">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" id="btn-save" class="btn btn-sm btn-primary me-2"><i class="far fa-save"></i> Simpan</button>
                            <button type="button" id="btn-reset" class="btn btn-sm btn-secondary" onclick="_clearForm();"><i class="bi bi-arrow-clockwise fs-3"></i> Reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
    </form>
    <!--end::Form-->
</div>
<!--end::Card Form-->
<!--begin::List Table Data-->
<div class="card shadow" id="card-data">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Data Alat Peraga
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                {{-- <div class="m-0 me-2">
                    <!--begin::Menu toggle-->
                    <a href="#" class="btn btn-sm btn-info font-weight-bolder" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">             
                        <i class="bi bi-funnel text-light me-1"></i> Filter
                    </a>
                    <!--end::Menu toggle-->
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-500px w-md-400px" data-kt-menu="true" id="kt_menu_64e5cdce6c4af" style="">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">Filter Data Alat Peraga</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Menu separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Menu separator-->
                        <!--begin::Form-->
                        <div class="px-7 py-5">
                            <div class="form-group mb-5">
                                <label class="fw-bolder" for="filterDtKategori">Kategori: </label>
                                <select id="filterDtKategori" name="filterDtKategori" class="form-control selectpicker show-tick" data-live-search="true" title="Filter kategori..." data-style="btn-secondary text-dark"></select>
                            </div>
                            <div class="form-group">
                                <label class="fw-bolder" for="filterDtStatus">Status: </label>
                                <select id="filterDtStatus" name="filterDtStatus" class="form-control selectpicker show-tick" data-style="btn-secondary" title="-- Semua --">
                                    <option data-icon="mdi mdi-file-document font-size-lg bs-icon" value="5" selected>Semua</option>
                                    <option data-icon="mdi mdi-earth font-size-lg bs-icon" value="1" >Publik</option>
                                    <option data-icon="mdi mdi-file font-size-lg bs-icon" value="0">Draft</option>
                                    <option data-icon="mdi mdi-delete-variant font-size-lg bs-icon" value="100">Sampah</option>
                                </select>
                            </div>
                            <div class="separator border-gray-200 mb-2 mt-4"></div>
                            <div class="d-flex justify-content-end">        
                                <button type="submit" class="btn btn-sm btn-danger me-2" id="btn-resetFilter" data-kt-menu-dismiss="false"><i class="bi bi-arrow-clockwise"></i> Reset</button>
                                <button type="submit" class="btn btn-sm btn-primary" id="btn-applyFilter" data-kt-menu-dismiss="true">Apply</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Form-->
                    </div>
                    <!--end::Menu 1-->        
                </div> --}}
                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" title="Tambah data Baru!" onclick="_addData();"><i class="las la-plus fs-3"></i> Tambah</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded  table-hover align-middle table-row-bordered border gy-3 gs-3" id="dt-alatPeraga">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-dark text-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Nama Laboratorium</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Kode Alat</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Nama Alat</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Jumlah</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Satuan</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-8"></tbody>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::List Table Data-->

@section('js')
<script src="{{ asset('/dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dist/plugins/bootstrap-file-input/js/plugins/piexif.js') }}"></script>
<script type="text/javascript" src="{{ asset('dist/plugins/bootstrap-file-input/js/plugins/sortable.js') }}"></script>
<script type="text/javascript" src="{{ asset('dist/plugins/bootstrap-file-input/js/fileinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dist/plugins/bootstrap-file-input/themes/fas/theme.js') }}"></script>
<script type="text/javascript" src="{{ asset('dist/plugins/bootstrap-file-input/themes/explorer-fas/theme.js') }}"></script>
<script src="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/lang/summernote-id-ID.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/dropify-master/js/dropify.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/script/backend/admin/data_alat_peraga.js') }}"></script>
@stop
@endsection