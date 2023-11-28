//Class Definition
var save_method;
var table;
//Class Initialization
jQuery(document).ready(function() {
    _loadListJadwalPraktek();
});
//Load Datatables 
const _loadListJadwalPraktek = () => {
    table = $('#dt-jadwalPraktek').DataTable({
        // searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_jadwal_praktek",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
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
            { data: 'laboratorium', name: 'laboratorium'},
            { data: 'nik_instruktur', name: 'nik_instruktur'},
            { data: 'nama_instruktur', name: 'nama_instruktur'},
            { data: 'no_wa', name: 'no_wa'},
            { data: 'waktu', name: 'waktu'},
            { data: 'jmlh_peserta', name: 'jmlh_peserta'},
            { data: 'judul_praktek', name: 'judul_praktek'},
            { data: 'action', name: 'action'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "10%", "targets": 1, "className": "align-top","visible": false},
            { "width": "10%", "targets": 2, "className": "align-top text-center" },
            { "width": "20%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center" },
            { "width": "10%", "targets": 5, "className": "align-top text-center", searchable:false, orderable:false},
            { "width": "10%", "targets": 6, "className": "align-top text-center", searchable:false, orderable:false},
            { "width": "20%", "targets": 7, "className": "align-top", searchable:false, orderable:false},
            { "width": "10%", "targets": 8, "className": "text-center", searchable:false, orderable:false},
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
            $("#dt-jadwalPraktek_length select").selectpicker(),
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
    $("#dt-jadwalPraktek").css("width", "100%");
}
const _actionPermohonana = (idp_jadwal_praktek, aksi, nama, nik) => {
    if (aksi == 'TERIMA') {
        $('#modalAksiPengajuan .modal-title').html('<h3 class="fw-bolder fs-4 text-gray-900">(<span class="text-success">Terima</span>) Pengajuan Jadwal Praktek | '+ nama +' - '+ nik +'</h3>');
    }else{
        $('#modalAksiPengajuan .modal-title').html('<h3 class="fw-bolder fs-4 text-gray-900">(<span class="text-danger">TOLAK</span>) Pengajuan Jadwal Praktek | '+ nama +' - '+ nik +'</h3>');
    }
    $('#keterangan').val('');
    $('[name="idp_jadwal"]').val(idp_jadwal_praktek);
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
            let formData = new FormData($("#form-aksiPengajuan")[0]), ajax_url = BASE_URL+ "/app_admin/ajax/jadwal_praktek_send_action";
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
                        var message = 'Pengajuan Ditolak, reminder WhatsApp sudah terkirim pada nomor terkait'
                        if (jenis_aksi.val() == 'TERIMA') {
                            var message = 'Pengajuan Diterima, reminder WhatsApp sudah terkirim pada nomor terkait'
                        }
                        Swal.fire({
                            title: "Success!",
                            text: message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            $('#modalAksiPengajuan').modal('hide'), _loadListJadwalPraktek(), _loadNotification();
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