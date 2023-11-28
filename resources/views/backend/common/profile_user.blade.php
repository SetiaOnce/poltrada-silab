@extends('backend.layouts', ['activeMenu' => 'PROFILE_USER', 'activeSubMenu' => '', 'title' => 'Profile User'])
@section('content')
<div class="notice d-flex bg-light-success rounded border-success border border-dashed mb-4 p-6">
    <!--begin::Icon-->
    <i class="bi bi-megaphone fs-2tx text-success me-4"></i>        
    <!--end::Icon-->
    <!--begin::Wrapper-->
    <div class="d-flex flex-stack flex-grow-1 ">
        <!--begin::Content-->
        <div class=" fw-semibold">
            <div class="fs-6 text-gray-700 ">#Untuk melakukan perubahan data-data profile silahkan masuk ke Aplikasi<a class="fw-bold" href="https://pegawai.poltradabali.ac.id/login" target="_blank"> Pegawai</a>.</div>
            <div class="fs-6 text-gray-700 ">#Pastikan akses akun anda selalu dijaga.</div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Wrapper-->  
</div>
<!--begin::User Info-->
<div class="card mb-5 mb-xl-10" id="cardUserInfo">
    <div class="card-body" id="dtlUserInfo">
        <div class="card" aria-hidden="true">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                <!--begin: Pic-->
                <div class="me-7 mb-4">
                    <a href="'.$response['foto'].'" class="image-popup" title="Admin PIC">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <svg class="bd-placeholder-img rounded w-100px h-100px" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <rect width="100%" height="100%" fill="#868e96"></rect>
                            </svg>
                        </div>
                    </a>
                </div>
                <!--end::Pic-->
                <!--begin::Info-->
                <div class="flex-grow-1">
                    <!--begin::Detail User-->
                    <div class="d-flex flex-row flex-column border border-gray-300 border-dashed rounded w-100 py-5 px-4 me-4 my-5">
                        <!--begin::Row-->
                        <div class="row mb-7">
                            <div class="col-lg-6">
                                <div class="w-100 mb-3">
                                    <h3 class="card-title placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h3>
                                </div>
                                <div class="w-100 mb-3">
                                    <h3 class="card-title placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="w-100 mb-3">
                                    <h3 class="card-title placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h3>
                                </div>
                                <div class="w-100 mb-3">
                                    <h3 class="card-title placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="w-100 mb-3">
                                    <h3 class="card-title placeholder-glow">
                                        <span class="placeholder col-6"></span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Detail User-->
                </div>
                <!--end::Info-->
                </div>
          </div>
    </div>
</div>
<!--end::User Info-->
@section('js')
<script src="{{ asset('/script/backend/common/profile_user.js') }}"></script>
@stop
@endsection