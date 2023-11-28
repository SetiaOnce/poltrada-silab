@extends('backend.layouts', ['activeMenu' => 'PROFILE_LABORATORIUM', 'activeSubMenu' => 'settings', 'title' => 'Profile Laboratorium'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
@stop

<!--begin::System Info-->
<div class="card mb-5 mb-xl-10" id="cardProfile">
    <!--begin::Edit-->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-10">
            <h3 class="fw-bolder m-0 mb-3"><i class="las la-pen text-dark fs-2 me-3"></i>Edit Profile Laboratorium</h3>
        </div>
        <!--begin::Form-->
        <form id="form-editProfileLaboratorium" class="form" onsubmit="return false">
            <!--begin::Input group-->
            <div class="row mb-6">
                <div class="col-lg-12">
                    <textarea name="profile_laboratorium" id="profile_laboratorium" class="form-control form-control-lg form-control-solid mb-3 mb-lg-0 summernote"></textarea>
                </div>
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
    <!--end::Edit-->
</div>
<!--end::Site Info-->

@section('js')
<script src="{{ asset('/dist/plugins/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/lang/summernote-id-ID.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/dropify-master/js/dropify.min.js') }}"></script>
<script src="{{ asset('/script/backend/admin/profile_laboratorium.js') }}"></script>
@stop
@endsection