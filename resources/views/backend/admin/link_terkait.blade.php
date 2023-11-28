@extends('backend.layouts', ['activeMenu' => 'LINK_TERKAIT', 'activeSubMenu' => 'settings', 'title' => 'Link Terkait'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/Magnific-Popup/magnific-popup.css') }}" rel="stylesheet" type="text/css" /> 
@stop

<!--begin::Card Form-->
<div class="card" id="card-form" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeFormSlide" onclick="_closeCard('form_slide');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form id="form-data" class="form" onsubmit="return false">
        <input type="hidden" id="id" name="id" />
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-bold fs-6" for="name">Nama</label>
                        <div class="col-lg-8">
                            <div class="input-group  mb-2">
                                <input type="text" class="form-control no-space" name="name" id="name" placeholder="Isikan nama link terkait..."/>
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-Thumnail">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6">Thumnail</label>
                        <div class="col-lg-8">
                            <input type="file" class="dropify-upl mb-3 mb-lg-0" id="thumnail" name="thumnail" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                            <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                            <div class="form-text">*) Max. size file: <code>2MB</code></div>
                            <div class="form-text">*) Rekomendasi thumnail: <small class="text-info">W=358px</small> dan <small class="text-info">H=79px</small> </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6" for="link_url">Link Url</label>
                        <div class="col-lg-8">
                            <div class="input-group  mb-2">
                                <span class="input-group-text"><i class="las la-external-link-alt fs-1"></i></span>
                                <input type="text" class="form-control no-space" name="link_url" id="link_url" placeholder="Isikan link url terkait ..." />
                            </div>
                            <div class="form-text">*) Contoh: <code>https://google.com</code></div>
                        </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-Status" style="display: none;">
                        <label class="col-lg-4 col-form-label required fw-bold fs-6" for="status">Status</label>
                        <div class="col-lg-8">
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" id="status" name="status" />
                                <label class="form-check-label" for="status"></label>
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="button" id="btn-save" class="btn btn-sm btn-primary me-2"><i class="far fa-save"></i> Simpan</button>
            <button type="button" id="btn-reset" class="btn btn-sm btn-secondary" onclick="_clearForm();"><i class="bi bi-arrow-clockwise fs-3"></i> Reset</button>
        </div>
        <!--end::Actions-->
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
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Data Link Terkait
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" id="btn-addSlide" onclick="_addData();"><i class="las la-plus fs-3"></i> Tambah</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded align-middle table-row-bordered border" id="dt-linkterkait">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Nama</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Thumnail</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Status</th>
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
<script src="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/dropify-master/js/dropify.min.js') }}"></script>
<script src="{{ asset('/script/backend/admin/link_terkait.js') }}"></script>
@stop
@endsection