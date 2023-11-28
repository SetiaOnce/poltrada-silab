@extends('backend.layouts', ['activeMenu' => 'JADWAL_PRAKTEK', 'activeSubMenu' => '', 'title' => 'Jadwal Praktek'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" />  
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/Magnific-Popup/magnific-popup.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css"/>
@stop

<!--begin::List Table Data-->
<div class="card shadow" id="card-data">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> List Pengajuan Jadwal Praktek
            </h3>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded table-hover align-middle table-row-bordered border gy-3 gs-3 " id="dt-jadwalPraktek">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-dark text-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">LABORATORIUM</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">NIK</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">NAMA</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">WHATSAPP</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">WAKTU</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">JUMLAH PESERTA</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">JUDUL PRAKTEK</th>
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="fs-8"></tbody>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<!--end::List Table Data-->
<!--begin::modal-->
<div class="modal fade" tabindex="-1" id="modalAksiPengajuan" data-bs-backdrop="static">
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
                <form class="form" autocomplete="off" id="form-aksiPengajuan" onsubmit="false">
                    <input type="hidden" name="idp_jadwal">
                    <input type="hidden" name="jenis_aksi">
                    <!--begin::Input group-->
                    <div class="form-group">
                        <label class="col-form-label required fs-6" for="keterangan">Catatan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control form-control-lg  mb-3 mb-lg-0" placeholder="Isikan keterangan...."></textarea>
                        <div class="form-text">*) Maksimal: <code>250</code> Karakter | Isi dengan: <code>(-)</code> jika tidak ada</div>
                    </div>
                    <!--end::Input group-->
                    <div class="modal-footer">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" id="btn-send-action" class="btn btn-sm btn-primary me-2"><i class="bi bi-send"></i> Kirim</button>
                            <button type="button" class="btn  btn-sm btn-danger" data-bs-dismiss="modal"><i class="fas fa-times align-middle me-1"></i> Tutup</button>
                        </div>
                    </div>
                </form>
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
<script src="{{ asset('/script/backend/admin/jadwal_praktek.js') }}"></script>
@stop
@endsection