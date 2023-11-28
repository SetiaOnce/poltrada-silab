@extends('backend.layouts', ['activeMenu' => 'PERAWATAN', 'activeSubMenu' => 'alatperaga', 'title' => 'Data Perawatan Alat'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
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
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeForm" onclick="_closeCard('form_perawatan');"><i class="fas fa-times"></i> Tutup</button>
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
                    <div class="form-group mb-5" id="fg-fidAlatPeraga" style="overflow:hidden;">
                        <label class="col-form-label required fs-6" for="fid_alat_peraga">Alat Peraga</label>
                        <select id="fid_alat_peraga" name="fid_alat_peraga" class="form-control selectpicker show-tick" data-container="#card-form" data-live-search="true" title="-- Pilih Alat Peraga --"></select>
                        <span class="form-text text-muted">*) Pencarian aset: Kode dan Nama alat peraga</span>
                    </div>
                    <div class="row" id="dtl_dataAlat"></div>
                    <!--begin::Input group-->
                    <div class="form-group mb-5">
                        <label class="col-form-label required fs-6" for="tgl_perawatan">Tanggal Perawatan</label>
                        <input type="text" name="tgl_perawatan" id="tgl_perawatan" class="form-control mb-3 mb-lg-0 " maxlength="250" placeholder="Isikan tanggal perawatan ..." />
                        <div class="form-text">*) Input: <code>dd/mm/yy</code> </div>
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
                        <div class="form-text">*) Maksimal: <code>250</code> Karakter | Isi dengan: <code>(-)</code> jika tidak ada</div>
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
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Data Perawatan Alat Peraga
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" title="Tambah data Baru!" onclick="_addData();"><i class="las la-plus fs-3"></i> Tambah</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded  table-hover align-middle table-row-bordered border gy-3 gs-3" id="dt-perawatanAlat">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-dark text-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Alat Peraga</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Tgl.Perawatan</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Keterangan</th>
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
<script src="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/lang/summernote-id-ID.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/dropify-master/js/dropify.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/script/backend/admin/perawatan.js') }}"></script>
@stop
@endsection