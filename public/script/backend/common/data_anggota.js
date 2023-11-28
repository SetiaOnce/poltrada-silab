//Class Definition
var save_method;
var table;
//Load Datatables banner
const _loadDataAnggota = () => {
    table = $('#dt-anggota').DataTable({
        searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_data_anggota",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            "data"  : function ( data ) {
                data.filterStatus  = $('#filterDtStatus').val();
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
            { data: 'nama', name: 'nama'},
            { data: 'jenis_kelamin', name: 'jenis_kelamin'},
            { data: 'email', name: 'email'},
            { data: 'foto', name: 'foto'},
        ],
        //Set column definition initialisation properties.
        columnDefs: [
            { "width": "5%", "targets": 0, "className": "align-top text-center", "searchable": false},
            { "width": "35%", "targets": 1, "className": "align-top" },
            { "width": "20%", "targets": 2, "className": "align-top  text-center" },
            { "width": "25%", "targets": 3, "className": "align-top", "orderable": false},
            { "width": "15%", "targets": 4, "className": "align-top text-center", "orderable": false, "searchable": false},
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
            $("#dt-anggota_length select").selectpicker(),
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
    $("#dt-anggota").css("width", "100%");
}
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
//Close Content Card by Open Method
const _closeCard = (card) => {
    if(card=='form_anggota') {
        save_method = '';
        _clearForm(), $('#card-form .card-header .card-title').html('');
    }
    $('#card-form').hide(), $('#card-data').show();
}
//Clear Form data
const _clearForm = () => {
    if (save_method == "" || save_method == "add_data") {
        $("#form-data")[0].reset(), _loadDropifyFile('', '#foto'), $('#jenis_kelamin').selectpicker('val', '');
        $('[name="id"]').val("");
    } else {
        let id = $('[name="id"]').val();
        _editData(id);
    }
}
//Add data
const _addData = () => {
    save_method = "add_data";
    _clearForm(),
    $("#card-form .card-header .card-title").html(
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Anggota</h3>`
    ),
    $("#card-data").hide(), $("#card-form").show();
}
//Save data by Enter
$("#form-data input").keyup(function (event) {
    event.preventDefault();
    if (event.keyCode == 13 || event.key === "Enter") {
        $("#btn-save").click();
    }
});
//Save data Form
$("#btn-save").on("click", function (e) {
    e.preventDefault();
    $("#btn-save").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    var nama = $('#nama');
    var nik = $('#nik');
    var telp = $('#telp');
    var email = $('#email');
    var alamat = $('#alamat');
    var jenis_kelamin = $('#jenis_kelamin');
    var foto = $('#foto');
    var priview_fileFoto = $('#iGroup-foto .dropify-preview .dropify-render').html();

    if (nama.val() == '') {
        toastr.error('Nama masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        nama.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (nik.val() == '') {
        toastr.error('NIK masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        nik.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (telp.val() == '') {
        toastr.error('No HP/Whatsapp masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        telp.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (email.val() == '') {
        toastr.error('Email masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (!validateEmail(email.val())) {
        toastr.error('Email tidak valid contoh(example@gmail.com)', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        email.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (alamat.val() == '') {
        toastr.error('Alamat masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        alamat.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (jenis_kelamin.val() == '') {
        toastr.error('Jenis kelamin masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        jenis_kelamin.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (priview_fileFoto == '') {
        toastr.error('Foto anggota masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-foto .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        foto.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }
    let textConfirmSave = "Simpan perubahan data sekarang ?";
    if (save_method == "add_data") {
        textConfirmSave = "Tambahkan data sekarang ?";
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
            let target = document.querySelector("#card-form"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-data")[0]), ajax_url = BASE_URL+ "/app_admin/ajax/data_anggota_save";
            if(save_method == 'update_data') {
                ajax_url = BASE_URL+ "/app_admin/ajax/data_anggota_save";
            }
            $.ajax({
                url: ajax_url,
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    
                    if (data.status == true) {
                        var message = 'Data anggota berhasil diperbarui'
                        if(save_method == 'add_data'){
                            var message = 'Data anggota baru berhasil ditambahkan'
                        }
                        Swal.fire({
                            title: "Success!",
                            text: message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_anggota'), _loadDataAnggota();
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
                    $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        }
    });
});
//Sinkronisasi data
$("#btn-sincrone").on("click", function (e) {
    e.preventDefault();

    Swal.fire({
        title: "",
        text: "Apakah kamu yakin ingin melanjutkan?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#card-data"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            $.ajax({
                url: BASE_URL+ "/app_admin/ajax/data_anggota_sincronise",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: new FormData($("#form-sincrone-data")[0]),
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    if (data.status == true) {
                        var message = 'Sinkronisasi data anggota berhasil dilakukan'
                        Swal.fire({
                            title: "Success!",
                            text: message,
                            icon: "success",
                            allowOutsideClick: false,
                        })
                        _loadDataAnggota();
                    } else {
                        if(data.valueNull) {   
                            Swal.fire({
                                title: "Ooops!",
                                html: 'Pilih salah satu data yang ingin disinkronisasi!',
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
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({
                        title: "Ooops!",
                        text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",
                        icon: "error",
                        allowOutsideClick: false,
                    });
                }
            });
        } else {
            
        }
    });
});
//Class Initialization
jQuery(document).ready(function() {
    _loadDataAnggota();
    $("#telp").mask("000000000000000");
    $("#nik").mask("000000000000000000");
    $('.uppercase').inputmask({casing:'upper'}); 
    $('.nospace').on('keypress', function (e) {
		return e.which !== 32;
	});
    // apply filter data
    $('#btn-applyFilter').click(function(){
        _loadDataAnggota();
    });
    // Reset filter data
    $('#btn-resetFilter').click(function(){
        $('#filterDtStatus').selectpicker('val', '5');
        _loadDataAnggota();
    });
});