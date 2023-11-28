@extends('frontend.layouts', ['activeMenu' => 'VIEW_DETIL_ALAT', 'activeSubMenu' => '', 'title' => 'Detail Alat Peraga'])
@section('content')


<div class="card mt-5">     
    <!--begin::Body-->
    <div class="card-body p-lg-20 pb-lg-0">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-xl-row">
            <!--begin::Content-->
            <div class="flex-lg-row-fluid me-xl-15">
                <!--begin::Post content-->
                <div class="mb-17">
                    <!--begin::book header-->
                    <div class="mb-8">  
                        <!--begin::Title--> 
                        <a href="javascript:void(0);" class="text-dark text-hover-primary fs-2 fw-bold">
                            <i class="bi bi-tools text-dark fs-1 me-3"></i>DATA ALAT PERAGA
                        </a>
                        <!--end::Title--> 

                        <!--begin::Container-->
                        <div class="overlay mt-8">    
                            @php
                                $file_image = $alat->foto;
                                if($file_image==''){
                                    $url_file = asset('dist/img/default-placeholder.png');
                                } else {
                                    if (!file_exists(public_path(). '/dist/img/alat-peraga/'.$file_image)){
                                        $url_file = asset('dist/img/default-placeholder.png');
                                        $file_image = NULL;
                                    }else{
                                        $url_file = url('dist/img/alat-peraga/'.$file_image);
                                    }
                                }
                            @endphp
                            <!--begin::Image-->   
                            <div class="text-center">
                                <div class="me-md-6">  
                                    <!--begin::Overlay--> 
                                    <div class="overlay">
                                        <a class="d-block overlay w-100 image-popup" href="javascript:void(0);" title="{{ $file_image }}">
                                            <img src="{{ $url_file }}" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="{{ $file_image }}"/>
                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                                            </div>    
                                        </a>
                                    </div>                 
                                </div>
                            </div>
                            <!--end::Image-->
                        </div>  
                        <!--end::Container-->  
                    </div>
                    <!--end::book header-->  
                    <!--begin::metadata-->  
                    <div class="mb-2 border border-gray-300 border-dashed rounded min-w-80px py-3 px-4 mx-2 mb-3">
                         <!--begin::Row-->
                         <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted">Kode Alat Peraga</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">                    
                                <span class="fw-bold fs-6 text-gray-800">{{ $alat->kode_alat_peraga }}</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted">Nama Alat Peraga</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">                    
                                <span class="fw-bold fs-6 text-gray-800">{{ $alat->nama_alat_peraga }}</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted">Jumlah</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">                    
                                <span class="fw-bold fs-6 text-gray-800">{{ $alat->jumlah }}</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted">Satuan</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">                    
                                <span class="fw-bold fs-6 text-gray-800">{{ $alat->satuan->satuan }}</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted">Kondisi</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">                    
                                <span class="fw-bold fs-6 text-gray-800">{{ $alat->kondisi }}</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted">Laboratorium</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">                    
                                <span class="fw-bold fs-6 text-gray-800">{{ $alat->lab->nama_laboratorium }}</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mb-4">
                            <!--begin::Label-->
                            <label class="col-lg-3 fw-semibold text-muted">Lokasi</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-9">                    
                                <span class="fw-bold fs-6 text-gray-800">{{ $alat->lokasi->nama_lokasi }}</span>                
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::metadata-->           
                </div>
                <!--end::Post content-->

                <!--begin::Title-->
                <h3 class="text-gray-900 fw-bolder mb-5">
                    <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Data Perawatan
                </h3>
                <!--end::Title-->
                <div class="row">
                    <div class="table-responsive">
                        <table id="dt-perawatan" class="table table-rounded table-hover align-middle table-row-bordered border gy-5 gs-7">
                            <thead class="fw-bolder text-uppercase bg-light">
                                <tr class="fw-bold fs-8 text-gray-800 ">
                                    <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Tanggal Perawatan</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Keterangan</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dtPerawatan as $no => $perawatan)             
                                @php
                                    $file_image = $perawatan->foto;
                                    if($file_image==''){
                                        $url_file = asset('dist/img/default-placeholder.png');
                                    } else {
                                        if (!file_exists(public_path(). '/dist/img/perawatan/'.$file_image)){
                                            $url_file = asset('dist/img/default-placeholder.png');
                                            $file_image = NULL;
                                        }else{
                                            $url_file = url('dist/img/perawatan/'.$file_image);
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td align="center">{{ $no+1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($perawatan->tgl_perawatan)) }}</td>
                                    <td>{{ $perawatan->keterangan }}</td>
                                    <td align="center">
                                        <a class="d-block overlay w-100 image-popup" href="javascript:void(0);" title="{{ $file_image }}">
                                            <img src="{{ $url_file }}" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="{{ $file_image }}" />
                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                                            </div>    
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--begin::Title-->
                <h3 class="text-gray-900 fw-bolder mb-3">
                    <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Data Pemeriksaan
                </h3>
                <!--end::Title-->
                <div class="row">
                    <div class="table-responsive">
                        <table id="dt-pemeriksaan" class="table table-rounded table-hover align-middle table-row-bordered border gy-5 gs-7">
                            <thead class="fw-bolder text-uppercase bg-light">
                                <tr class="fw-bold fs-8 text-gray-800 ">
                                    <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Tanggal Pemeriksaan</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Keterangan</th>
                                    <th  class="align-middle px-2 border-bottom-2 border-gray-200">Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dtPemeriksaan as $no => $pemeriksaan)             
                                @php
                                    $file_image = $pemeriksaan->foto;
                                    if($file_image==''){
                                        $url_file = asset('dist/img/default-placeholder.png');
                                    } else {
                                        if (!file_exists(public_path(). '/dist/img/pemeriksaan/'.$file_image)){
                                            $url_file = asset('dist/img/default-placeholder.png');
                                            $file_image = NULL;
                                        }else{
                                            $url_file = url('dist/img/pemeriksaan/'.$file_image);
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td align="center">{{ $no+1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($pemeriksaan->tgl_pemeriksaan)) }}</td>
                                    <td>{{ $pemeriksaan->keterangan }}</td>
                                    <td align="center">
                                        <a class="d-block overlay w-100 image-popup" href="javascript:void(0);" title="{{ $file_image }}">
                                            <img src="{{ $url_file }}" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="{{ $file_image }}" />
                                            <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                                <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                                            </div>    
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <span class="text-danger fs-8 text-justify border rounded p-5">*Pastikan kamu sudah melakukan login pada aplikasi ini, jika ingin melakukan penginputan terhadap perawatan dan pemeriksaan alat peraga</span>
                <div class="justify-content-center text-center mt-4">
                    <a href="{{ url('app_admin/alat_peraga/perawatan') }}" class="btn btn-sm btn btn-primary mb-2"><i class="fas fa-times"></i> Perawatan Alat Peraga</a>
                    <a href="{{ url('app_admin/alat_peraga/pemeriksaan') }}" class="btn btn-sm btn btn-info mb-2"><i class="fas fa-times"></i> Pemeriksaan Alat Peraga</a>
                </div>
            </div>  
            <!--end::Content-->
        </div>
        <!--end::Layout-->
    </div>
    <!--end::Body-->
</div>


@section('js')
<script src="{{ asset('/script/frontend/main.js') }}"></script>
@stop
@endsection