"use strict";
// Class Definition
var table;
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-dark"><span class="spinner-border text-primary"></span> Mohon Tunggu ...</div>';
// load detail laboratorium
function _viewDetailLaboratorium(idp_lab) {
    var target = document.querySelector('#kt_app_content_container'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
    blockUi.block(), blockUi.destroy();
    $.ajax({
        url: BASE_URL+ "/ajax_load_detail_laboratorium",
        type: "GET",
        dataType: "JSON",
        data: {
            idp_lab
        },success: function (data) {
            blockUi.release(), blockUi.destroy();
            $('#dt-detailLaboratorium').html(`
                <div class="row d-flex justify-content-between">
                    <div class="col-md-3">
                        <div class="row g-3 g-lg-6">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                <img src="`+data.url_thumbnail+`" alt="user-image">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="profile-laboratorium">
                            <div class="d-flex flex-column">
                                <a href="javascript:void(0);" class="text-gray-900 text-hover-primary fs-6 fw-bold">PROFILE LABORATORIUM</a>
                            </div>
                            <div style="padding-left: 30px;">
                                <!--begin::Row-->
                                <div class="row mt-4">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Nama Laboratorium</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">                    
                                        <span class="fw-bold fs-6 text-gray-800">`+ data.lab.nama_laboratorium +`</span>                
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row mt-2">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Program Studi</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">                    
                                        <span class="fw-bold fs-6 text-gray-800">`+ data.prodi +`</span>                
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row mt-2">
                                    <!--begin::Label-->
                                    <label class="col-lg-3 fw-semibold text-muted">Alamat</label>
                                    <!--end::Label-->
                                    <!--begin::Col-->
                                    <div class="col-lg-8">                    
                                        <span class="fw-bold fs-6 text-gray-800">`+ data.lab.alamat +`</span>                
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                        </div>
                        <div class="daftar-alat-peraga mt-5">
                            <div class="d-flex flex-column">
                                <a href="javascript:void(0);" class="text-gray-900 text-hover-primary fs-6 fw-bold">DAFTAR ALAT PERAGA</a>
                            </div>
                            <div style="padding-left: 30px;">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table table-rounded table-hover align-middle table-row-bordered border gy-3 gs-3" id="dt-alatPeraga">
                                        <thead>
                                            <tr class="fw-bolder text-uppercase bg-secondary text-dark">
                                                <th class="text-center align-middle px-2 border-bottom-1 border-primary">No.</th>
                                                <th class="align-middle px-2 border-bottom-1 border-primary">Kode Alat</th>
                                                <th class="align-middle px-2 border-bottom-1 border-primary">Nama Alat</th>
                                                <th class="align-middle px-2 border-bottom-1 border-primary">Jumlah</th>
                                                <th class="align-middle px-2 border-bottom-1 border-primary">Satuan</th>
                                                <th class="align-middle px-2 border-bottom-1 border-primary">Foto</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            $('#sectionDetailLaboratorium').show();
            $('html, body').animate({
                scrollTop: $("#sectionDetailLaboratorium").offset().top
            }, 1000); 
        }, complete: function(){
            _viewDatatableAlatPeraga(idp_lab);
        }, error: function (jqXHR, textStatus, errorThrown) {
            blockUi.release(), blockUi.destroy();
            console.log('Load data is error');
        }
    });
}
// datatables alat peraga
function _viewDatatableAlatPeraga(idp_lab){
    table = $('#dt-alatPeraga').DataTable({
        // searchDelay: 300,
        processing: true,
        serverSide: false,
        ajax: {
            "url" : BASE_URL+ "/ajax_load_alat_peraga",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            "data"  : function ( data ) {
                data.idp_lab  = idp_lab;
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
            { data: 'kode_alat_peraga', name: 'kode_alat_peraga'},
            { data: 'nama_alat_peraga', name: 'nama_alat_peraga'},
            { data: 'jumlah', name: 'jumlah'},
            { data: 'satuan', name: 'satuan'},
            { data: 'foto', name: 'foto'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center" },
            { "width": "15%", "targets": 1, "className": "align-top text-center"},
            { "width": "30%", "targets": 2, "className": "align-top" },
            { "width": "10%", "targets": 3, "className": "align-top" },
            { "width": "15%", "targets": 4, "className": "align-top text-center" },
            { "width": "15%", "targets": 5, "className": "align-top text-center", searchable:false, orderable:false},
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
        },
    });
    $("#dt-alatPeraga").css("width", "100%");
}
// close detail laboratoum
$("#closeDetailLaboratorium").on("click", function() {
    $('html, body').animate({
        scrollTop: $("#sectionLaboratorium").offset().top
    }, 1000); 
    setTimeout(function() {
        $('#sectionDetailLaboratorium').hide();
    }, 1000);
});
// kt handle form pengajuan jadwal praktek
var KTFormPengajuan = function () {
    const _handleparadonsForm = function() {
        $(".datepicker").flatpickr({
            defaultDate: '',
            dateFormat: "d/m/Y"
        });
        $(".timepicker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
        });
        $('.inputmax20').mask('00000000000000000000'),  $('.inputmax11').mask('0000000000');
    }
	var _handleSubmitForm = function() {
		// Handle submit button
		$('#btn-save').on('click', function (e) {
			e.preventDefault();
			$('#btn-save').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
			const fid_lab = $('#fid_lab'), nik_instruktur = $('#nik_instruktur'), nama_instruktur = $('#nama_instruktur'), no_wa = $('#no_wa'), jmlh_peserta = $('#jmlh_peserta'), judul_praktek = $('#judul_praktek'), tanggal = $('#tanggal'), jam_awal = $('#jam_awal'), jam_akhir = $('#jam_akhir'), ketereangan = $('#ketereangan');
            
			if (fid_lab.val() == '' || fid_lab.val() == null) {
				toastr.error('Laboratorium masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				fid_lab.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (nik_instruktur.val() == '') {
				toastr.error('NIK instruktur masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				nik_instruktur.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (nama_instruktur.val() == '') {
				toastr.error('Nama instruktur masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				nama_instruktur.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (no_wa.val() == '') {
				toastr.error('Nomor whatsapp masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				no_wa.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (jmlh_peserta.val() == '') {
				toastr.error('Jumlah peserta masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				jmlh_peserta.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (judul_praktek.val() == '') {
				toastr.error('Judul praktek masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				judul_praktek.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (tanggal.val() == '') {
				toastr.error('Tanggal penggunaan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				tanggal.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (jam_awal.val() == '') {
				toastr.error('Waktu penggunaan belum lengkap...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				jam_awal.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (jam_akhir.val() == '') {
				toastr.error('Waktu penggunaan belum lengkap...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				jam_akhir.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}if (ketereangan.val() == '') {
				toastr.error('Keterangan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				ketereangan.focus();
				$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
				return false;
			}

			var target = document.querySelector('#kt-form-pengajuan'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
			blockUi.block(), blockUi.destroy();
			let formData = new FormData($('#form-jadwalPraktek')[0]), ajax_url= BASE_URL+ "/ajax_save_jadwal_praktek";
			$.ajax({
				url: ajax_url,
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					if (data.status==true){
						Swal.fire({title: "Sukses!", html: "Terima kasih atas pengiriman formulir Anda! Kami telah menerima data Anda dengan sukses. Tim pengelola kami akan segera memproses informasi yang Anda berikan. Harap tunggu, Anda akan segera menerima balasan melalui WhatsApp.", icon: "success", allowOutsideClick: false}).then(function (result) {
							$("#form-jadwalPraktek")[0].reset();
						});
					}else{
						if(data.pesan_code=='format_inputan') { 
							Swal.fire({title: "Ooops!", text: data.pesan_error[0], icon: "warning", allowOutsideClick: false});  
						} else if(data.pesan_error) {
							Swal.fire({title: "Ooops!", text: data.pesan_error, icon: "warning", allowOutsideClick: false});  
						}else{
							Swal.fire({title: "Ooops!", text: "Gagal memproses data, Periksa form inputan yang tersedia lalu coba kembali.", icon: "error", allowOutsideClick: false});   
						}
					}
				}, error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-save').html('<i class="bi bi-send"></i> Kirim Pengajuan').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
	}
    var _handleCancelSubmit = function() {
		$('#btn-reset').click(function(){
            $("#form-jadwalPraktek")[0].reset();
        });
	}
    // Custom Select Master Laboratorium
    const loadSelectpicker_laboratorium = function() {
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
                $('#fid_lab').html(output).selectpicker('refresh').selectpicker('val', '');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Load data is error');
            }
        });
    }
    // Public methods
    return {
        init: function () {
            _handleparadonsForm();
            _handleSubmitForm();
            _handleCancelSubmit();
            loadSelectpicker_laboratorium();
        }
    }
}();
// search data pegawai
$('#search-dtPegawai').click(function(){
    let nik_instruktur = $('#nik_instruktur');
    $('#search-dtPegawai').html('<span class="spinner-border spinner-border-sm align-middle"></span>').attr('disabled', true);
    if (nik_instruktur.val() == '') {
        toastr.error('NIK masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        nik_instruktur.focus();
        $('#search-dtPegawai').html('<i class="bi bi-search text-white me-1"></i> Cari').attr('disabled', false);
        return false;
    }
    $.ajax({
        url: BASE_URL+ "/ajax_get_data_pegawai",
        type: "GET",
        dataType: "JSON",
        data: {
            nik:nik_instruktur.val()
        },success: function (data) {
            $('#search-dtPegawai').html('<i class="bi bi-search text-white me-1"></i> Cari').attr('disabled', false);
            if(data.row != ''){
                $('#nama_instruktur').val(data.row.nama),
                $('#no_wa').val(data.row.telp);
            }else{
                toastr.error('Data tidak ditemukan, lakukan penginputan data secara manual', 'Uuppss!', {"progressBar": true, "timeOut": 3500});
                $('#nama_instruktur').val(''),
                $('#no_wa').val('');
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            blockUi.release(), blockUi.destroy();
            console.log('Load data is error');
        }
    });
});
// Class Initialization
jQuery(document).ready(function() {
    KTFormPengajuan.init();
});