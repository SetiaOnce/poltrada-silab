@extends('backend.layouts', ['activeMenu' => 'LAPORAN_PERAWATAN', 'activeSubMenu' => 'laporan', 'title' => 'Laporan Perawatan'])
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
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> List Perawatan Alat Peraga
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <div class="m-0 me-2">
                    <!--begin::Menu toggle-->
                    <a href="javascript:void(0);" class="btn btn-sm btn-info font-weight-bolder" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">             
                        <i class="bi bi-funnel text-light me-1"></i> Filter
                    </a>
                    <!--end::Menu toggle-->
                    <!--begin::Menu 1-->
                    <div class="menu menu-sub menu-sub-dropdown w-350px w-md-400px" data-kt-menu="true" id="kt_menu_64e5cdce6c4af" style="">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">Filter Data Perawatan</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Menu separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Menu separator-->
                        <!--begin::Form-->
                        <div class="px-7 py-5">
                            <label class="col-form-label fs-6 ">Range Tanggal Perawatan</label>
                            <!--begin::Input group-->
                            <div class="input-group mb-5">
                                <input type="text" class="form-control form-control-sm date-flatpickr" name="filterDt-startDate" id="filterDt-startDate" maxlength="10" placeholder="dd/mm/YYYY" readonly />
                                <span class="input-group-text">s/d</span>
                                <input type="text" class="form-control form-control-sm date-flatpickr" name="filterDt-endDate" id="filterDt-endDate" maxlength="10" placeholder="dd/mm/YYYY" readonly />
                            </div>
                            <div class="separator border-gray-200 mb-2"></div>
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
            <table class="table table-rounded  table-hover align-middle table-row-bordered border gy-3 gs-3" id="dt-perawatanAlat">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-dark text-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Alat Peraga</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Tgl.Perawatan</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Keterangan</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
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
<script src="{{ asset('/dist/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/script/backend/common/laporan_perawatan.js') }}"></script>
@stop
@endsection