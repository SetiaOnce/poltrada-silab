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

<!--begin::Card Form-->
<div class="card" id="card-form" style="display: none;">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title"></div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeForm" onclick="_closeCard('form_pemeriksaan');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Form-->
    <form class="form" autocomplete="off" id="form-data" onsubmit="false">
        <input type="hidden" name="id"><input type="hidden" name="methodform_data">
        <!--begin::Card body-->
        <div class="card-body">
            <!--begin::Row-->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <!--begin::form group-->
                    <div class="form-group mb-5" id="fg-fidLab" style="overflow:hidden;">
                        <label class="col-form-label required fs-6" for="fid_lab">Laboratorium</label>
                        <select id="fid_lab" name="fid_lab" class="form-control selectpicker show-tick" data-container="#card-form" data-live-search="true" title="-- Pilih Laboratorium --"></select>
                    </div>
                    <!--end::form group-->
                    <!--begin::form group-->
                    <div class="form-group mb-5" id="fg-statusPeminjaman" style="overflow:hidden;">
                        <label class="col-form-label required fs-6" for="status_peminjaman">Status Peminjam</label>
                        <select id="status_peminjaman" name="status_peminjaman" class="form-control selectpicker show-tick" data-container="#card-form" data-live-search="true" title="-- Pilih Status Peminjam --"></select>
                    </div>
                    <!--end::form group-->
                    <!--begin::Input group-->
                    <div class="form-group mt-4">
                        <label class="col-form-label required fs-6" for="nik_notar">NIK/Notar</label>
                        <input type="text" name="nik_notar" id="nik_notar" class="form-control form-control-lg mb-3 mb-lg-0 inputmax20" maxlength="20" placeholder="Isikan nik atau notar ..." />
                        <div class="form-text">*) Maksimal: <code>20</code> Digit</div>
                    </div>
                    <!--end::Input group-->
                    <div class="row mt-3" id="notFoundData"></div>
                    <div id="hideForm1" style="display: none;">
                        <!--begin::Input group-->
                        <div class="form-group mt-4">
                            <label class="col-form-label required fs-6" for="nama_peminjam">Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" id="nama_peminjam" class="form-control form-control-lg mb-3 mb-lg-0 form-input-disabled" maxlength="120" placeholder="Isikan nama peminjam ..." />
                            <div class="form-text">*) Maksimal: <code>120</code> Karakter</div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="form-group mt-4">
                            <label class="col-form-label required fs-6" for="telepon">Telepon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control form-control-lg mb-3 mb-lg-0 inputmax20 form-input-disabled" maxlength="120" placeholder="Isikan telepon ..."/>
                            <div class="form-text">*) Maksimal: <code>15</code> Digit</div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="form-group mt-4">
                            <label class="col-form-label required fs-6" for="prodi_instansi">Prodi/Instansi</label>
                            <input type="text" name="prodi_instansi" id="prodi_instansi" class="form-control form-control-lg mb-3 mb-lg-0 " maxlength="120" placeholder="Isikan prodi atau instansi ..." />
                            <div class="form-text">*) Maksimal: <code>120</code> Karakter</div>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-6">
                            <!--begin::Input group-->
                            <div class="form-group mb-5">
                                <label class="col-form-label required fs-6" for="tgl_peminjaman">Tanggal Peminjaman</label>
                                <input type="text" name="tgl_peminjaman" id="tgl_peminjaman" class="form-control mb-3 mb-lg-0 " maxlength="250" placeholder="Isikan tanggal peminjaman ..." />
                                <div class="form-text">*) Input: <code>dd/mm/yyyy</code> </div>
                            </div>
                            <!--begin::Input group-->
                        </div>
                        <div class="col-md-6">
                            <!--begin::Input group-->
                            <div class="form-group mb-5">
                                <label class="col-form-label required fs-6" for="tgl_pengembalian">Tanggal Pengembalian</label>
                                <input type="text" name="tgl_pengembalian" id="tgl_pengembalian" class="form-control mb-3 mb-lg-0 " maxlength="250" placeholder="Isikan tanggal pengembalian ..." />
                                <div class="form-text">*) Input: <code>dd/mm/yyyy</code> </div>
                            </div>
                            <!--begin::Input group-->
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" id="btn-save" class="btn btn-sm btn-primary me-2"><i class="far fa-save"></i> Simpan</button>
                            <button type="button" id="btn-reset" class="btn btn-sm btn-secondary" onclick="_clearForm();"><i class="bi bi-arrow-clockwise fs-3"></i> Reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
    </form>
    <!--end::Form-->
