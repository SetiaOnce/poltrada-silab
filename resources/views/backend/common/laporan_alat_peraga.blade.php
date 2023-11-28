@extends('backend.layouts', ['activeMenu' => 'LAPORAN_ALAT_PERAGA', 'activeSubMenu' => 'laporan', 'title' => 'Laporan Alat Peraga'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" />  
<link href="{{ asset('/dist/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop

<!--begin::List Table Data-->
<div class="card shadow" id="card-data">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> List Data Alat Peraga
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <div class="m-0 me-2">
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
                                <label class="fw-bolder" for="filterLokasi">Lokasi: </label>
                                <select id="filterLokasi" name="filterLokasi" class="form-control selectpicker show-tick" data-live-search="true" title="-- Filter Lokasi --" data-style="btn-secondary text-dark"></select>
                            </div>
                            <div class="form-group mb-5">
                                <label class="fw-bolder" for="filterLaboratorium">Laboratorium: </label>
                                <select id="filterLaboratorium" name="filterLaboratorium" class="form-control selectpicker show-tick" data-live-search="true" title="-- Filter Laboratorium --" data-style="btn-secondary text-dark"></select>
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
                </div>
                <div class="me-0">
                    <button class="btn btn-sm btn-secondary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="bi bi-file-earmark-arrow-down-fill align-middle"></i> Export
                    </button>
                    
                    <!--begin::Menu 3-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true" style="">
                        <!--begin::Heading-->
                        <div class="menu-item px-3">
                            <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">
                                Pilihan Export
                            </div>
                        </div>
                        <!--end::Heading-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-1">
                            <a href="javascript:void(0);" id="export_excel" class="menu-link px-3">
                                <i class="bi bi-file-earmark-excel me-1 align-middle"></i>EXCEL
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-1">
                            <a href="javascript:void(0);" id="export_pdf" class="menu-link px-3">
                                <i class="bi bi-file-earmark-pdf me-1 align-middle"></i>PDF
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-1">
                            <a href="javascript:void(0);" id="export_print" class="menu-link px-3">
                                <i class="bi bi-printer me-1 align-middle"></i>PRINT
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-1">
                            <a href="javascript:void(0);" id="export_copy" class="menu-link px-3">
                                <i class="mdi mdi-content-copy me-1 align-middle"></i>COPY
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu 3-->
                </div>
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
                        <th class="text-center align-middle px-2 border-bottom-2">Satuan</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Detail</th>
                    </tr>
                </thead>
                <tbody class="fs-8"></tbody>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::List Table Data-->

<!--begin::Detail alat peraga-->
<div class="card shadow" id="card-detail" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Detail Alat Peraga
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="javascript:void(0);" onclick="_closeDetail()" class="btn btn-sm btn-bg-light btn-color-danger btn-active-light-danger mb-4"><i class="las la-undo fs-3 me-1"></i>Kembali</a>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="row p-5 border border-1 rounded" id="sectionDetailAlat">
            <div class="detailInformasiAlat"></div>
            <div class="dataPerawatan">
                <!--begin::Title-->
                <h3 class="text-gray-900 fw-bolder mb-3">
                    <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Data Perawatan
                </h3>
                <!--end::Title-->
                <div class="row">
                    <div class="table-responsive">
                        <table id="dt-peserta" class="table table-rounded table-hover align-middle table-row-bordered border gy-5 gs-7">
                            <thead class="fw-bolder text-uppercase bg-light">
                                <tr class="fw-bold fs-8 text-gray-800 ">
                                    <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Tanggal Perawatan</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Keterangan</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="dataPemeriksaan">
                <!--begin::Title-->
                <h3 class="text-gray-900 fw-bolder mb-3">
                    <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Data Pemeriksaan
                </h3>
                <!--end::Title-->
                <div class="row">
                    <div class="table-responsive">
                        <table id="dt-peserta" class="table table-rounded table-hover align-middle table-row-bordered border gy-5 gs-7">
                            <thead class="fw-bolder text-uppercase bg-light">
                                <tr class="fw-bold fs-8 text-gray-800 ">
                                    <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Tanggal Pemeriksaan</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Keterangan</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Detail alat peraga-->

@section('js')
<script src="{{ asset('/dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/lang/summernote-id-ID.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/script/backend/common/laporan_alat_peraga.js') }}"></script>
@stop
@endsection