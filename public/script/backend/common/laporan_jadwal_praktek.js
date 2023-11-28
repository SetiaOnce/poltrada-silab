var table;
//Class Initialization
jQuery(document).ready(function() {
    _loadListJadwalPraktek(),loadSelectpicker_laboratorium();
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
            _loadListJadwalPraktek();
        });
        // Reset filter data
        $('#btn-resetFilter').click(function(){
            $("#filterDt-startDate").val('');
            $("#filterDt-endDate").val('');
            $('#filterLaboratorium').selectpicker('val', '');
            _loadListJadwalPraktek();
        });
});
//Load Datatables 
const _loadListJadwalPraktek = () => {
    table = $('#dt-jadwalPraktek').DataTable({
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
        // searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_laporan_jadwal_praktek",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            data: function ( data ) {
                data.tgl_start= $('#filterDt-startDate').val();
                data.tgl_end= $('#filterDt-endDate').val();
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
            { data: 'nik_instruktur', name: 'nik_instruktur'},
            { data: 'nama_instruktur', name: 'nama_instruktur'},
            { data: 'no_wa', name: 'no_wa'},
            { data: 'waktu', name: 'waktu'},
            { data: 'jmlh_peserta', name: 'jmlh_peserta'},
            { data: 'judul_praktek', name: 'judul_praktek'},
            { data: 'status', name: 'status'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "10%", "targets": 1, "className": "align-top","visible": false},
            { "width": "10%", "targets": 2, "className": "align-top" },
            { "width": "20%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center" },
            { "width": "10%", "targets": 5, "className": "align-top text-center", searchable:false, orderable:false},
            { "width": "10%", "targets": 6, "className": "align-top text-center", searchable:false, orderable:false},
            { "width": "20%", "targets": 7, "className": "align-top", searchable:false, orderable:false},
            { "width": "10%", "targets": 8, "className": "align-top", searchable:false, orderable:false},
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