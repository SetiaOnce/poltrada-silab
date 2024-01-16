// variable Initialization
var table;
//Class Initialization
jQuery(document).ready(function() {
    _loadDataPeminjaman();
});
//Load Datatables 
const _loadDataPeminjaman = () => {
    table = $('#dt-peminjaman').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_peminjaman_alat",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            "data"  : function ( data ) {
                data.tgl_start= $('#filterDt-startDate').val();
                data.tgl_end= $('#filterDt-endDate').val();
            }
        },
        destroy: true,
        draw: true,
        deferRender: true,
        responsive: false,
        autoWidth: false,
        LengthChange: true,
        paginate: true,
        pageResize: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'tanggal_peminjaman', name: 'tanggal_peminjaman'},
            { data: 'status_peminjaman', name: 'status_peminjaman'},
            { data: 'nama_peminjam', name: 'nama_peminjam'},
            { data: 'telepon', name: 'telepon'},
            { data: 'no_peminjaman', name: 'no_peminjaman'},
            { data: 'jumlah_alat', name: 'jumlah_alat'},
            { data: 'approve', name: 'approve'},
            { data: 'action', name: 'action'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "5%", "targets": 1, "className": "align-top","visible": false},
            { "width": "10%", "targets": 2, "className": "align-top" },
            { "width": "20%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center" },
            { "width": "10%", "targets": 5, "className": "align-top text-center" },
            { "width": "10%", "targets": 6, "className": "align-top text-center" },
            { "width": "10%", "targets": 7, "className": "align-top text-center" },
            { "width": "10%", "targets": 8, "className": "align-top text-center" },
        ],
        oLanguage: {
            sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
            sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
            sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
            sInfoFiltered: "",
            sProcessing: `<div class="d-flex justify-content-center align-items-center"><div class="blockui-message"><span class="spinner-border text-primary align-middle me-2"></span> Mohon Tunggu...</div></div>`,
            sZeroRecords: "Tidak ada Data yang dapat ditampilkan..",
            sLengthMenu: `<select class="mb-2 show-tick form-select-transparent" data-width="fit" data-style="btn-sm btn-secondary" data-container="body">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="-1">Semua</option>
            </select>`,
            oPaginate: {
                sPrevious: "Sebelumnya",
                sNext: "Selanjutnya",
            },
        },
        "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
        fnDrawCallback: function (settings, display) {
            $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
            //Custom Table
            $("#dt-peminjaman_length select").selectpicker(),
            $('[data-bs-toggle="tooltip"]').tooltip({ 
                trigger: "hover"
            }).on("click", function () {
                $(this).tooltip("hide");
            });
            $('.image-popup').magnificPopup({
                type: 'image', closeOnContentClick: true, closeBtnInside: false, fixedContentPos: true,
                image: {
                    verticalFit: true
                }
            });
            var api = this.api();
			var rows = api.rows({
				page: 'current'
			}).nodes();
			var last = null;
			api.column(1, {
				page: 'current'
			}).data().each(function (group, i) {
				if (last !== group) {
					$(rows).eq(i).before(
						'<tr class="align-middle "><td class="bg-secondary" colspan="8"><b><i class="mdi mdi-calendar-range me-2"></i> ' + group + '</b></td></tr>'
					);
					last = group;
				}
			});
        },
    });
    $("#dt-peminjaman").css("width", "100%");
}
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_alat'){
        location.reload(true);
    }else if(card=='form_pengembalian'){
        $('#card-PengembalianSrc').hide();
    }
    $('#card-form').hide(), $('#card-data').show();
}
const _viewAlatPinjaman = (idp) => {
    let target = document.querySelector("#card-data"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: BASE_URL+ '/app_admin/ajax/load_modal_alat_pinjaman',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('#sectionModalAlatPinjaman').html(data.alatPinjaman);
                $('#modalViewAlatPinjaman .modal-title').html('<h3 class="fw-bolder fs-4 text-gray-900"><i class="bi bi-cart-plus fs-2 text-gray-900 me-2"></i>Alat Pinjaman | '+ data.namaPeminjam +' ('+ data.noPeminjaman +')</h3>');
                $('#modalViewAlatPinjaman').modal('show');
            } else {
                Swal.fire({title: "Ooops!", text: data.message, icon: "warning", allowOutsideClick: false});
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            blockUi.release(), blockUi.destroy();
            console.log("load data is error!");
            Swal.fire({
                title: "Ooops!",
                text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                icon: "error",
                allowOutsideClick: false,
            });
        },
    });
}
const _actionPermohonan = (fid_peminjaman, aksi, nama, no_peminjaman) => {
    if (aksi == 'TERIMA') {
        $('#modalAksiPengajuan .modal-title').html('<h3 class="fw-bolder fs-4 text-gray-900">(<span class="text-success">Terima</span>) Pengajuan Peminjaman Alat Praktek | '+ nama +' - '+ no_peminjaman +'</h3>');
    }else{
        $('#modalAksiPengajuan .modal-title').html('<h3 class="fw-bolder fs-4 text-gray-900">(<span class="text-danger">TOLAK</span>) Pengajuan Peminjaman Alat Praktek | '+ nama +' - '+ no_peminjaman +'</h3>');
    }
    $('#keterangan').val('');
    $('[name="fid_peminjaman"]').val(fid_peminjaman);
    $('[name="jenis_aksi"]').val(aksi);
    $('#modalAksiPengajuan').modal('show');
}
// send aksi jadwal praktek
$("#btn-send-action").on("click", function (e) {
    e.preventDefault();
    $("#btn-send-action").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);

    var keterangan = $('#keterangan');
    var jenis_aksi = $('[name="jenis_aksi"]');
    
    if (jenis_aksi.val() == 'TERIMA') {
        if (keterangan.val() == '') {
            toastr.error('Catatan penerimaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            keterangan.focus();
            $('#btn-send-action').html('<i class="bi bi-send"></i> Kirim').attr('disabled', false);
            return false;
        }
        var textConfirmSave = "Apakah kamu yakin akan menerima pengajuan ini?";
    }else{
        if (keterangan.val() == '') {
            toastr.error('Catatan penolakan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            keterangan.focus();
            $('#btn-send-action').html('<i class="bi bi-send"></i> Kirim').attr('disabled', false);
            return false;
        }
        var textConfirmSave = "Apakah kamu yakin akan menolak pengajuan ini?";
    }
    Swal.fire({
        title: "",
        text: textConfirmSave,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let formData = new FormData($("#form-aksiPengajuan")[0]), ajax_url = BASE_URL+ "/app_admin/ajax/peminjaman_alat_send_action";
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-send-action').html('<i class="bi bi-send"></i> Kirim').attr('disabled', false);
                    if (data.status == true) {
                        var message = 'Pengajuan berhasil ditolak'
                        if (jenis_aksi.val() == 'TERIMA') {
                            var message = 'Pengajuan berhasil diterima'
                        }
                        Swal.fire({
                            title: "Success!",
                            text: message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            $('#modalAksiPengajuan').modal('hide'), _loadDataPeminjaman(),_loadNotification();
                        });
                    } else {
                        if(data.pesan_code=='format_inputan') {   
                            Swal.fire({
                                title: "Ooops!",
                                html: data.pesan_error[0],
                                icon: "warning",
                                allowOutsideClick: false,
                            });
                        } else {
                            Swal.fire({
                                title: "Ooops!",
                                html: data.pesan_error,
                                icon: "warning",
                                allowOutsideClick: false,
                            });
                        }
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-send-action').html('<i class="bi bi-send"></i> Kirim').attr('disabled', false);
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $('#btn-send-action').html('<i class="bi bi-send"></i> Kirim').attr('disabled', false);
        }
    });
});
// ===>> THIS BELLOW FOR HANDLE PENGEMBALIAN ALAT PINJAMAN <<===//
// Class definition
var KTPengembalianAlat = function () {
    // Header pinjaman
    var _handlePengembalianAlat = (idp_pinjaman) => {
        $.ajax({
            url: BASE_URL+ '/app_admin/ajax/load_pengembalian_alat_detail',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'GET',
            dataType: 'JSON',
            data: {
                idp_pinjaman
            },
            success: function (data) {
                $('#detailHeaderPinjaman2').html(data.headerPeminjaman);
                $('#listAlatPinjaman').html(data.alatPinjaman);
                $('.btn-konfirmasiPengembalianSrc').html(data.btnKOnfirmasi);
                $('#listAlatPinjaman').magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    tLoading: 'Sedang memuat foto #%curr%...',
                    mainClass: 'mfp-img-mobile',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: false,
                        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
                    },
                    image: {
                        tError: '<a href="%url%">Foto #%curr%</a> tidak dapat dimuat...',
                        titleSrc: function(item) {
                            return item.el.attr('title') + '<small>'+item.el.attr('subtitle')+'</small>';
                        }
                    }
                });
                $('#card-data').hide();
                $('#card-PengembalianSrc').show();
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log("load data is error!");
                Swal.fire({
                    title: "Ooops!",
                    text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                    icon: "error",
                    allowOutsideClick: false,
                });
            },
        });
    }
    // Public methods
    return {
        init: function (idp_pinjaman) {
            _handlePengembalianAlat(idp_pinjaman);
        }
    }
}();
// handle confirm pengembalian alat pinjaman
function _condirmPengembalianAlat(idp_pinjaman){
    $("#btn-confirmPengembalian").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Memproses data...').attr("disabled", true);
    Swal.fire({
        title: "Konfirmasi Pengembalian",
        text: 'Alat ini sudah dikembalikan?',
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#card-PengembalianSrc"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();

            $.ajax({
                url: BASE_URL+ "/app_admin/ajax/confirm_pengembalian_alat",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                dataType: 'JSON',
                data: {
                    idp_pinjaman
                },success: function (data) {
                    $('#btn-confirmPengembalian').html('<i class="bi bi-check-all fs-4"></i> Konfirmasi Pengembalian').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        Swal.fire({
                            title: "Success!",
                            text: "Konfirmasi pengembalian alat peraga sukses dilakukan...",
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_pengembalian'), _loadDataPeminjaman();
                        });
                    } else {
                        Swal.fire({
                            title: "Ooops!",
                            text: "Proses data error, Periksa koneksi internet anda lalu coba kembali.",
                            icon: "warning",
                            allowOutsideClick: false,
                        });
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#btn-confirmPengembalian').html('<i class="bi bi-check-all fs-4"></i> Konfirmasi Pengembalian').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $('#btn-confirmPengembalian').html('<i class="bi bi-check-all fs-4"></i> Konfirmasi Pengembalian').attr('disabled', false);
        }
    });
}
// handle pengembalian alat pinjaman
function _kelolaPengembalian(idp_pinjaman){
    KTPengembalianAlat.init(idp_pinjaman);
}