</div>
<!--end::Card Form-->
<!--begin::Manage alat-->
<div class="card shadow" id="card-alatSrc" style="display: none">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h3 class="fw-bolder fs-2 text-gray-900">
                <i class="fas fa-th-list fs-2 text-gray-900 me-2"></i> List Data Alat
            </h3>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn btn-bg-light btn-color-danger me-2" id="btn-closeForm" onclick="_closeCard('form_alat');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="detailHeaderPinjaman"></div>
        <!--begin::Alert-->
        <div class="alert alert-dismissible bg-primary d-flex flex-column flex-sm-row p-5 mt-4">
            <!--begin::Icon-->
            <i class="bi bi-megaphone fs-2hx text-light me-4 mb-5 mb-sm-0"></i>
            <!--end::Icon-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                <!--begin::Title-->
                <h4 class="mb-2 text-light">Informasi!</h4>
                <!--end::Title-->
                <!--begin::Content-->
                <span>Checklist checkbox yang ada pada bagian kiri tabel untuk memilih alat yang akan dipinjam...</span>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Alert-->
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <!--begin::Wrapper-->
        <div class="d-flex justify-content-end flex-stack mb-5">
            <!--begin::Group actions-->
            <div class="d-flex  align-items-center" data-kt-docs-table-toolbar="selected">
                {{-- <div class="fw-bold me-5">
                    <span class="me-2" data-kt-docs-table-select="selected_count"></span> Alat Dipilih
                </div> --}}
                <button type="button" class="btn btn-sm btn-success me-2" data-bs-toggle="tooltip" title="Approve alat yang dichecklist!" id="btn-approve-alat"><i class="bi bi-check-all fs-3"></i> Approve</button>
            </div>
            <!--end::Group actions-->
        </div>
        <!--end::Wrapper-->
        <div class="table-responsive">
            <!--begin::Datatable-->
            <table id="datatableAlat" class="table table-rounded  table-hover align-middle table-row-bordered border gy-3 gs-3">
                <thead class="fw-bolder text-uppercase bg-dark text-light">
                    <tr class="fw-bolder text-uppercase">
                        <th class="text-center  border-bottom-2 border-primary">Nama Alat</th>
                        <th class="w-25px text-center align-middle px-2 border-bottom-2 border-primary">Kode Alat</th>
                        <th class="w-20px text-center align-middle px-2 border-bottom-2 border-primary">Laboratorium</th>
                        <th class="w-20px text-center align-middle px-2 border-bottom-2 border-primary">Foto</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold" id="dt_peminjaman_alat"></tbody>
            </table>
            <!--end::Datatable-->
        </div>
    </div>
</div>
<!--end::Manage alat-->
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
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="tooltip" title="Tambah data Baru!" onclick="_addData();"><i class="las la-plus fs-3"></i> Tambah</button>
            </div>
        </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-rounded  table-hover align-middle table-row-bordered border gy-3 gs-3 nowrap" id="dt-peminjaman">
                <thead>
                    <tr class="fw-bolder text-uppercase bg-dark text-light">
                        <th class="text-center align-middle px-2 border-bottom-2 border-gray-200">No.</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Tanggal Peminjaman</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Status Peminjaman</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Nama Peminjaman</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Telepon</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">No Peminjaman</th>
                        <th class="align-middle px-2 border-bottom-2 border-gray-200">Jmlh</th>
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
<div class="modal fade" tabindex="-1" id="modalApproveAlat" data-bs-backdrop="static">
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
                <form class="form" autocomplete="off" id="form-savealat" onsubmit="false">
                    <input type="hidden" name="fid_peminjaman">
                    <input type="hidden" name="array_alat_pinjaman">
                    <div class="table-responsive">
                        <!--begin::Datatable-->
                        <table class="table table-rounded  table-hover align-middle table-row-bordered border gy-3 gs-3">
                            <thead class="fw-bolder text-uppercase bg-dark text-light">
                                <tr class="fw-bolder text-uppercase">
                                    <th class="text-center  border-bottom-2 border-primary">Nama Alat</th>
                                    <th class="w-25px text-center align-middle px-2 border-bottom-2 border-primary">Kode Alat</th>
                                    <th class="w-20px text-center align-middle px-2 border-bottom-2 border-primary">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold" id="dt-approveAlat">
                            </tbody>
                        </table>
                        <!--end::Datatable-->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-save-alat" class="btn btn-primary me-2"><i class="far fa-save"></i> Simpan</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times align-middle me-1"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>
<!--end::modal-->
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