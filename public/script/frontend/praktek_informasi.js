"use strict";
var table1; var table2;
// Class Definition
//Block & Unblock on Div
var messageBlockUi = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Mohon Tunggu...</div>';
// FORM CLASS LOGIN
var KTInformationJadwal = function() {
	//laod system 
	var _loadSystemInfo = function() {
		$.ajax({
            url: BASE_URL+ "/ajax/load_system_information",
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.jmlhJadwal < 4) {
                    $("#videoBackground").attr("src", data.video_url);
                    $("#videoBackground")[0].load();
                }else{
                    $("#videoBackground").attr("src", '');
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
	}
	//jadwal praktek now
	var _handleDatatableJadwalNow = function() {
        table1 = $('#dt-listJadwalNow').DataTable({
            processing: false,
            serverSide: false,
            ajax: {
                "url" : BASE_URL+ "/ajax/load_list_jadwal_praktek_now",
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
            paging: false,
            lengthChange: false,
            searching: false,
            info: false,
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'laboratorium', name: 'laboratorium'},
                { data: 'judul_praktek', name: 'judul_praktek'},
                { data: 'nama_instruktur', name: 'nama_instruktur'},
                { data: 'jam', name: 'jam'},
                { data: 'jmlh_peserta', name: 'jmlh_peserta'},
                { data: 'status', name: 'status'},
            ],
            //Set column definition initialisation properties.
            columnDefs: [
                // { "width": "5%", "targets": 0, "className": "align-top text-center", searchable:false, orderable:false},
                { "width": "25%", "targets": 0, "className": "align-top", searchable:false, orderable:false},
                { "width": "25%", "targets": 1, "className": "align-top", searchable:false, orderable:false},
                { "width": "20%", "targets": 2, "className": "align-top", searchable:false, orderable:false},
                { "width": "10%", "targets": 3, "className": "align-top text-center", searchable:false, orderable:false},
                { "width": "10%", "targets": 4, "className": "align-top text-center", searchable:false, orderable:false},
                { "width": "20%", "targets": 5, "className": "align-top text-center", searchable:false, orderable:false},
            ],
            order: [[3, "asc"]],
            oLanguage: {
                sEmptyTable: "Tidak ada Jadwal Praktek yang dapat ditampilkan..",
                sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
                sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
                sInfoFiltered: "",
                sProcessing: `<div class="d-flex justify-content-center align-items-center"><div class="blockui-message"><span class="spinner-border text-primary align-middle me-2"></span> Mohon Tunggu...</div></div>`,
                sZeroRecords: "Tidak ada Jadwal Praktek yang dapat ditampilkan..",
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
                $("#dt-listJadwalNow_length select").selectpicker(),
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
        $("#dt-listJadwalNow").css("width", "100%");
	}
	//jadwal praktek schedule
	var _handleDatatableOnSchedule = function() {
        table2 = $('#dt-listJadwalSchedule').DataTable({
            processing: false,
            serverSide: false,
            ajax: {
                "url" : BASE_URL+ "/ajax/load_list_jadwal_praktek_schedule",
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
            paging: false,
            lengthChange: false,
            searching: false,
            info: false,
            columns: [
                { data: 'laboratorium', name: 'laboratorium'},
                { data: 'jam', name: 'jam'},
            ],
            //Set column definition initialisation properties.
            columnDefs: [
                { "width": "65%", "targets": 0, "className": "align-top", searchable:false, orderable:false},
                { "width": "35%", "targets": 1, "className": "align-top text-center", searchable:false, orderable:false},
            ],
            oLanguage: {
                sEmptyTable: "Tidak ada Jadwal Praktek yang dapat ditampilkan..",
                sInfo: "Menampilkan _START_ s/d _END_ dari _TOTAL_",
                sInfoEmpty: "Menampilkan 0 - 0 dari 0 entri.",
                sInfoFiltered: "",
                sProcessing: `<div class="d-flex justify-content-center align-items-center"><div class="blockui-message"><span class="spinner-border text-primary align-middle me-2"></span> Mohon Tunggu...</div></div>`,
                sZeroRecords: "Tidak ada Jadwal Praktek yang dapat ditampilkan..",
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
                $("#dt-listJadwalSchedule_length select").selectpicker(),
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
        $("#dt-listJadwalSchedule").css("width", "100%");
	}
    // Public Functions
    return {
        // public functions
        init: function() {
            _loadSystemInfo();
            _handleDatatableJadwalNow();
            _handleDatatableOnSchedule();
        }
    };
}();
// handle show time
function showTime(){
    var date = new Date();
    var h = date.getHours(); 
    var m = date.getMinutes();
    var s = date.getSeconds();
    h = h+1;
    if(h == 0){
        h = 12;
    }
    
    h = (h < 10) ? "0" + h : h;
    m = (m < 10) ? "0" + m : m;
    s = (s < 10) ? "0" + s : s;
    
    document.getElementById("clockDisplayHour").innerText = h;
    document.getElementById("clockDisplayHour").textContent = h;
    document.getElementById("clockDisplayMeniute").innerText = m;
    document.getElementById("clockDisplayMeniute").textContent = m;
    document.getElementById("clockDisplaySecond").innerText = s;
    document.getElementById("clockDisplaySecond").textContent = s;
    
    setTimeout(showTime, 1000);
}
// Class Initialization
jQuery(document).ready(function() {
    KTInformationJadwal.init();
    showTime();
});
setInterval(KTInformationJadwal.init, 1800000);
