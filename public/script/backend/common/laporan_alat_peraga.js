//Class Definition
var save_method;
var table;
//Load Datatables 
const _loadDataAlatPeraga = () => {
    table = $('#dt-alatPeraga').DataTable({
        buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },{
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },{
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }
        ],
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_laporan_alat_peraga",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            "data"  : function ( data ) {
                data.filter_lokasi  = $('#filterLokasi').val();
                data.filter_laboratorium  = $('#filterLaboratorium').val();
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
            { data: 'laboratorium', name: 'laboratorium'},
            { data: 'kode_alat_peraga', name: 'kode_alat_peraga'},
            { data: 'nama_alat_peraga', name: 'nama_alat_peraga'},
            { data: 'jumlah', name: 'jumlah'},
            { data: 'satuan', name: 'satuan'},
            { data: 'foto', name: 'foto'},
            { data: 'detail', name: 'detail'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "20%", "targets": 1, "className": "align-top","visible": false},
            { "width": "15%", "targets": 2, "className": "align-top" },
            { "width": "30%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center" },
            { "width": "10%", "targets": 5, "className": "align-top text-center", searchable:false, orderable:false},
            { "width": "10%", "targets": 6, "className": "text-center", searchable:false, orderable:false},
            { "width": "10%", "targets": 7, "className": "text-center", searchable:false, orderable:false},
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
            $("#dt-alatPeraga_length select").selectpicker(),
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
						'<tr class="align-middle "><td class="bg-secondary" colspan="8"><b><i class="mdi mdi-animation mr-2"></i> ' + group + '</b></td></tr>'
					);
					last = group;
				}
			});
        },
    });
    $("#dt-alatPeraga").css("width", "100%");
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
/*************************
    Custom Select Laboratorium
*************************/
function loadSelectpicker_laboratorium() {
    $.ajax({
        url: BASE_URL+ "/select/ajax_getlaboratorium",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].nama_laboratorium + '</option>';
            }
            $('#filterLaboratorium').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
/*************************
    Custom Select Lokasi
*************************/
function loadSelectpicker_lokasi() {
    $.ajax({
        url: BASE_URL+ "/select/ajax_getlokasi",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].nama_lokasi + '</option>';
            }
            $('#filterLokasi').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
/*handle close detail*/
const _closeDetail = () => {
    $('#card-detail').hide(), $('#card-data').show();
} 
/*handle view detail alat peraga*/ 
const _detailAlatPeraga = (idp) =>{
    let target = document.querySelector("#card-data"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: BASE_URL+ '/app_admin/ajax/load_detail_alat_peraga',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            $('#sectionDetailAlat .detailInformasiAlat').html(data.detailInformasiAlat);
            $('#sectionDetailAlat .dataPerawatan').html(data.dataPerawatan);
            $('#sectionDetailAlat .dataPemeriksaan').html(data.dataPemeriksaan);
            $('#card-data').hide(), $('#card-detail').show();
            $('html, body').animate({
                scrollTop: $("#card-detail").offset().top
            }, 1000); 
        }, complete: function(){
            $('#dt-perawatan').DataTable({
                orderable:false,
                "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
                oLanguage: {
                    sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
                    sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
                    sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
                    sInfoFiltered: "",
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
                fnDrawCallback: function (settings, display) {
                    $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
                    //Custom Table
                    $("#dt-perawatan_length select").selectpicker(),
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
                },
            });
            $('#dt-pemeriksaan').DataTable({
                orderable:false,
                "dom": "<'row'<'col-sm-6 d-flex align-items-center justify-conten-start'l><'col-sm-6 d-flex align-items-center justify-content-end'f>><'table-responsive'tr><'row'<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>",
                oLanguage: {
                    sEmptyTable: "Tidak ada Data yang dapat ditampilkan..",
                    sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
                    sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
                    sInfoFiltered: "",
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
                fnDrawCallback: function (settings, display) {
                    $('[data-bs-toggle="tooltip"]').tooltip("dispose"), $(".tooltip").hide();
                    //Custom Table
                    $("#dt-pemeriksaan_length select").selectpicker(),
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
                },
            });
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
    _loadDataAlatPeraga(), loadSelectpicker_laboratorium(), loadSelectpicker_lokasi();
    // apply filter data
    $('#btn-applyFilter').click(function(){
        _loadDataAlatPeraga();
    });
    // Reset filter data
    $('#btn-resetFilter').click(function(){
        $('#filterLaboratorium').selectpicker('val', '');
        $('#filterLokasifilterLokasi').selectpicker('val', '');
        _loadDataAlatPeraga();
    });
});