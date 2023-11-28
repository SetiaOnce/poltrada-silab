@extends('backend.layouts', ['activeMenu' => 'FAQ', 'activeSubMenu' => 'settings', 'title' => 'Frequesntly Asked Questions'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" />  
@stop


<!--begin::Card Form-->
<div class="card" id="card-form" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeForm" onclick="_closeCard('form_faq');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form class="form" autocomplete="off" id="form-data">
        <input type="hidden" name="id"><input type="hidden" name="methodform_data">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Row-->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <div class="form-group mt-4">
                        <label for="judul">Pertanyaan: <span class="text-danger">*</span></label>
                        <textarea name="judul" id="judul" class="required show-tick form-control" placeholder="Isikan pertanyaan faq"></textarea>
                    </div>
                    <div class="form-group mt-4">
                        <label for="isi">Jawaban: <span class="text-danger">*</span></label>
                        <textarea name="isi" id="isi" class="required show-tick form-control" placeholder="Isikan jawaban faq"></textarea>
                    </div>
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
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Data Frequesntly Asked Questions
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
            <table class="table table-rounded  table-hover align-middle table-row-bordered nowrap border" id="dt-faqs">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Judul</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Status</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Aksi</th>
                    </tr>
                </thead>
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
<script src="{{ asset('/script/backend/admin/faq.js') }}"></script>
@stop
@endsection