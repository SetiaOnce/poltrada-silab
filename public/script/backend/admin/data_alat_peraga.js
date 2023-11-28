//Class Definition
var save_method;
var table;
//Class Initialization
jQuery(document).ready(function() {
    _loadDataAlatPeraga();
    // selectpicker
    loadSelectpicker_satuan(), loadSelectpicker_laboratorium(), loadSelectpicker_lokasi();
    //status changer
    $('#status').change(function() {
        if(this.checked) {
            $('#iGroup-Status .form-check-label').text('AKTIF');
        }else{
            $('#iGroup-Status .form-check-label').text('TIDAK AKTIF');
        }
    });
});
//Load Datatables 
const _loadDataAlatPeraga = () => {
    table = $('#dt-alatPeraga').DataTable({
        // searchDelay: 300,
        processing: true,
        serverSide: true,
        ajax: {
            "url" : BASE_URL+ "/app_admin/ajax/load_alat_peraga",
            'headers': { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            'type': 'POST',
            // "data"  : function ( data ) {
            //     data.filterStatus  = $('#filterDtStatus').val();
            //     data.filterKategori  = $('#filterDtKategori').val();
            // }
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
            { data: 'action', name: 'action'},
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
}
/*************************
    Custom Select satuan
*************************/
function loadSelectpicker_satuan() {
    $.ajax({
        url: BASE_URL+ "/select/ajax_getsatuan",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].satuan + '</option>';
            }
            $('#fid_satuan').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
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
            $('#fid_lab').html(output).selectpicker('refresh').selectpicker('val', '');
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
            $('#fid_lokasi').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
    });
}
// file dokumen
function load_filedok(url_file, paramsId, paramViews){
    if(url_file==''){
        //Upload File
        $(paramsId).fileinput({
            maxFileSize: 3024, //3MB
            language: "id", showUpload: false, dropZoneEnabled: false,
            allowedFileExtensions: ["pdf"], browseClass: "btn btn-dark btn-file btn-square rounded-right",
            browseLabel: "Cari File...", showCancel: false, removeLabel: "Hapus"
        }),
        $(paramViews).html('').hide();
    }else{
        // Upload File
        $(paramsId).fileinput({
            maxFileSize: 3024, //3MB
            language: "id", showUpload: false, dropZoneEnabled: false,
            allowedFileExtensions: ["pdf"], browseClass: "btn btn-dark btn-file btn-square rounded-right",
            browseLabel: "Cari File...", showCancel: false, removeLabel: "Hapus"
        });
        var setToView = `<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="` +url_file+ `" height="100%" frameborder="0">Your browser does not support open file <code>` +url_file+ `</code>.</iframe></div>`;
        $(paramViews).html(setToView).show();
    }
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
    if(card=='form_alat_peraga') {
        save_method = '';
        _clearForm(), $('#card-form .card-header .card-title').html('');
    }
    $('#card-form').hide(), $('#card-data').show();
}
//Clear Form data
const _clearForm = () => {
    if (save_method == "" || save_method == "add_data") {
        $("#form-data")[0].reset(), _loadDropifyFile('', '#foto'), $('#satuan').selectpicker('val', ''), $('#fid_lab').selectpicker('val', ''), $('#fid_lokasi').selectpicker('val', '');
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
        `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-window-plus fs-2 text-gray-900 me-2"></i>Form Tambah Data Alat Peraga</h3>`
    ),
    $("#card-data").hide(), $("#card-form").show();
}
//Edit data
const _editData = (idp) => {
    save_method = "update_data";
    let target = document.querySelector("#card-form"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
    blockUi.block(), blockUi.destroy();
    //Ajax load from ajax
    $.ajax({
        url: BASE_URL+ '/app_admin/ajax/alat_peraga_edit',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            idp,
        },
        success: function (data) {
            blockUi.release(), blockUi.destroy();
            if (data.status == true) {
                $('[name="id"]').val(data.row.id),

                $('#kode_alat_peraga').val(data.row.kode_alat_peraga);
                $('#nama_alat_peraga').val(data.row.nama_alat_peraga);
                $('#jumlah').val(data.row.jumlah);
                $('#kondisi').val(data.row.kondisi);
                $('#keterangan').val(data.row.keterangan);

                //fid satuan
                $('select[name=fid_satuan]').val(data.row.fid_satuan),
                $('.selectpicker').selectpicker('refresh');
                //fid satuan
                $('select[name=fid_lab]').val(data.row.fid_lab),
                $('.selectpicker').selectpicker('refresh');
                //fid satuan
                $('select[name=fid_lokasi]').val(data.row.fid_lokasi),
                $('.selectpicker').selectpicker('refresh');

                // file foto
                _loadDropifyFile(data.url_foto, '#foto');
                //Status
                if (data.row.status == 1) {
                    $('#status').prop('checked', true),
                    $('#iGroup-Status .form-check-label').text('AKTIF');
                } else {
                    $('#status').prop('checked', false),
                    $('#iGroup-Status .form-check-label').text('TIDAK AKTIF');
                }
                $('#iGroup-Status').show();
                $("#card-form .card-header .card-title").html(
                    `<h3 class="fw-bolder fs-2 text-gray-900"><i class="bi bi-pencil-square fs-2 text-gray-900 me-2"></i>Form Edit Data Alat Peraga</h3>`
                ),
                $("#card-data").hide(), $("#card-form").show();
                $('html, body').animate({scrollTop: $("#card-form").offset().top}, 1000); 
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
    var kode_alat_peraga = $('#kode_alat_peraga');
    var nama_alat_peraga = $('#nama_alat_peraga');
    var jumlah = $('#jumlah');
    var fid_satuan = $('#fid_satuan');
    var kondisi = $('#kondisi');
    var fid_lab = $('#fid_lab');
    var fid_lokasi = $('#fid_lokasi');
    var foto = $('#foto');
    var preview_foto = $('#iGroup-foto .dropify-preview .dropify-render').html();
    var keterangan = $('#keterangan');
    
    if (fid_lab.val() == '') {
        toastr.error('Laboratorium masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        fid_lab.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (fid_lokasi.val() == '') {
        toastr.error('Lokasi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        fid_lokasi.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (kode_alat_peraga.val() == '') {
        toastr.error('Kode alat peraga masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        kode_alat_peraga.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (nama_alat_peraga.val() == '') {
        toastr.error('Nama alat peraga masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        nama_alat_peraga.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (jumlah.val() == '') {
        toastr.error('Jumlah alat peraga masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        jumlah.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (fid_satuan.val() == '') {
        toastr.error('Satuan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        fid_satuan.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (kondisi.val() == '') {
        toastr.error('Kondisi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        kondisi.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (preview_foto == '') {
        toastr.error('Foto masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#iGroup-foto .dropify-wrapper').addClass('border-2 border-danger').stop().delay(1500).queue(function () {
            $(this).removeClass('border-2 border-danger');
        });
        foto.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (keterangan.val() == '') {
        toastr.error('Keterangan masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        keterangan.focus();
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
            let target = document.querySelector("#card-form"), blockUi = new KTBlockUI(target, { message: messageBlockUi , zIndex: 9});
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-data")[0]), ajax_url = BASE_URL+ "/app_admin/ajax/alat_peraga_save";
            if(save_method == 'update_data') {
                ajax_url = BASE_URL+ "/app_admin/ajax/alat_peraga_update";
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
                        var message = 'Data alat peraga berhasil diperbarui'
                        if(save_method == 'add_data'){
                            var message = 'Data alat peraga baru berhasil ditambahkan'
                        }
                        Swal.fire({
                            title: "Success!",
                            text: message,
                            icon: "success",
                            allowOutsideClick: false,
                        }).then(function (result) {
                            _closeCard('form_alat_peraga'), _loadDataAlatPeraga();
                        });
                    }else if (data.kode_available == true) {
                        Swal.fire({
                            title: "Ooops!",
                            text: "Gagal memproses data, Kode Alat Peraga yang sama sudah terdata pada sistem ini.",
                            icon: "warning",
                            allowOutsideClick: false,
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
//Update Status Data
const _updateStatus = (idp, value) => {
    let textLbl = 'Nonaktifkan';
    if(value=='1') {
        textLbl = 'Aktifkan';
    }
    let textSwal = textLbl+ ' Data buku sekarang ?';
    if(value=='100') {
        textSwal = 'Yakin ingin memindahkan buku ini ke data sampah ?';
    }
    Swal.fire({
        title: "",
        html: textSwal,
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak, Batalkan!"
    }).then(result => {
        if (result.value) {
            let target = document.querySelector('#card-data'), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            // Load Ajax
            $.ajax({
                url: BASE_URL+ "/app_admin/ajax/data_buku_update_status",
                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                dataType: "JSON",
                data: {
                    idp, value
                }, success: function (data) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Success!", html: data.message, icon: "success", allowOutsideClick: false }).then(function (result) {
                        _loadDataAlatPeraga();
                    });
                }, error: function (jqXHR, textStatus, errorThrown) {
                    blockUi.release(), blockUi.destroy();
                    Swal.fire({ title: "Ooops!", text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.", icon: "error", allowOutsideClick: false }).then(function (result) {
                        console.log("Update data is error!");
                        _loadDataAlatPeraga();
                    });
                }
            });
        }
    });
}
// cetak barcode
const _cetakBarcode = (idp) => {
    Swal.fire({
        title: "",
        html: "Pilih Ukuran Barcode",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Kecil",
        cancelButtonText: "Besar"
    }).then(result => {
        if (result.value) {
            //   ukuran barcode kecil
            window.open( BASE_URL+'/app_admin/alat_peraga_barcode/'+idp+'/1', '_blank');
        }else{
            //   ukuran barcode besar
            window.open( BASE_URL+'/app_admin/alat_peraga_barcode/'+idp+'/2', '_blank');
        }
    });
}