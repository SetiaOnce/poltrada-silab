@extends('frontend.layouts', ['activeMenu' => 'BERANDA', 'activeSubMenu' => '', 'title' => 'Beranda Laboratorium'])
@section('content')
@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/clockpicker@0.0.7/dist/jquery-clockpicker.min.css" rel="stylesheet" type="text/css" />
@stop
<div class="tns" style="direction: ltr">
    <div data-tns="true" data-tns-nav-position="bottom" data-tns-autoplay-timeout="5000" data-tns-mouse-drag="true" data-tns-controls="false">
        @foreach ($dt_banners as $banner)
            @php
                $file_image = $banner->file_image;
                if($file_image==''){
                    $url_file = asset('dist/img/default-placeholder.png');
                } else {
                    if (!file_exists(public_path(). '/dist/img/banner/'.$file_image)){
                        $url_file = asset('dist/img/default-placeholder.png');
                        $file_image = NULL;
                    }else{
                        $url_file = url('dist/img/banner/'.$file_image);
                    }
                }
            @endphp
            <!--begin::Item-->
            <div class="text-center px-5 pt-5 pt-lg-10 px-lg-10">
                <img src="{{ $url_file }}" class="card-rounded  mw-100" alt="{{ $file_image }}" />
            </div>
            <!--end::Item-->
        @endforeach
    </div>
</div>
<div class="tns tns-default" style="direction: ltr">
    <!--begin::Slider-->
    <div data-tns="true" data-tns-loop="true" data-tns-swipe-angle="false" data-tns-speed="1000"
    data-tns-autoplay="true"
    data-tns-autoplay-timeout="5000"
    data-tns-controls="true"
    data-tns-nav="false"
    data-tns-items="3"
    data-tns-center="false"
    data-tns-mouse-drag="true"
    data-tns-dots="false"
    data-tns-controls="false"
    data-tns-prev-button="#kt_team_slider_prev1"
    data-tns-next-button="#kt_team_slider_next1">

        @foreach ($dt_linkTerkait as $link)           
        @php
            $file_name = $link->thumnail;
            if($file_name==''){
                $url_file = asset('dist/img/default-placeholder.png');
            } else {
                if (!file_exists(public_path(). '/dist/img/link-terkait/'.$file_name)){
                    $url_file = asset('dist/img/default-placeholder.png');
                    $file_name = NULL;
                }else{
                    $url_file = url('dist/img/link-terkait/'.$file_name);
                }
            }
        @endphp
        <!--begin::Item-->
        <div class="text-center px-5 py-5">
            <a href="{{ $link->link_url }}" target="_blank">
                <img src="{{ $url_file }}" class="card-rounded mw-100" alt="{{ $link->name }}"/>
            </a>
        </div>
        <!--end::Item-->
        @endforeach
    </div>
    <!--end::Slider-->

    <!--begin::Slider button-->
    <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_prev1">
        <span class="svg-icon fs-3x">
            <i class="bi bi-chevron-double-left fs-1 fw-bolder"></i>
        </span>
    </button>
    <!--end::Slider button-->
    <!--begin::Slider button-->
    <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_next1">
        <span class="svg-icon fs-3x">
            <i class="bi bi-chevron-double-right fs-1 fw-bolder"></i>
        </span>
    </button>
    <!--end::Slider button-->
</div>

