@extends('backend.layouts', ['activeMenu' => 'DASHBOARD', 'activeSubMenu' => '', 'title' => 'Dasboard'])
@section('content')
@section('css')
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
@stop
@if (session()->get('key_level') == 'perpustakaan-administrator')
<div class="h-xl-100 mb-5" id="sectionWidgetDashboardAdmin">               
    <!--begin::Row-->
    <div class="row g-3 g-lg-6">
        <!--begin::Col-->
        <div class="col-6">
            <!--begin::Items-->
            <div class="bg-secondary bg-opacity-70 rounded-2 px-6 py-5 h-150px">
                <!--begin::Symbol-->
                <div class="symbol symbol-30px me-5 mb-8">
                                    
                </div>
                <!--end::Symbol-->

                <!--begin::Stats-->
                <div class="m-0">
                    
                </div>
                <!--end::Stats-->
            </div>    
            <!--end::Items-->
        </div>    
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-6">
            <!--begin::Items-->
            <div class="bg-secondary bg-opacity-70 rounded-2 px-6 py-5 h-150px">
                <!--begin::Symbol-->
                <div class="symbol symbol-30px me-5 mb-8">
                                    
                </div>
                <!--end::Symbol-->

                <!--begin::Stats-->
                <div class="m-0">
                    
                </div>
                <!--end::Stats-->
            </div>    
            <!--end::Items-->
        </div>    
        <!--end::Col-->
            <!--begin::Col-->
            <div class="col-6">
                <!--begin::Items-->
            <div class="bg-secondary bg-opacity-70 rounded-2 px-6 py-5 h-150px">
                <!--begin::Symbol-->
                <div class="symbol symbol-30px me-5 mb-8">
                                
                </div>
                <!--end::Symbol-->

                <!--begin::Stats-->
                <div class="m-0">
                
                </div>
                <!--end::Stats-->
            </div>    
            <!--end::Items-->
        </div>    
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-6">
            <!--begin::Items-->
            <div class="bg-secondary bg-opacity-70 rounded-2 px-6 py-5 h-150px">
                <!--begin::Symbol-->
                <div class="symbol symbol-30px me-5 mb-8">
                                    
                </div>
                <!--end::Symbol-->

                <!--begin::Stats-->
                <div class="m-0">
                    
                </div>
                <!--end::Stats-->
            </div>    
            <!--end::Items-->
        </div>    
        <!--end::Col-->    
    </div>
    <!--end::Row-->
</div>
@else  
<div class="card card-flush h-xl-100 mb-5" id="sectionWidgetDashboardStaff">   
    <!--begin::Heading-->
    <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px" style="background-image:url('{{ asset('dist/img/banner-index.jpg') }}" data-bs-theme="light">
        <div class="flex-grow-1">
            <!--begin::Detail User-->
            <div class="d-flex flex-row flex-column border border-gray-300 border-dashed rounded w-100 py-5 px-4 me-4 my-5">
                <!--begin::Row-->
                <div class="row mb-7">
                    <div class="col-lg-6">
                        <div class="w-100 mb-3">
                            <h3 class="card-title placeholder-glow">
                                <span class="placeholder col-6"></span>
                                <span class="placeholder col-4"></span>
                            </h3>
                        </div>
                        <div class="w-100 mb-3">
                            <h3 class="card-title placeholder-glow">
                                <span class="placeholder col-6"></span>
                                <span class="placeholder col-4"></span>
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
    </div>
    <!--end::Heading-->              
    <!--begin::Body-->
    <div class="card-body mt-n20">
        <!--begin::Stats-->
        <div class="mt-n20 position-relative">
            <!--begin::Row-->
            <div class="row g-3 g-lg-6">
                <!--begin::Col-->
                <div class="col-6">
                    <!--begin::Items-->
                    <div class="bg-secondary bg-opacity-70 rounded-2 px-6 py-5 h-150px">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-30px me-5 mb-8">
                                         
                        </div>
                        <!--end::Symbol-->

                        <!--begin::Stats-->
                        <div class="m-0">
                          
                        </div>
                        <!--end::Stats-->
                    </div>    
                    <!--end::Items-->
                </div>    
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-6">
                    <!--begin::Items-->
                    <div class="bg-secondary bg-opacity-70 rounded-2 px-6 py-5 h-150px">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-30px me-5 mb-8">
                                         
                        </div>
                        <!--end::Symbol-->

                        <!--begin::Stats-->
                        <div class="m-0">
                          
                        </div>
                        <!--end::Stats-->
                    </div>    
                    <!--end::Items-->
                </div>    
                <!--end::Col-->
                 <!--begin::Col-->
                 <div class="col-6">
                     <!--begin::Items-->
                    <div class="bg-secondary bg-opacity-70 rounded-2 px-6 py-5 h-150px">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-30px me-5 mb-8">
                                        
                        </div>
                        <!--end::Symbol-->

                        <!--begin::Stats-->
                        <div class="m-0">
                        
                        </div>
                        <!--end::Stats-->
                    </div>    
                    <!--end::Items-->
                </div>    
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-6">
                    <!--begin::Items-->
                    <div class="bg-secondary bg-opacity-70 rounded-2 px-6 py-5 h-150px">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-30px me-5 mb-8">
                                         
                        </div>
                        <!--end::Symbol-->

                        <!--begin::Stats-->
                        <div class="m-0">
                          
                        </div>
                        <!--end::Stats-->
                    </div>    
                    <!--end::Items-->
                </div>    
                <!--end::Col-->
                         
            </div>
            <!--end::Row-->
        </div> 
        <!--end::Stats-->
    </div>    
    <!--end::Body-->        
</div>
@endif
<!--begin:: Trend Aset Pertahun-->
<div class="card  mb-xl-10">
    <div class="card-body">
        <div id="trend-PeminjamanAlat"></div>
        </div>
    </div>
</div>
<!--begin:: End Aset Pertahun-->

<!--begin::modal-->
<div class="modal fade" tabindex="-1" id="modalViewTransaksiJatuhTempo" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"></h3>
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg fs-1"></i>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <div class="container">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-rounded table-hover align-middle table-row-bordered border" id="dt-TransaksiJatuhTempo">
                            <thead>
                                <tr class="fw-bolder text-uppercase bg-dark text-light">
                                    <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                                    <th class="align-middle px-2 border-bottom-2 border-gray-200">NAMA ANGGOTA</th>
                                    <th class="align-middle px-2 border-bottom-2 border-gray-200">TANGGAL PINJAM</th>
                                    <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">TANGGAL KEMBALI</th>
                                    <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">JUMLAH ALAT</th>
                                    <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">NO PINJAMAN</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times align-middle me-1"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::modal-->

@section('js')
<script src="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/script/backend/common/dashboard.js') }}"></script>
@stop
@endsection