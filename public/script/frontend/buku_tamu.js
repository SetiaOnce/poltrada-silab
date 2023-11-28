"use strict";
// Class Definition
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-dark"><span class="spinner-border text-primary"></span> Mohon Tunggu ...</div>';
// FORM CLASS buku tamu
var KTbukuTamu = function() {
	// private function submit form buku tamu
	var _handleSubmitForm = function() {
		$('#nama_instansi').focus();
		// Handle submit button
		$('#btn-save').on('click', function (e) {
			e.preventDefault();
			$('#btn-save').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
			var nama_instansi = $('#nama_instansi');
			var nama_kegiatan = $('#nama_kegiatan');
			var tanggal_kunjungan = $('#tanggal_kunjungan');
			var foto = $('#foto');
			var preview_foto = $('#iGroup-foto .dropify-preview .dropify-render').html();
			if (nama_instansi.val() == '') {
				toastr.error('Nama instansi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				nama_instansi.focus();
				$('#btn-save').html('<i class="far fa-save"></i> Simpan Buku Tamu').attr('disabled', false);
				return false;
			}if (nama_kegiatan.val() == '') {
				toastr.error('Nama kegiatan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				nama_kegiatan.focus();
				$('#btn-save').html('<i class="far fa-save"></i> Simpan Buku Tamu').attr('disabled', false);
				return false;
			}if (tanggal_kunjungan.val() == '') {
				toastr.error('Tanggal kunjungan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				tanggal_kunjungan.focus();
				$('#btn-save').html('<i class="far fa-save"></i> Simpan Buku Tamu').attr('disabled', false);
				return false;
			}if (preview_foto == '') {
				toastr.error('Foto kunjungan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				$('#iGroup-foto .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
					$(this).removeClass('border-2 border-danger');
				});
				foto.focus();
				$('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
				return false;
			}

			var target = document.querySelector('#kt-form-tamu'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
			blockUi.block(), blockUi.destroy();
			let formData = new FormData($('#form-bukuTamu')[0]), ajax_url= BASE_URL+ "/ajax_save_buku_tamu";
			$.ajax({
				url: ajax_url,
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					$('#btn-save').html('<i class="far fa-save"></i> Simpan Buku Tamu').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					if (data.status==true){
						Swal.fire({title: "Sukses!", text: "Formulir yang Anda masukkan telah berhasil disimpan. Terima kasih atas partisipasi Anda!", icon: "success", allowOutsideClick: false}).then(function (result) {
							$("#form-bukuTamu")[0].reset();
							_loadDropifyFile('', '#foto');
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
					$('#btn-save').html('<i class="far fa-save"></i> Simpan Buku Tamu').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
	}
	// private function form paradons
	var _handleparadonsForm = function() {
		//FLATPICKER OPTIONS
		var tglKunjungan = $('#tanggal_kunjungan').val();
		$("#tanggal_kunjungan").flatpickr({
			defaultDate: tglKunjungan,
			dateFormat: "d/m/Y"
		});
		// Hanlde reset form
		$('#btn-reset').click(function(){
			$("#form-bukuTamu")[0].reset();
		});
	}
    // Public Functions
    return {
        // public functions
        init: function() {
			_handleparadonsForm();
            _handleSubmitForm();
        }
    };
}();

//Load File Dropify
const _loadDropifyFile = (url_file, paramsId) => {
	if (url_file == "") {
		let drEvent1 = $(paramsId).dropify({
			defaultFile: '',
		});
		drEvent1 = drEvent1.data('dropify');
		drEvent1.resetPreview();
		drEvent1.clearElement();
		drEvent1.settings.defaultFile = '';
		drEvent1.destroy();
		drEvent1.init();
	} else {
		let drEvent1 = $(paramsId).dropify({
			defaultFile: url_file,
		});
		drEvent1 = drEvent1.data('dropify');
		drEvent1.resetPreview();
		drEvent1.clearElement();
		drEvent1.settings.defaultFile = url_file;
		drEvent1.destroy();
		drEvent1.init();
	}
}
//begin::Dropify
$('.dropify-upl').dropify({
	messages: {
		'default': '<span class="btn btn-sm btn-secondary">Drag/ drop file atau Klik disini</span>',
		'replace': '<span class="btn btn-sm btn-primary"><i class="fas fa-upload"></i> Drag/ drop atau Klik untuk menimpa file</span>',
		'remove':  '<span class="btn btn-sm btn-danger"><i class="las la-trash-alt"></i> Reset</span>',
		'error':   'Ooops, Terjadi kesalahan pada file input'
	}, error: {
		'fileSize': 'Ukuran file terlalu besar, Max. ( {{ value }} )',
		'minWidth': 'Lebar gambar terlalu kecil, Min. ( {{ value }}}px )',
		'maxWidth': 'Lebar gambar terlalu besar, Max. ( {{ value }}}px )',
		'minHeight': 'Tinggi gambar terlalu kecil, Min. ( {{ value }}}px )',
		'maxHeight': 'Tinggi gambar terlalu besar, Max. ( {{ value }}px )',
		'imageFormat': 'Format file tidak diizinkan, Hanya ( {{ value }} )'
	}
});

// Class Initialization
jQuery(document).ready(function() {
    KTbukuTamu.init(), _loadDropifyFile('', '#foto');
});