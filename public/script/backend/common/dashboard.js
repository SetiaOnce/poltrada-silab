//Class Definition
var table;
// load transaksi jatuh tempoi
const _tableJatuhTempo = () => {
    table = $('#dt-TransaksiJatuhTempo').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/load_table_transaksi_jatuh_tempo",
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
            { data: 'nama_peminjam', name: 'nama_peminjam'},
            { data: 'tanggal_pinjam', name: 'tanggal_pinjam'},
            { data: 'tanggal_kembali', name: 'tanggal_kembali'},
            { data: 'jumlah_alat', name: 'jumlah_alat'},
            { data: 'no_peminjaman', name: 'no_peminjaman'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "25%", "targets": 1, "className": "align-top" },
            { "width": "20%", "targets": 2, "className": "align-top" },
            { "width": "20%", "targets": 3, "className": "align-top" },
            { "width": "10%", "targets": 4, "className": "align-top text-center" },
            { "width": "10%", "targets": 5, "className": "align-top text-center" },
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
            $("#dt-TransaksiJatuhTempo_length select").selectpicker(),
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
    $("#dt-TransaksiJatuhTempo").css("width", "100%");
}
const _loadTransaksiJatuhTempo = () => {
    let target = document.querySelector("#sectionWidgetDashboardStaff"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: BASE_URL+ '/app_admin/load_transaksi_jatuh_tempo',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            _tableJatuhTempo();
            $('#modalViewTransaksiJatuhTempo').modal('show');
            $('#modalViewTransaksiJatuhTempo .modal-title').html('<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-alarm fs-2 text-gray-900 me-2"></i>Data Transaksi Jatuh Tempo</h3>');
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
};
// Class definition
var KTPDashboard = function () {
    const _loadWidgetDashboard = () => {
        setTimeout(
            function() {
                $.ajax({
                    url: BASE_URL+ "/app_admin/load_widget_dashboard",
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {
                        if(data.level_user == 'perpustakaan-administrator'){
                            $('#sectionWidgetDashboardAdmin').html(`
                                <!--begin::Row-->
                                <div class="row g-3 g-lg-6">
                                    <!--begin::Col-->
                                    <div class="col-6">
                                        <!--begin::Items-->
                                        <div class="bg-primary bg-opacity-70 rounded-2 px-6 py-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px me-5 mb-8">
                                                <span class="symbol-label">  
                                                    <i class="bi bi-tools fs-1 text-primary"></i>                             
                                                </span>                
                                            </div>
                                            <!--end::Symbol-->
                    
                                            <!--begin::Stats-->
                                            <div class="m-0">
                                                <!--begin::Number-->
                                                <span class="text-light fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">`+data.jmlh_alatPeraga+`</span>
                                                <!--end::Number-->
                    
                                                <!--begin::Desc-->
                                                <span class="text-light fw-semibold fs-6">ALAT PERAGA</span>
                                                <!--end::Desc-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>    
                                        <!--end::Items-->
                                    </div>    
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-6">
                                        <!--begin::Items-->
                                        <div class="bg-success bg-opacity-70 rounded-2 px-6 py-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px me-5 mb-8">
                                                <span class="symbol-label">  
                                                    <i class="bi bi-people fs-1 text-success"></i>                             
                                                </span>                
                                            </div>
                                            <!--end::Symbol-->
                    
                                            <!--begin::Stats-->
                                            <div class="m-0">
                                                <!--begin::Number-->
                                                <span class="text-light fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">`+data.jmlh_kunjungan+`</span>
                                                <!--end::Number-->
                    
                                                <!--begin::Desc-->
                                                <span class="text-light fw-semibold fs-6">KUNJUNGAN</span>
                                                <!--end::Desc-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>    
                                        <!--end::Items-->
                                    </div>    
                                    <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                        <!--begin::Items-->
                                        <div class="bg-info bg-opacity-70 rounded-2 px-6 py-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px me-5 mb-8">
                                                <span class="symbol-label">  
                                                    <i class="bi bi-book fs-1 text-info"></i>                             
                                                </span>                
                                            </div>
                                            <!--end::Symbol-->
                    
                                            <!--begin::Stats-->
                                            <div class="m-0">
                                                <!--begin::Number-->
                                                <span class="text-light fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">`+data.jmlh_kegiatanPraktek+`</span>
                                                <!--end::Number-->
                    
                                                <!--begin::Desc-->
                                                <span class="text-light fw-semibold fs-6">KEGIATAN PRAKTEK</span>
                                                <!--end::Desc-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>    
                                        <!--end::Items-->
                                    </div>    
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col-6">
                                        <!--begin::Items-->
                                        <div class="bg-danger bg-opacity-70 rounded-2 px-6 py-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-30px me-5 mb-8">
                                                <span class="symbol-label">  
                                                    <i class="bi bi-file-text fs-1 text-danger"></i>                             
                                                </span>                
                                            </div>
                                            <!--end::Symbol-->
                    
                                            <!--begin::Stats-->
                                            <div class="m-0">
                                                <!--begin::Number-->
                                                <span class="text-light fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">`+data.jmlh_transaksi+`</span>
                                                <!--end::Number-->
                    
                                                <!--begin::Desc-->
                                                <span class="text-light fw-semibold fs-6">TRANSAKSI</span>
                                                <!--end::Desc-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>    
                                        <!--end::Items-->
                                    </div>    
                                    <!--end::Col-->
                                                
                                </div>
                                <!--end::Row-->
                            `);
                        }else{
                            $('#sectionWidgetDashboardStaff').html(`
                                <!--begin::Heading-->
                                <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px" style="background-image:url('`+ data.url_banner +`" data-bs-theme="light">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column text-dark pt-15">
                                    <span class="fw-bolder fs-2x mb-3 text-dark"><i class="bi bi-megaphone fs-1 text-dark"></i> Informasi</span>
                                    
                                    <div class="fs-4 text-dark fw-bolder">
                                        <span class="">Peminjaman Alat Peraga Yang Telah Terlambat dan Jatuh Tempo</span>
                                        <span class="position-relative d-inline-block">
                                            <a href="javascript:void(0);" class="link-white -hover fw-bold d-block mb-1"> `+ data.transaksi_jatuhTempo +`</a>
                                            <!--begin::Separator-->
                                            <span class="position-absolute opacity-50 bottom-0 start-0 border-2 border-body border-bottom w-100"></span>
                                            <!--end::Separator--> 
                                        </span>    
                                        <span class="me-4">Transaksi</span> 
                                        <a href="javascript:void(0);" onclick="_loadTransaksiJatuhTempo()" class="btn btn-sm btn-primary"><i class="mdi mdi-eye fs-4"></i> Lihat Transaksi</a>
                                    </div>    
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Heading-->              
                            <!--begin::Body-->
                            <div class="card-body mt-n20">
                                <!--begin::Stats-->
                                <div class="mt-n20 position-relative">
                                    <!--begin::Row-->
                                    <div class="row g-3 g-lg-6">
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-primary bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">  
                                                        <i class="bi bi-tools fs-1 text-primary"></i>                             
                                                    </span>                
                                                </div>
                                                <!--end::Symbol-->
                        
                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span class="text-light fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">`+data.jmlh_alatPeraga+`</span>
                                                    <!--end::Number-->
                        
                                                    <!--begin::Desc-->
                                                    <span class="text-light fw-semibold fs-6">ALAT PERAGA</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>    
                                            <!--end::Items-->
                                        </div>    
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-success bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">  
                                                        <i class="bi bi-people fs-1 text-success"></i>                             
                                                    </span>                
                                                </div>
                                                <!--end::Symbol-->
                        
                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span class="text-light fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">`+data.jmlh_kunjungan+`</span>
                                                    <!--end::Number-->
                        
                                                    <!--begin::Desc-->
                                                    <span class="text-light fw-semibold fs-6">KUNJUNGAN</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>    
                                            <!--end::Items-->
                                        </div>    
                                        <!--end::Col-->
                                         <!--begin::Col-->
                                         <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-info bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">  
                                                        <i class="bi bi-book fs-1 text-info"></i>                             
                                                    </span>                
                                                </div>
                                                <!--end::Symbol-->
                        
                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span class="text-light fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">`+data.jmlh_kegiatanPraktek+`</span>
                                                    <!--end::Number-->
                        
                                                    <!--begin::Desc-->
                                                    <span class="text-light fw-semibold fs-6">KEGIATAN PRAKTEK</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>    
                                            <!--end::Items-->
                                        </div>    
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-danger bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">  
                                                        <i class="bi bi-file-text fs-1 text-danger"></i>                             
                                                    </span>                
                                                </div>
                                                <!--end::Symbol-->
                        
                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span class="text-light fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">`+data.jmlh_transaksi+`</span>
                                                    <!--end::Number-->
                        
                                                    <!--begin::Desc-->
                                                    <span class="text-light fw-semibold fs-6">TRANSAKSI</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>    
                                            <!--end::Items-->
                                        </div>    
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div> 
                                <!--end::Stats-->
                            </div>    
                            <!--end::Body-->                     
                            `);
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        console.log('Load data is error');
                    }
                });
        }, 500);
    }
    // load trend peminjaman alat
    const _loadTrendPeminjamanAlat = () => {
        var date = new Date();
        var yyyy = date.getFullYear();
        setTimeout( function() {
            var optionsChart = {
                series: [],
                chart: {
                height: 350,
                type: 'area',
                zoom: {
                    enabled: false
                }
              },
              dataLabels: {
                enabled: true,
                textAnchor: 'start',
                formatter: function(val, opt) { 
                return val
                    // return opt.w.globals.categoryLabels[opt.seriesIndex]
                },
                offsetX: 0,
              },
              stroke: {
                show: true,
                curve: 'smooth',
                lineCap: 'butt',
                colors: undefined,
                width: 2,
                dashArray: 0,
              },
                title: {
                    text: ' TREND TRANSAKSI PEMINJAMAN ALAT PERAGA TAHUN '+yyyy,
                    align: 'center'
                },
                grid: {
                    row: {
                    colors: ['#FFFFFF'], // takes an array which will be repeated on columns
                    opacity: 0.5
                    },
                },
              xaxis: {
                categories: [],
                title: {
                    text: 'BULAN (JANUARI s/d DESEMBER)'
                }
              },
              yaxis: {
                title: {
                    text: 'JUMLAH PEMINJAM'
                    }
                },
              fill: {
                    opacity: 1
                },
              tooltip: {
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    }
                },
            };
            var chartPeminjamanAlat = new ApexCharts(document.querySelector("#trend-PeminjamanAlat"), optionsChart);
            chartPeminjamanAlat.render();
            $.ajax({
                url: BASE_URL+ "/app_admin/load_trend_peminjaman_alat",
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    chartPeminjamanAlat.updateOptions({
                        series: [{
                            name: 'JUMLAH PEMINJAM',
                            data: data.amountData,
                        }],
                        xaxis: {
                            categories: data.xavisBulan,
                        },
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Load data is error');
                }
            });
        }, 500);
    }
    // Public methods
    return {
        init: function () {
            _loadWidgetDashboard();
            _loadTrendPeminjamanAlat();
        }
    }
}();
// Class Initialization
jQuery(document).ready(function() {
    KTPDashboard.init();
});