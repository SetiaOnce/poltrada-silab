// var gRecaptchaPublicComment;
// var CaptchaCallback = function() {
//     if (typeof grecaptcha === "undefined") {
//         grecaptcha = {};
//     }
//     grecaptcha.ready = function (cb) {
//         if (typeof grecaptcha === "undefined") {
//             const c = "___grecaptcha_cfg";
//             window[c] = window[c] || {};
//             (window[c]["fns"] = window[c]["fns"] || []).push(cb);
//         } else {
//             cb();
//         }
//     };
//     grecaptcha.ready(function () {
//         gRecaptchaPublicComment = grecaptcha.render('captchaPeminjaman', {
//             'sitekey' : '6LcQ5kAnAAAAAOv4Yc3UsMozgaewcPVjPrEPg9SC',
//             'theme' : 'light'
//         });
//     });
// };
//Class Definition
var alatarray=[];var fid_header_pinjaman;
//Message BlockUi
const messageBlockUi = '<div class="blockui-message bg-light text-dark"><span class="spinner-border text-primary"></span> Mohon Tunggu ...</div>';
//Class Initialization
jQuery(document).ready(function() {
    // selectpicker
    loadSelectpicker_statuspeminjaman(),loadSelectpicker_laboratorium();
    //FLATPICKER OPTIONS
    var tglPinjam = $('#tgl_peminjaman').val(), tglKembali = $('#tgl_pengembalian').val();
    $("#tgl_peminjaman").flatpickr({ defaultDate: tglPinjam,  dateFormat: "d/m/Y", minDate: "today" });
    $("#tgl_pengembalian").flatpickr({defaultDate: tglKembali, dateFormat: "d/m/Y", minDate: "today" });
    // mask input
    $('.inputmax20').mask('00000000000000000000'), $('.inputmax10').mask('0000000000'), $('#tgl_peminjaman').inputmask('99/99/9999', { placeholder: 'dd/mm/yyyy' }), $('#tgl_pengembalian').inputmask('99/99/9999', { placeholder: 'dd/mm/yyyy' });
    // on change status peminjaman
    $('#status_peminjaman').change(function(){
        var status = this.value;
        if(status == 'TARUNA'){
            $('#hideForm1').hide();
            $('.form-input-disabled').prop('readonly', true);
            var nik_notar = $('[name="nik_notar"]').val();
            if(nik_notar != ''){
                _checkDataTarunaDosen(nik_notar, status)
            }
        }else if(status == 'DOSEN'){
            $('#hideForm1').hide();
            $('.form-input-disabled').prop('readonly', true);
            var nik_notar = $('[name="nik_notar"]').val();
            if(nik_notar != ''){
                _checkDataTarunaDosen(nik_notar, status)
            }
        }else{
            $('#hideForm1').show();
            $('.form-input-disabled').prop('readonly', false);
            $('#notFoundData').html(``), $('#notFoundData').hide();
        }
    });
    // on write form nik or nim 
    $('[name="nik_notar"]').on('input', function() {
        var status =  $('#status_peminjaman').val();
        var nik_notar = this.value;
        if(status == 'TARUNA' || status == 'DOSEN'){
            _checkDataTarunaDosen(nik_notar, status)
        }
    });
});
// handle check data taruna and dosen
const _checkDataTarunaDosen = (nik_notar, status) => {
    $.ajax({
        url: BASE_URL+ '/ajax/check_data_taruna_dosen',
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'JSON',
        data: {
            nik_notar, status,
        },
        success: function (data) {
            if(data.status==true){
                $('#hideForm1').show();
                $('#notFoundData').html(``).hide();
                $(".form-input-disabled").val('');
                $('[name="nama_peminjam"]').val(data.nama_peminjam);
                $('[name="telepon"]').val(data.telepon);
            }else{
                $('#hideForm1').hide();
                $('#notFoundData').html(`
                    <div class="alert alert-light d-flex align-items-center p-5">
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-danger"><i>Data tidak ditemukan...</i></h4>
                        </div>
                    </div>
                `).show();
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log("load data is error!");
        },
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
    Custom Select Status Peminjaman
*************************/
function loadSelectpicker_statuspeminjaman() {
    $.ajax({
        url: BASE_URL+ "/select/ajax_getstatuspeminjaman",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            var output = '';
            var i;
            for (i = 0; i < data.length; i++) {
                output += '<option value="' + data[i].id + '">' + data[i].status + '</option>';
            }
            $('#status_peminjaman').html(output).selectpicker('refresh').selectpicker('val', '');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Load data is error');
        }
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
    var fid_lab = $('#fid_lab');
    var status_peminjaman = $('#status_peminjaman');
    var nik_notar = $('#nik_notar');
    var nama_peminjam = $('#nama_peminjam');
    var telepon = $('#telepon');
    var prodi_instansi = $('#prodi_instansi');
    var tgl_peminjaman = $('#tgl_peminjaman');
    var tgl_pengembalian = $('#tgl_pengembalian');
    
    if (fid_lab.val() == '') {
        toastr.error('Laboratorium masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#fg-fidLab button').removeClass('btn-secondary text-dark').addClass('btn-danger text-light').stop().delay(1500).queue(function () {
			$(this).removeClass('btn-danger text-light').addClass('btn-secondary text-dark');
		});
        fid_lab.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (status_peminjaman.val() == '') {
        toastr.error('Status peminjam masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        $('#fg-statusPeminjam button').removeClass('btn-secondary text-dark').addClass('btn-danger text-light').stop().delay(1500).queue(function () {
			$(this).removeClass('btn-danger text-light').addClass('btn-secondary text-dark');
		});
        status_peminjaman.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }
    if (nik_notar.val() == '') {
        toastr.error('NIK/Notar masih kosong pastikan untuk memasukkan nik atau notar yang terdata pada sistem...', 'Uuppss!', {"progressBar": true, "timeOut": 2500});
        nik_notar.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }
    if (status_peminjaman.val() == 'DOSEN' || status_peminjaman.val() == 'TARUNA') {        
        if (nama_peminjam.val() == '') {
            toastr.error('Pastikan untuk memasukkan nik atau notar yang terdata pada sistem...', 'Uuppss!', {"progressBar": true, "timeOut": 2500});
            nama_peminjam.focus();
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }
    }else{
        if (nama_peminjam.val() == '') {
            toastr.error('Nama peminjam masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
            nama_peminjam.focus();
            $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            return false;
        }
    }
    if (telepon.val() == '') {
        toastr.error('Telepon masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        telepon.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (prodi_instansi.val() == '') {
        toastr.error('Prodi atau instansi masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        prodi_instansi.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (tgl_peminjaman.val() == '') {
        toastr.error('Tanggal peminjaman masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        tgl_peminjaman.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }if (tgl_pengembalian.val() == '') {
        toastr.error('Tanggal pengembalian masih kosong...', 'Uuppss!', {"progressBar": true, "timeOut": 1500});
        tgl_pengembalian.focus();
        $('#btn-save').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
        return false;
    }

    Swal.fire({
        title: "",
        text: "Simpan biodata diri ?",
        icon: "question",
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.value) {
            let target = document.querySelector("#card-step01"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
            blockUi.block(), blockUi.destroy();
            let formData = new FormData($("#form-peminjaman")[0]), ajax_url = BASE_URL+ "/ajax/peminjaman_biodata_save";
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
                        _kelolaAlatPinjaman(data.idp_peminjaman, data.fid_lab), _stepperCheck(2, data.idp_peminjaman);
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
// Stepper check
const _stepperCheck = (stepper, idp_peminjaman, det_peminjaman) => {
    if(stepper == 1){
        $('#Kt_Stepper .stepper1 .stepper-bg').addClass('bg-primary').removeClass('bg-light-primary');
        $('#Kt_Stepper .stepper1 .stepper-icon').addClass('d-none').removeClass('text-primary');
        $('#Kt_Stepper .stepper1 .stepper-number').removeClass('d-none text-primary').addClass('text-white');
        
        $('#Kt_Stepper .stepper2 .stepper-bg').removeClass('bg-primary').addClass('bg-light-primary');
        $('#Kt_Stepper .stepper2 .stepper-icon').addClass('d-none');
        $('#Kt_Stepper .stepper2 .stepper-number').removeClass('d-none').addClass('text-primary');
        // hide show card form
        $('#card-step01').show(),$('#card-step02').hide(),$('#card-step03').hide();
        // set value when back to biodata
        $('[name="idp"]').val(fid_header_pinjaman)
    }else if(stepper == 2){
        $('#Kt_Stepper .stepper1 .stepper-bg').removeClass('bg-primary').addClass('bg-light-primary');
        $('#Kt_Stepper .stepper1 .stepper-icon').removeClass('d-none text-white').addClass('text-primary');
        $('#Kt_Stepper .stepper1 .stepper-number').addClass('d-none');
    
        $('#Kt_Stepper .stepper2 .stepper-bg').addClass('bg-primary').removeClass('bg-light-primary');
        $('#Kt_Stepper .stepper2 .stepper-icon').addClass('d-none').removeClass('text-primary');
        $('#Kt_Stepper .stepper2 .stepper-number').removeClass('d-none text-primary').addClass('text-white');
        // hide show card form
        $('#card-step02').show(), $('#card-step01').hide(),$('#card-step03').hide();
    }else{
        $('#Kt_Stepper .stepper1 .stepper-bg').removeClass('bg-primary').addClass('bg-light-primary');
        $('#Kt_Stepper .stepper1 .stepper-icon').removeClass('d-none text-white').addClass('text-primary');
        $('#Kt_Stepper .stepper1 .stepper-number').addClass('d-none');

        $('#Kt_Stepper .stepper2 .stepper-bg').removeClass('bg-primary').addClass('bg-light-primary');
        $('#Kt_Stepper .stepper2 .stepper-icon').removeClass('d-none text-white').addClass('text-primary');
        $('#Kt_Stepper .stepper2 .stepper-number').addClass('d-none');
        // hide show card form
        $('#modalApproveAlat').modal('hide');
        $('#card-step03').show(), $('#card-step02').hide(), $('#card-step01').hide();

        // detail peminjaman element
        let peminjaman = det_peminjaman.peminjaman;
        let dt_alat = det_peminjaman.dt_alat;
        var elementDivContent = '';
        elementDivContent = `
            <h3 class="text-gray-900 fw-bolder mb-3">
                <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Detail peminjaman
            </h3>
            <div class="row">
            <table class="table table-rounded table-row-bordered border">
                <tbody>
                    <tr>
                        <td style="width: 50px">Laboratorium</td>
                        <td style="width: 200px">`+peminjaman.nama_laboratorium+`</td>
                    </tr>
                    <tr>
                        <td style="width: 50px">Status Peminjam</td>
                        <td style="width: 200px">`+peminjaman.status_peminjaman+`</td>
                    </tr>
                    <tr>
                        <td style="width: 50px">Nama Peminjam</td>
                        <td style="width: 200px">`+peminjaman.nama_peminjam+`</td>
                    </tr>
                    <tr>
                        <td style="width: 50px">Nik/Notar</td>
                        <td style="width: 200px">`+peminjaman.nik_notar+`</td>
                    </tr>
                    <tr>
                        <td style="width: 50px">Nomor Peminjaman</td>
                        <td style="width: 200px">`+peminjaman.no_peminjaman+`</td>
                    </tr>
                    <tr>
                        <td style="width: 50px">Tanggal Peminjaman</td>
                        <td style="width: 200px">`+peminjaman.tgl_peminjaman+`</td>
                    </tr>
                    <tr>
                        <td style="width: 50px">Tanggal Pengembalian</td>
                        <td style="width: 200px">`+peminjaman.tgl_pengembalian+`</td>
                    </tr>
                </tbody>
            </table>
        </div>
        `;
        elementDivContent += `
            <h3 class="text-gray-900 fw-bolder mb-3">
                <i class="bi bi-list-ul fs-3 align-middle text-dark me-2"></i>Alat yang dipinjam
            </h3>
        `;
        elementDivContent += `
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-rounded  table-hover align-middle table-row-bordered border" id="datatableAlat">
                        <thead>
                            <tr class="fw-bolder text-uppercase bg-light fs-8">
                                <th class="border-bottom-2 border-gray-200 align-middle px-2">NAMA ALAT</th>
                                <th class="border-bottom-2 text-center border-gray-200 align-middle px-2">KODE ALAT</th>
                                <th class="border-bottom-2 text-center border-gray-200 align-middle px-2">JUMLAH</th>
                            </tr>
                        </thead>
                        <tbody class="fs-8">
        `;
        $.each(dt_alat, function(key, row) {
            elementDivContent += `
                <tr>
                    <td class="p-3" width="50">`+row.nama_alat+`</td>
                    <td width="30" align="center">`+row.kode_alat+`</td>
                    <td width="20" align="center">`+row.jumlah+`</td>
                </tr>
            `;
        });
        elementDivContent += `
                    </tbody>
                    </table>
                </div>
            </div>
        `;
        $('#detailPeminjaman').html(elementDivContent);
    }
}

// ===>> THIS BELLOW FOR HANDLE PEMINJAMAN ALAT <<===//
// Class definition
var KTListPeminjamanAlat = function () {
    // Private functions
    var initDatatable = function (idp_pinjaman, idp_lab) {
        $('#dt_peminjaman_alat').html('');
        $.ajax({
            url: BASE_URL+ "/ajax/load_alat_pinjaman",
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'POST',
            dataType: 'JSON',
            data: {
                idp_pinjaman,idp_lab
            },
            success: function (data) {
                $('#dt_peminjaman_alat').html(data);
            }, complete: function(){
                initCheckboxes();
                $('#datatableAlat').DataTable({
                    destroy: true,
                    columnDefs: [
                        { "width": "40%", "targets": 0, "className": "align-top p-4" },
                        { "width": "40%", "targets": 1, "className": "align-top text-center" },
                        { "width": "20%", "targets": 2, "className": "align-top text-center" },
                    ],
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
                        $("#datatableAlat_length select").selectpicker(),
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
    // Init toggle toolbar
    var initCheckboxes = function () {
        // Select all checkboxes
        const container = document.querySelector('#datatableAlat');
        const checkboxes = container.querySelectorAll('[type="checkbox"]');
        // Toggle delete selected toolbar
        checkboxes.forEach(checkbox => {
            // Checkbox on click event
            checkbox.addEventListener('change', function () {
                var valueCheckbox = parseInt(checkbox.value);
                if (this.checked) {
                    // push to array category
                    alatarray.push(valueCheckbox);
                }else{
                    // push delete from array caregory
                    const deleteKaetegory = alatarray.indexOf(valueCheckbox); 
                    if (deleteKaetegory !== -1) {
                        alatarray.splice(deleteKaetegory, valueCheckbox);
                    }
                }
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });
    }
    // Toggle toolbars
    var toggleToolbars = function () {
        // Define variables
        const container = document.querySelector('#datatableAlat');
        const toolbarBase = document.querySelector('[data-kt-docs-table-toolbar="base"]');
        const toolbarSelected = document.querySelector('[data-kt-docs-table-toolbar="selected"]');
        const selectedCount = document.querySelector('[data-kt-docs-table-select="selected_count"]');

        // Select refreshed checkbox DOM elements
        const allCheckboxes = container.querySelectorAll('tbody [type="checkbox"]');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
        if (checkedState) {
            // selectedCount.innerHTML = count;
            // toolbarBase.classList.add('d-none');
            // toolbarSelected.classList.remove('d-none');
        } else {
            // toolbarBase.classList.remove('d-none');
            // toolbarSelected.classList.add('d-none');
        }
    }
    // Public methods
    return {
        init: function (idp_pinjaman, idp_lab) {
            fid_header_pinjaman = idp_pinjaman;
            initDatatable(idp_pinjaman, idp_lab);
        }
    }
}();
function _kelolaAlatPinjaman(idp_pinjaman, idp_lab){
    KTListPeminjamanAlat.init(idp_pinjaman, idp_lab);
}
// approve checked checkbox
$('#btn-approve-alat').click(function(){
    if (alatarray.length < 1) {
        Swal.fire({title: "Ooops!",html: 'Alat masih kosong, checklist alat yang ingin dipinjam!',icon: "warning",allowOutsideClick: false,});
    }else{
        let target = document.querySelector("#card-step02"), blockUi = new KTBlockUI(target, { message: messageBlockUi });
        blockUi.block(), blockUi.destroy();
        $.ajax({
            url: BASE_URL+ '/ajax/check_alat_approve',
            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
            type: 'POST',
            dataType: 'JSON',
            data: {
                alatarray, fid_header_pinjaman
            },
            success: function (data) {
                blockUi.release(), blockUi.destroy();
                $('[name="array_alat_pinjaman"]').val(alatarray);
                $('[name="fid_peminjaman"]').val(fid_header_pinjaman);
                
                $('#dt-approveAlat').html(data.outputAlatPinjaman);
                $(".inputmax6").inputmask({
                    regex: "^[1-9][0-9]*$",
                    placeholder: "",
                    showMaskOnFocus: false,
                    showMaskOnHover: false
                });
                $('#modalApproveAlat .modal-title').html('<h3 class="fw-bolder fs-3 text-gray-900"><i class="bi bi-cart-plus fs-2 text-gray-900 me-2"></i> Tentukan jumlah alat praktek yang ingin dipinjam (<span class="text-info">'+ data.jmlhAlat +'</span>)</h3>');
                $('#modalApproveAlat').modal('show');
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
});
// save alat pinjaman
$('#btn-save-alat').click(function(){
    $("#btn-save-alat").html('<span class="spinner-border spinner-border-sm align-middle me-3"></span> Mohon Tunggu...').attr("disabled", true);
    let formData = new FormData($("#form-savealat")[0]), ajax_url = BASE_URL+ "/ajax/alat_pinjaman_save";
    $.ajax({
        url: ajax_url,
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (data) {
            $('#btn-save-alat').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            if (data.status == true) {
                _stepperCheck(3, 0, data.row);
            } else {
                if(data.pesan_code=='format_inputan') {   
                    Swal.fire({title: "Ooops!",html: data.pesan_error[0],icon: "warning",allowOutsideClick: false,});
                } else {
                    Swal.fire({title: "Ooops!",html: data.pesan_error,icon: "warning",allowOutsideClick: false,});
                }
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            $('#btn-save-alat').html('<i class="far fa-save"></i> Simpan').attr('disabled', false);
            Swal.fire({title: "Ooops!",text: "Terjadi kesalahan yang tidak diketahui, Periksa koneksi jaringan internet lalu coba kembali. Mohon hubungi pengembang jika masih mengalami masalah yang sama.",icon: "error",allowOutsideClick: false,});
        }
    });
});
// print
function printContent() {
    window.print();
}
