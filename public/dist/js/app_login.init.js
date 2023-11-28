"use strict";
// Class Definition
//Block & Unblock on Div
var messageBlockUi = '<div class="blockui-message"><span class="spinner-border text-primary"></span> Mohon Tunggu...</div>';
// FORM CLASS LOGIN
var KTLogin = function() {
	//SignIn Handle 1
	var _handleSignInForm = function() {
		$('#email').focus();
		//Handle Enter Submit
		$("#form-signIn").keyup(function(event) {
			if (event.keyCode == 13 || event.key === 'Enter') {
				$("#btn-login").click();
			}
		});
		// Handle submit button
		$('#btn-login').on('click', function (e) {
			e.preventDefault();
			$('#btn-login').html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr('disabled', true);
			var email = $('#email');
			var password = $('#password');
			if (email.val() == '') {
				toastr.error('Email masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				email.focus();
				$('#btn-login').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Login').attr('disabled', false);
				return false;
			}
			if (password.val() == '') {
				toastr.error('Password masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
				password.focus();
				$('#btn-login').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Login').attr('disabled', false);
				return false;
			}

			var target = document.querySelector('#kt_sign_in'), blockUi = new KTBlockUI(target, {message: messageBlockUi});
			blockUi.block(), blockUi.destroy();
			let formData = new FormData($('#form-signIn')[0]), ajax_url= BASE_URL+ "/ajax/request_login";
			$.ajax({
				url: ajax_url,
				headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
				dataType: "JSON",
				success: function (data) {
					console.log(data);
					$('#btn-login').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Login').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					if (data.status==true){
						window.location.href = BASE_URL+ "/app_admin/dashboard"
					}else{
						if(data.pesan_code=='format_inputan') { 
							Swal.fire({title: "Ooops!", text: data.pesan_error[0], icon: "warning", allowOutsideClick: false});  
						} else if(data.pesan_error) {
							Swal.fire({title: "Ooops!", text: data.pesan_error, icon: "warning", allowOutsideClick: false});  
						}else{
							Swal.fire({title: "Ooops!", text: "Gagal Login, Periksa koneksi jaringan internet lalu coba kembali.", icon: "error", allowOutsideClick: false});   
						}
					}
				}, error: function (jqXHR, textStatus, errorThrown) {
					$('#btn-login').html('<i class="bi bi-box-arrow-in-right fs-4"></i> Login').attr('disabled', false);
					blockUi.release(), blockUi.destroy();
					Swal.fire({title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, mohon hubungi pengembang!", icon: "error", allowOutsideClick: false}).then(function (result) {
						location.reload(true);
					});
				}
			});
		});
	}
    // Public Functions
    return {
        // public functions
        init: function() {
            _handleSignInForm();
        }
    };
}();

/* Show Hide Password */
var showPass = 0;
$('#showPass').on('click', function () {
	if (showPass == 0) {
		$('#password').attr('type', 'text');
		$(this).find('i').removeClass('bi bi-eye');
		$(this).find('i').addClass('bi bi-eye-slash');
		$(this).attr("title", "Sembunyikan password");
		showPass = 1;
	} else {
		$('#password').attr('type', 'password');
		$(this).find('i').removeClass('bi bi-eye-slash');
		$(this).find('i').addClass('bi bi-eye');
		$(this).attr("title", "Tampilkan password");
		showPass = 0;
	}
});
// System INFO
var loadSystemInfo = function() {
	// Public Functions
	return {
		// public functions
		init: function() {
			$.ajax({
				url: base_url+ "ajax/load_system_login",
				type: "GET",
				dataType: "JSON",
				success: function (data) {
					$('#bg-login').attr('style', 'background: linear-gradient(0deg, rgb(21 33 26), #8be8e58e), url(' +data.url_loginBg+ '); background-size: cover;');
					$('#hLogo-login').html(`<a href="` +base_url+ `login" title="LOGIN - ` +data.row.nama_alias+ `">
						<img src="` +data.url_loginLogo+ `" class="mb-5" height="52" alt="` +data.row.backend_logo_icon+ `">
					</a>`);
					$('#hT-login1').html(`<!--begin::Title-->
					<h1 class="text-dark mb-2">Login</h1>
					<!--end::Title-->
					<!--begin::Sub Title-->
					<div class="text-gray-400 fw-semibold fs-7">Gunakan akun anda</div>
					<!--end::Sub Title-->`);
					$('#kt_footer .copyRight').html(data.row.copyright);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('Load data is error');
				}
			});
		}
	};
}();
// Class Initialization
jQuery(document).ready(function() {
    loadSystemInfo.init(), KTLogin.init();
});