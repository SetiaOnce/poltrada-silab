@extends('informasi.layouts')
@section('content')

<div class="w-100 row d-flex justify-content-between">
    <div class="col-md-4 mb-4">
        <!--begin::Wrapper-->
        <div class="bg-body rounded shadow-lg px-5 pt-5 pb-5 mx-auto"style="background: linear-gradient(26.57deg, #47BAB3 8.33%, #274472 91.67%)">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-12 text-center">
                    <h4 class="fs-2x text-light w-bolder mb-6">                           
                        <i class="bi bi-calendar-event fs-2 text-light me-2 align-center"></i> Jadwal Praktek <br><span class="text-light"><span style="color: #ff5722;"> Sedang Berlangsung</span></h3></span>
                    </h4>
                </div>
            </div>
            <!--end::Card header-->
            <div class="card-body">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class=" bg-light text-dark table table-rounded table-hover align-middle table-row-bordered border gy-3 gs-3" id="dt-listJadwalSchedule">
                        <thead>
                            <tr class="fw-bolder text-uppercase">
                                <th class="align-middle px-2 border-bottom-2 border-gray-200">Laboratorium</th>
                                <th class="align-middle px-2 border-bottom-2 border-gray-200">Jam</th>
                            </tr>
                        </thead>
                        <tbody class="bg-secondary"></tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
        </div>
        <!--end::Wrapper-->
        <!--begin::Wrapper-->
        <div class="w-100  bg-body rounded shadow-lg  px-5 pt-5 pb-5  mt-4" >
            <div id="fBody-login1" class="text-center align-center">
                <ul class="nav nav-pills nav-pills-custom justify-content-center" role="tablist">
                    <!--begin::Item--> 
                    <li class="nav-item  me-3 me-lg-6" role="presentation">
                        <!--begin::Link-->
                        <a class="nav-link d-flex justify-content-between border-primary p-6" data-bs-toggle="pill" href="javascript:void(0);" aria-selected="false" tabindex="-1" role="tab">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2" id="clockDisplayHour"></span>       
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item--> 
                    <!--begin::Item--> 
                    <li class="nav-item me-3 me-lg-6" role="presentation">
                        <!--begin::Link-->
                        <a class="nav-link d-flex justify-content-between border-primary p-6" data-bs-toggle="pill" href="javascript:void(0);" aria-selected="false" tabindex="-1" role="tab">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2" id="clockDisplayMeniute"></span>       
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item--> 
                    <!--begin::Item--> 
                    <li class="nav-item me-3 me-lg-6" role="presentation">
                        <!--begin::Link-->
                        <a class="nav-link d-flex justify-content-between border-primary p-6" data-bs-toggle="pill" href="javascript:void(0);" aria-selected="false" tabindex="-1" role="tab">
                            <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2"  id="clockDisplaySecond"></span>       
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item--> 
                </ul>
            </div>
        </div>
        <!--end::Wrapper-->
    </div>
    <div class="col-md-8">
        <!--begin::Wrapper-->
        <div class=" bg-body rounded shadow-lg px-5 pt-5 pb-5 mx-auto"style="background: linear-gradient(26.57deg, #47BAB3 8.33%, #274472 91.67%)">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-12 text-center">
                    <h4 class="fs-2x text-light w-bolder mb-6">                           
                        <i class="bi bi-calendar-event fs-2 text-light me-2 align-center"></i> Jadwal Praktek Hari Ini : <span class="text-light"><span style="color: #ff5722;">{{ App\Helpers\Shortcut::tanggalLower(date('Y-m-d')) }}</span></h3></span>
                    </h4>
                </div>
            </div>
            <!--end::Card header-->
            <div class="card-body">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class=" bg-light text-dark table table-rounded table-hover align-middle table-row-bordered border gy-3 gs-3" id="dt-listJadwalNow">
                        <thead>
                            <tr class="fw-bolder text-uppercase">
                                {{-- <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th> --}}
                                <th class="align-middle px-2 border-bottom-2 border-gray-200">Laboratorium</th>
                                <th class="align-middle px-2 border-bottom-2 border-gray-200">Praktek</th>
                                <th class="align-middle px-2 border-bottom-2 border-gray-200">Instruktur</th>
                                <th class="align-middle px-2 border-bottom-2 border-gray-200">Jam</th>
                                <th class="align-middle px-2 border-bottom-2 border-gray-200">Jumlah Peserta</th>
                                <th class="align-middle px-2 border-bottom-2 border-gray-200">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-secondary"></tbody>
                    </table>
                    <!--end::Table-->
                </div>
            </div>
        </div>
        <!--end::Wrapper-->
    </div>
</div>
@endsection