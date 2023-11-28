@extends('backend.layouts', ['activeMenu' => 'PROFILE_APP', 'activeSubMenu' => 'settings', 'title' => 'Profile App'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
@stop

<!--begin::System Info-->
<div class="card mb-5 mb-xl-10" id="cardSiteInfo">
    <!--begin::Edit-->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-10">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-pen text-dark fs-2 me-3"></i>Edit Informasi Situs Web</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10 col-sm-12">
                <!--begin::Form-->
                <form id="form-editSiteInfo" class="form" onsubmit="return false">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-form-label required fs-6" for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control form-control-lg mb-3 mb-lg-0 " maxlength="255" placeholder="Isikan nama situs ..." />
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-form-label required fs-6" for="nama_alias">Nama Alias/ Nama Pendek</label>
                        <input type="text" name="nama_alias" id="nama_alias" class="form-control form-control-lg mb-3 mb-lg-0" maxlength="60" placeholder="Isikan nama alias / nama pendek situs ..." />
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-form-label required fs-6" for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control  form-control-lg mb-3 mb-lg-0" rows="2" maxlength="160" placeholder="Isikan deskripsi singkat situs ..."></textarea>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-form-label required fs-6" for="keyword">keyword/ Kata Kunci</label>
                        <select class="form-control form-control-lg mb-3 mb-lg-0" id="keyword" name="keyword[]" multiple></select>
                        <div class="form-text">*) Pisahkan keyword dengan tanda koma, contoh: <code>perpustakaan, poltrada, bali</code></div>
                        <div class="form-text">*) Maksimal: <code>10</code> kata kunci</div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <label class="col-form-label required fs-6" for="copyright">copyright</label>
                        <textarea name="copyright" id="copyright" class="form-control form-control-lg  mb-3 mb-lg-0 summernote"></textarea>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-frontend_logo">
                        <label class="col-form-label required fs-6">Logo Header Publik</label>
                        <input type="file" class="dropify-upl mb-3 mb-lg-0" id="logo_header_public" name="logo_header_public" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                        <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                        <div class="form-text">*) Rekomendasi Ukuran: <span class="text-primary">450 X 81 Pixel</span></div>
                        <div class="form-text">*) Max. size file: <code>2MB</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-login_bg">
                        <label class="col-form-label required fs-6">Banner Login</label>
                        <input type="file" class="dropify-upl mb-3 mb-lg-0" id="banner_login" name="banner_login" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                        <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                        <div class="form-text">*) Rekomendasi Ukuran: <span class="text-primary">W:540 X H:450 Pixel</span></div>
                        <div class="form-text">*) Max. size file: <code>2MB</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6 dropify-custom-dark" id="iGroup-backend_logo">
                        <label class="col-form-label required fs-6">Backend Logo</label>
                        <input type="file" class="dropify-upl mb-3 mb-lg-0" id="backend_logo" name="backend_logo" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                        <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                        <div class="form-text">*) Rekomendasi Ukuran: <span class="text-primary">450 X 81 Pixel</span></div>
                        <div class="form-text">*) Max. size file: <code>2MB</code></div>
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row mb-6" id="iGroup-backend_logo_icon">
                        <label class="col-form-label required fs-6">Logo Icon</label>
                        <input type="file" class="dropify-upl mb-3 mb-lg-0" id="backend_logo_icon" name="backend_logo_icon" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                        <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                        <div class="form-text">*) Rekomendasi Ukuran: <span class="text-primary">727 X 747 Pixel</span></div>
                        <div class="form-text">*) Max. size file: <code>2MB</code></div>
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
<script src="{{ asset('/script/backend/admin/profile_app.js') }}"></script>
@stop
@endsection