<!--begin::Card laboratorium-->
<div class="card h-lg-100 mt-5 card-of-lab" id="sectionLaboratorium" style="background: linear-gradient(26.57deg, #47BAB3 8.33%, #A1CF7E 91.67%)">
    <!--begin::Body-->
    <div class="card-body">          
        <!--begin::Content head-->
        <div class="mb-15">
            <!--begin::Title-->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-12 text-center">
                    <h4 class="fs-2x text-light w-bolder mb-6">                           
                        <i class="bi bi-buildings fs-1 align-middle text-light me-1"></i> LABORATORIUM  
                    </h4>
                </div>
            </div>
            <!--end::Title-->
            <div class="separator separator-dashed"></div>
        </div>    
        <!--end::Content head-->
        <div class="row g-10 mb-5 justify-content-center">   
            @foreach ($dt_laboratorium as $lab)          
            <!--begin::Col--> 
            <div class="col-md-3" onclick="_viewDetailLaboratorium({{ $lab->id }})">
                <a href="javascript:void();" >
                    <div class="card h-100 " style="filter: drop-shadow(0px 0px 40px rgba(255, 255, 255, 0.178))">
                        <!--begin::Card body-->
                        <div class="card-body d-flex justify-content-center text-center flex-column p-8">
                            <!--begin::Name-->
                            <a href="javascript:void(0);" class="text-gray-800 text-hover-primary d-flex flex-column">
                                <!--begin::Image-->
                                <div class="symbol symbol-60px mb-5">
                                    <img src="{{ asset('dist/img/icons/'.$lab->icon.'') }}" class="theme-light-show" alt="">
                                    <img src="{{ asset('dist/img/icons/'.$lab->icon.'') }}" class="theme-dark-show" alt="">                            
                                </div>
                                <!--end::Image-->
                                <!--begin::Title-->
                                <div class="fs-5 fw-bold mb-2">
                                    {{ strtoupper($lab->nama_laboratorium) }}        
                                </div>
                                <!--end::Title-->
                            </a>
                            <!--end::Name-->
                        </div>
                        <!--end::Card body-->
                    </div>
                </a>
            </div>
            <!--end::Col-->                          
            @endforeach
        </div>            
    </div>
    <!--end::Body-->
</div>
<!--end::Card laboratorium-->
<!--begin::Card Detail Laboratorium-->
<div class="mb-lg-n30 mt-6 position-relative z-index-2" id="sectionDetailLaboratorium" style="display: none;">
    <!--begin::Card-->
    <div class="card" style="filter: drop-shadow(0px 0px 40px rgba(59, 59, 59, 0.08))">
        <!--begin::Card body-->
        <div class="card-body p-lg-20" id="dt-detailLaboratorium"></div>
        <!--end::Card body-->       
        <!--begin::Card body-->
        <div class="card-body p-lg-5 col-lg-12 d-flex justify-content-center">
            <button type="button" class="btn btn-sm btn-danger" id="closeDetailLaboratorium"><i class="bi bi-x fs-3"></i> Tutup</button>
        </div>
        <!--end::Card body-->       
    </div>
    <!--end::Card-->
