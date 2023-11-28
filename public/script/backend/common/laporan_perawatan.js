//Class Definition
var save_method;
var table;
//Load Datatables 
const _loadLaporanPerawatan = () => {
    table = $('#dt-perawatanAlat').DataTable({
        buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },{
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },{
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            }
        ],
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_laporan_perawatan",
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
            { data: 'alat_peraga', name: 'alat_peraga'},
            { data: 'tanggal_perawatan', name: 'tanggal_perawatan'},
            { data: 'keterangan', name: 'keterangan'},
            { data: 'foto', name: 'foto'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "35%", "targets": 1, "className": "align-top"},
            { "width": "10%", "targets": 2, "className": "align-top text-center" },
            { "width": "20%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center" },
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
            $("#dt-perawatanAlat_length select").selectpicker(),
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
    $("#dt-perawatanAlat").css("width", "100%");
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
//Class Initialization
jQuery(document).ready(function() {
    _loadLaporanPerawatan();
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
        _loadLaporanPerawatan();
    });
    // Reset filter data
    $('#btn-resetFilter').click(function(){
        $("#filterDt-startDate").val('');
        $("#filterDt-endDate").val('');
        _loadLaporanPerawatan();
    });
});