@extends('frontend.layouts', ['activeMenu' => 'VIEW_DETIL_ALAT', 'activeSubMenu' => '', 'title' => 'Detail Alat Peraga'])
@section('content')
@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" />  
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/Magnific-Popup/magnific-popup.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css"/>
@stop
<div class="card mt-5" id="card-alat">     
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
                            <tbody class="fs-8">
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
                                    <td align="center" width="10">{{ $no+1 }}</td>
                                    <td width="20">{{ date('d-m-Y', strtotime($perawatan->tgl_perawatan)) }}</td>
                                    <td width="35">{{ $perawatan->keterangan }}</td>
                                    <td align="center" width="35">
                                        <a class="d-block overlay w-100 image-popup" href="{{ $url_file }}" title="{{ $file_image }}">
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
                            <tbody class="fs-8">
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
                                    <td align="center" width="10">{{ $no+1 }}</td>
                                    <td width="20">{{ date('d-m-Y', strtotime($pemeriksaan->tgl_pemeriksaan)) }}</td>
                                    <td width="35">{{ $pemeriksaan->keterangan }}</td>
                                    <td align="center" width="35">
                                        <a class="d-block overlay w-100 image-popup" href="{{ $url_file }}" title="{{ $file_image }}">
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
                <div class="justify-content-center text-center mt-4 p-4">
                    <a href="javascript:void(0);" onclick="_checkLogin('PERAWATAN', '{{ $alat->id }}')" class="btn btn-sm btn btn-primary mb-2"><i class="bi bi-pencil-square"></i> Perawatan Alat Peraga</a>
                    <a href="javascript:void(0);" onclick="_checkLogin('PEMERIKSAAN', '{{ $alat->id }}')" class="btn btn-sm btn btn-info mb-2"><i class="bi bi-pencil-square"></i> Pemeriksaan Alat Peraga</a>
                </div>
            </div>  
            <!--end::Content-->
        </div>
        <!--end::Layout-->
    </div>
    <!--end::Body-->
</div>

<!--begin::modal-->
<div class="modal fade" tabindex="-1" id="modalAction" data-bs-backdrop="static">
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
                <!--begin::Form-->
                <form class="form" autocomplete="off" id="form-perawatan" onsubmit="false" style="display: none;">
                    <input type="hidden" name="fid_alat_peraga"><input type="hidden" name="type_action">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Row-->
                        <div class="row justify-content-center">
                            <div class="col-lg-10 col-md-10 col-sm-12">
                                <!--begin::Input group-->
                                <div class="form-group mb-5">
                                    <label class="col-form-label required fs-6" for="tgl_perawatan">Tanggal Perawatan</label>
                                    <input type="text" name="tgl_perawatan" id="tgl_perawatan" class="form-control mb-3 mb-lg-0 dateString" maxlength="250" placeholder="Isikan tanggal perawatan ..." value="{{ date('d/m/Y') }}"/>
                                    <div class="form-text">*) Input: <code>dd/mm/yy</code> </div>
                                </div>
                                <!--begin::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6" id="iGroup-fotoPerawatan">
                                    <label class="col-form-label required fw-bold fs-6">Foto Perawatan</label>
                                    <input type="file" class="dropify-upl mb-3 mb-lg-0" id="foto_perawatan" name="foto_perawatan" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                    <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                    <div class="form-text">*) Max. size file: <code>2MB</code></div>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="form-group mt-4">
                                    <label class="col-form-label required fs-6" for="keterangan_perawatan">Keterangan</label>
                                    <textarea name="keterangan_perawatan" id="keterangan_perawatan" rows="3" class="form-control form-control-lg  mb-3 mb-lg-0" placeholder="Isikan keterangan...."></textarea>
                                    <div class="form-text">*) Maksimal: <code>250</code> Karakter | Isi dengan: <code>(-)</code> jika tidak ada</div>
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Card body-->
                </form>
                <!--end::Form-->
                <!--begin::Form-->
                <form class="form" autocomplete="off" id="form-pemeriksaan" onsubmit="false" style="display: none;">
                    <input type="hidden" name="fid_alat_peraga"><input type="hidden" name="type_action">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Row-->
                        <div class="row justify-content-center">
                            <div class="col-lg-10 col-md-10 col-sm-12">
                                <!--begin::Input group-->
                                <div class="form-group mb-5">
                                    <label class="col-form-label required fs-6" for="tgl_pemeriksaan">Tanggal Pemeriksaan</label>
                                    <input type="text" name="tgl_pemeriksaan" id="tgl_pemeriksaan" class="form-control mb-3 mb-lg-0 dateString" maxlength="250" placeholder="Isikan tanggal pemeriksaan ..." value="{{ date('d/m/Y') }}"/>
                                    <div class="form-text">*) Input: <code>dd/mm/yy</code> </div>
                                </div>
                                <!--begin::Input group-->
                                <!--begin::Input group-->
                                <div class="row mb-6" id="iGroup-fotoPemeriksaan">
                                    <label class="col-form-label required fw-bold fs-6">Foto Pemeriksaan</label>
                                    <input type="file" class="dropify-upl mb-3 mb-lg-0" id="foto_pemeriksaan" name="foto_pemeriksaan" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                    <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                    <div class="form-text">*) Max. size file: <code>2MB</code></div>
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="form-group mt-4">
                                    <label class="col-form-label required fs-6" for="keterangan_pemeriksaan">Keterangan</label>
                                    <textarea name="keterangan_pemeriksaan" id="keterangan_pemeriksaan" rows="3" class="form-control form-control-lg  mb-3 mb-lg-0" placeholder="Isikan keterangan...."></textarea>
                                    <div class="form-text">*) Maksimal: <code>250</code> Karakter | Isi dengan: <code>(-)</code> jika tidak ada</div>
                                </div>
                                <!--end::Input group-->
                            </div>
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Card body-->
                </form>
                <!--end::Form-->
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-save" class="btn btn-sm btn-primary me-2"><i class="far fa-save"></i> Simpan</button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal"><i class="fas fa-times align-middle me-1"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::modal-->
@section('js')
<script src="{{ asset('/dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/summernote/lang/summernote-id-ID.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/dropify-master/js/dropify.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/script/frontend/detail_alat_peraga.js') }}"></script>
@stop
@endsection