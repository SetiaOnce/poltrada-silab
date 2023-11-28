//Class Definition
var save_method;
var table;
//Load Datatables 
const _loadLaporanPeminjaman = () => {
    table = $('#dt-laporanPeminjaman').DataTable({
        buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },{
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },{
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            },{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            }
        ],
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_laporan_peminjaman",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            data: function ( data ) {
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
            { data: 'status', name: 'status'},
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
            $("#dt-laporanPeminjaman_length select").selectpicker(),
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
    $("#dt-laporanPeminjaman").css("width", "100%");
    $('#export_print').on('click', function(e) {
        e.preventDefault();
        table.button(0).trigger();
    });
    $('#export_pdf').on('click', function(e) {
        e.preventDefault();
        table.button(1).trigger();
    });
    $('#export_excel').on('click', function(e) {
        e.preventDefault();
        table.button(2).trigger();
    });
    $('#export_copy').on('click', function(e) {
        e.preventDefault();
        table.button(3).trigger();
    });
}
// view data alat pinjaman
const _viewAlatPinjaman = (idp) => {
    let target = document.querySelector("#card-data"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: BASE_URL+ '/app_admin/ajax/load_laporan_alat_pinjaman',
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
//Class Initialization
jQuery(document).ready(function() {
    _loadLaporanPeminjaman();
    //FLATPICKER OPTIONS
    var startDate = $('#filterDt-startDate').val(), endDate = $('#filterDt-endDate').val();
    $("#filterDt-startDate").flatpickr({
        defaultDate: startDate,
        dateFormat: "d/m/Y"
    });
    $("#filterDt-endDate").flatpickr({
        defaultDate: endDate,
        dateFormat: "d/m/Y"
    });
    // apply filter data
    $('#btn-applyFilter').click(function(){
        _loadLaporanPeminjaman();
    });
    // Reset filter data
    $('#btn-resetFilter').click(function(){
        $("#filterDt-startDate").val('');
        $("#filterDt-endDate").val('');
        _loadLaporanPeminjaman();
    });
});