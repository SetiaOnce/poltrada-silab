@extends('backend.layouts', ['activeMenu' => 'PEMINJAMAN', 'activeSubMenu' => '', 'title' => 'Peminjaman Alat Peraga'])
@section('content')

@section('css')
<link href="{{ asset('/dist/plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/custom/datatables/datatables.bundle.v817.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet" type="text/css" />  
<link href="{{ asset('/dist/plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/Magnific-Popup/magnific-popup.css') }}" rel="stylesheet" type="text/css" /> 
<link href="{{ asset('/dist/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop

<!--begin::Manage pengembalian alat-->
<div class="card shadow" id="card-PengembalianSrc"  style="display: none">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="bi bi-arrow-down-square-fill fs-2 text-gray-900 me-2"></i> Pengembalian Alat Pinjaman 
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeForm" onclick="_closeCard('form_pengembalian');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="detailHeaderPinjaman2"></div>
        <div class="container mt-6">
            <a href="javascript:void(0);" class="text-gray-800 text-hover-primary fs-4 fw-bold "><i class="fas fa-th-list fs-2 text-gray-900 me-2"></i>List alat yang dipinjam : </a>
            <div class="row g-10 mb-10 mt-1" id="listAlatPinjaman"></div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row mt-5">
            <div class="col-lg-12 d-flex justify-content-end btn-konfirmasiPengembalianSrc"></div>
        </div>
    </div>
</div>
<!--end::Manage pengembalian alat-->
<!--begin::List Table Data-->
<div class="card shadow" id="card-data">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> Data List Peminjaman Alat
            </h3>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded  table-hover align-middle table-row-bordered border gy-3 gs-3" id="dt-peminjaman">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-dark text-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Tanggal Peminjaman</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Status Peminjaman</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Nama Peminjaman</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Telepon</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">No Peminjaman</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Jmlh</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Ket</th>
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
<div class="modal fade" tabindex="-1" id="modalViewAlatPinjaman" data-bs-backdrop="static">
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
                <div class="row justify-content-center d-flex" id="sectionModalAlatPinjaman">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times align-middle me-1"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::modal-->
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
                    <input type="hidden" name="fid_peminjaman">
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
<script src="{{ asset('/script/backend/admin/peminjaman.js') }}"></script>
@stop
@endsection