@extends('backend.layouts', ['activeMenu' => 'KEBIJAKAN_APLIKASI', 'activeSubMenu' => 'settings', 'title' => 'Kebijakan Aplikasi'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
@stop

<!--begin::System Info-->
<div class="card mb-5 mb-xl-10" id="cardKebijakanApp">
    <!--begin::Edit-->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-10">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-pen text-dark fs-2 me-3"></i>Edit Kebijakan Aplikasi</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <!--begin::Form-->
                <form id="form-kebijakanApp" class="form" onsubmit="return false">
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-iconImage">
                        <label class="col-form-label required fw-bold fs-6">Icon Gambar</label>
                        <input type="file" class="dropify-upl mb-3 mb-lg-0" id="icon_image" name="icon_image" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                        <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                        <div class="form-text">*) Max. size file: <code>2MB</code></div>
                        <div class="form-text">*) Rekomendasi Ukuran: <small class="text-info">W=300px</small> dan <small class="text-info">H=300px</small> </div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-form-label required fs-6" for="judul">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control form-control-lg mb-3 mb-lg-0 " maxlength="255" placeholder="Isikan judul ..." />
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-form-label required fs-6" for="isi_kebijakan">Isi Kebijakan</label>
                        <textarea name="isi_kebijakan" id="isi_kebijakan" class="form-control form-control-lg  mb-3 mb-lg-0 summernote"></textarea>
                    </div>
                    <!--end::Input group-->
                    <div class="row mt-5">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" id="btn-save" class="btn btn-sm btn-primary me-2"><i class="far fa-save"></i> Simpan</button>
                            <button type="button" id="btn-reset" class="btn btn-sm btn-secondary" onclick="_loadProfileApp();"><i class="bi bi-arrow-clockwise fs-3"></i> Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--end::Edit-->
</div>
<!--end::Site Info-->

@section('js')
<script src="{{ asset('/dist/plugins/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/lang/summernote-id-ID.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/dropify-master/js/dropify.min.js') }}"></script>
<script src="{{ asset('/script/backend/admin/kebijakan_aplikasi.js') }}"></script>
@stop
@endsection