</div>
<!--end::Card Detail Laboratorium-->
<!--begin::Card Form Pengajuan Praktek-->
<div class="card mt-5" id="kt-form-pengajuan">     
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
                                <h4 class="fs-2 text-gray-800 w-bolder mb-6">                           
                                   <i class="bi bi-ui-checks fs-1 align-middle text-dark me-2"></i> FORM PENGAJUAN JADWAL PRAKTEK    
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
                            <form id="form-jadwalPraktek" class="form" onsubmit="return false">
                                <!--begin::Input group-->
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-form-label required fs-6" for="fid_lab">Laboratorium</label>
                                        <select id="fid_lab" name="fid_lab" class="form-control selectpicker show-tick" data-container="#card-form" data-live-search="true" title="-- Pilih Laboratorium --"></select>
                                        <div class="form-text">*) Harus diisi</div>
                                    </div>
                                </div>
                                <!--end::Input group-->
                                <div class="row justify-content-between">
                                    <div class="col-md-6 px-4">
                                        <!--begin::Input group-->
                                        <div class="row">
                                            <label class="col-form-label required fs-6" for="nik_instruktur">NIK</label>
                                            <div class="input-group">
                                                <input type="text" name="nik_instruktur" id="nik_instruktur" class="form-control mb-3 mb-lg-0 inputmax20" maxlength="20"  placeholder="Masukkan nik..." autocomplete="off" />
                                                <span class="input-group-text text-white bg-info cursor-pointer" data-bs-toggle="tooltip" title="Pencarian pada pusat data pegawai!" id="search-dtPegawai">
                                                    <i class="bi bi-search text-white me-1"></i> Cari
                                                </span>
                                            </div>
                                            <div class="form-text">*) Maksimal: <code>20</code> Digit</div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <div class="col-md-6 px-4">
                                        <!--begin::Input group-->
                                        <div class="row">
                                            <label class="col-form-label required fs-6" for="nama_instruktur">Nama Instruktur</label>
                                            <input type="text" name="nama_instruktur" id="nama_instruktur" class="form-control mb-3 mb-lg-0 " maxlength="120" placeholder="Masukkan nama instruktur..." autocomplete="off" />
                                            <div class="form-text">*) Maksimal: <code>120</code> Karakter</div>
                                        </div>
                                        <!--end::Input group--> 
                                    </div> 
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-md-6 px-4">
                                        <!--begin::Input group-->
                                        <div class="row">
                                            <label class="col-form-label required fs-6" for="no_wa">No Whatsapp</label>
                                            <input type="text" name="no_wa" id="no_wa" class="form-control mb-3 mb-lg-0 inputmax20" maxlength="20" placeholder="Masukkan nomor whatsapp..." autocomplete="off"/>
                                            <div class="form-text">*) Maksimal: <code>20</code> Digit</div>
                                        </div>
                                        <!--end::Input group--> 
                                    </div> 
                                    <div class="col-md-6 px-4">
                                        <!--begin::Input group-->
                                        <div class="row">
                                            <label class="col-form-label required fs-6" for="jmlh_peserta">Jumlah Peserta</label>
                                            <input type="text" name="jmlh_peserta" id="jmlh_peserta" class="form-control mb-3 mb-lg-0 inputmax11" maxlength="11"  placeholder="Masukkan jumlah peserta..." autocomplete="off" />
                                            <div class="form-text">*) Maksimal: <code>11</code> Digit</div>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                                <!--begin::Input group-->
                                <div class="row">
                                    <label class="col-form-label required fs-6" for="judul_praktek">Judul Praktek</label>
                                    <input type="text" name="judul_praktek" id="judul_praktek" class="form-control mb-3 mb-lg-0 " maxlength="250" placeholder="Masukkan judul praktek..." autocomplete="off"/>
                                    <div class="form-text">*) Maksimal: <code>250</code> Karakter</div>
                                </div>
                                <!--end::Input group--> 
                                <div class="row justify-content-between">
                                    <div class="col-md-6 px-4">
                                        <div class="row">
                                            <label class="col-form-label required fs-6">Tanggal</label>
                                            <div class="input-group">
                                                <span class="input-group-text" for="tanggal"><i class="bi bi-calendar2-event fs-3 text-dark"></i></span>
                                                <input type="text" class="form-control form-control-sm datepicker" name="tanggal" id="tanggal" placeholder="Masukkan tanggal..." aria-describedby="tanggal"/>
                                            </div>
                                            <div class="form-text">*) Input: <code>dd/mm/yyyy</code></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 px-4">
                                        <!--begin::Input group-->
                                        <div class="row">
                                            <label class="col-form-label required fs-6">Waktu Penggunaan</label>
                                            <!--begin::Input group-->
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm timepicker" name="jam_awal" id="jam_awal" maxlength="10" placeholder="Jam mulai" readonly />
                                                <span class="input-group-text">s/d</span>
                                                <input type="text" class="form-control form-control-sm timepicker" name="jam_akhir" id="jam_akhir" maxlength="10" placeholder="Jam selesai" readonly />
                                            </div>
                                            <div class="form-text">*) Input: <code>HH:mm</code></div>
                                        </div>
                                        <!--end::Input group--> 
                                    </div> 
                                </div>
                                <div class="row">
                                    <!--begin::Input group-->
                                    <div class="form-group">
                                        <label class="col-form-label required fs-6" for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control form-control-lg  mb-3 mb-lg-0" placeholder="Isikan keterangan...."></textarea>
                                        <div class="form-text">*) Maksimal: <code>250</code> Karakter | Isi dengan: <code>(-)</code> jika tidak ada</div>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <div class="row mt-10">
                                    <div class="col-lg-12 d-flex justify-content-center">
                                        <button type="button" id="btn-save" class="btn btn-sm btn-primary me-2"><i class="bi bi-send"></i> Kirim Pengajuan</button>
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
<!--end::Card Form Pengajuan Praktek-->

@section('js')
<script src="{{ asset('/dist/plugins/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.js') }}"></script>
<script src="{{ asset('/dist/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/clockpicker@0.0.7/dist/bootstrap-clockpicker.min.js"></script>
<script src="{{ asset('/script/frontend/main.js') }}"></script>
@stop
@endsection