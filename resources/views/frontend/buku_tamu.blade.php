@extends('frontend.layouts', ['activeMenu' => 'BUKU_TAMU', 'activeSubMenu' => '', 'title' => 'Buku Tamu'])
@section('content')
@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop
<!--begin::Card-->
<div class="card mt-5" id="kt-form-tamu">     
    <!--begin::Body-->
    <div class="card-body p-lg-15">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">

            <!--begin::Content-->
            <div class="flex-lg-row-fluid">
                <!--begin::Extended content-->
                <div class="mb-13">
                    <!--begin::Content head-->
                    <div class="mb-15">
                        <!--begin::Title-->
                        <div class="row justify-content-center">
                            <div class="col-lg-10 col-md-10 col-sm-12 text-center">
                                <h4 class="fs-2x text-gray-800 w-bolder mb-6">                           
                                    Buku Tamu              
                                </h4>
                            </div>
                        </div>
                        <!--end::Title-->
                        <div class="separator separator-dashed"></div>
                    </div>    
                    <!--end::Content head-->            
                    
                    <div class="row justify-content-center">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                            <!--begin::Form-->
                            <form id="form-bukuTamu" class="form" onsubmit="return false">
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <label class="col-form-label required fs-6" for="nama_instansi">Nama Instansi</label>
                                    <input type="text" name="nama_instansi" id="nama_instansi" class="form-control form-control-lg mb-3 mb-lg-0 " maxlength="120" placeholder="Isikan nama instansi..." autocomplete="off" />
                                    <div class="form-text">*) Maksimal: <code>120</code> Karakter</div>
                                </div>
                                <!--end::Input group--> 
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <label class="col-form-label required fs-6" for="nama_kegiatan">Nama Kegiatan</label>
                                    <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control form-control-lg mb-3 mb-lg-0 " maxlength="250"  placeholder="Isikan nama kegiatan..." autocomplete="off" />
                                    <div class="form-text">*) Maksimal: <code>250</code> Karakter</div>
                                </div>
                                <!--end::Input group--> 
                                <!--begin::Input group-->
                                <div class="row mb-6">
                                    <label class="col-form-label required fs-6" for="tanggal_kunjungan">Tanggal Kunjungan</label>
                                    <input type="text" name="tanggal_kunjungan" id="tanggal_kunjungan" class="form-control form-control-lg mb-3 mb-lg-0 " maxlength="120" value="{{ date('d/m/Y') }}" placeholder="Isikan tanggal kunjungan..." autocomplete="off" />
                                    <div class="form-text">*) Input: <code>dd/mm/yyyy</code></div>
                                </div>
                                <!--end::Input group--> 
                                <!--begin::Input group-->
                                <div class="row mb-6" id="iGroup-foto">
                                    <label class="col-form-label required fw-bold fs-6">Foto Kunjungan</label>
                                    <input type="file" class="dropify-upl mb-3 mb-lg-0" id="foto" name="foto" accept=".png, .jpg, .jpeg" data-show-remove="false" data-allowed-file-extensions="jpg png jpeg" data-max-file-size="2M" />
                                    <div class="form-text">*) Type file: <code>*.jpeg | *.jpeg | *.png</code></div>
                                    <div class="form-text">*) Max. size file: <code>2MB</code></div>
                                </div>
                                <!--end::Input group-->
                                <div class="row mt-5">
                                    <div class="col-lg-12 d-flex justify-content-center">
                                        <button type="button" id="btn-save" class="btn btn-sm btn-primary me-2"><i class="far fa-save"></i> Simpan Buku Tamu</button>
                                        <button type="button" id="btn-reset" class="btn btn-sm btn-danger"><i class="bi bi-arrow-clockwise fs-3"></i> Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
                <!--end::Extended content-->                
            </div>  
            <!--end::Content-->   

        </div>
        <!--end::Layout-->
    </div>
    <!--end::Body-->
</div>
<!--begin::End-->

@section('js')
<script src="{{ asset('/dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/dropify-master/js/dropify.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/script/frontend/buku_tamu.js') }}"></script>
@stop
@endsection