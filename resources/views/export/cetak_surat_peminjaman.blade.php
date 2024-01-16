<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow" />
    <title>SURAT PEMINJAMAN - {{ $peminjaman->no_peminjaman }}</title>
    <style>
        body { font-family: "Arial Narrow", Arial, sans-serif, sans-serif; font-size:13px; } 
    </style>
    <style>
    .container{
        width: 100%;    
    }
    .img-header{
        width: 1200px; /* Sesuaikan lebar gambar */
        height: auto;
    }
    .header-title1{
        text-align: center;
        font-size: 20px;
        font-family: Georgia, serif;
        margin-top: -20px;
        margin-bottom: -20px;
        text-decoration: underline;
    }
    .header-desc{
        text-align: left;
        font-size: 13px;
        font-family: Georgia, serif;
        margin-bottom: -2px;
    }
    .panel-body{
        border-style: double;
    }
    .panel-body ul{
        text-decoration: none;
    }
    #tableInfo {
        font-family: "Arial Narrow", Arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    #tableInfo td, #tableInfo th {
        border: 1px solid #c8c8c8;
        opacity:1;
        padding: 4px;
        margin: 2px; 
    }
    #tableInfo tr:nth-child(even){background-color: #FFF;}
    #tableInfo tr:hover {background-color: #FFF;}
    #tableInfo th {
        padding-top: 6px;
        padding-bottom: 6px; 
        background: #DDD;
        color: #222;
    } 
    .subtitle-one{
        margin-top: -10px;
    }
    #diteruskan{
        height: 20px;
        align-items: center;
        text-align: center;
        background: #C4D7B2;
    }
    .text-diteruskan{
        font-size: 14px;
    }

    #desc-diteruskan{
        padding-left: 10px;
        align-items: center;
        display: flex;
        margin-top: 10px;
    }
    .card{
        max-width: 100%;
        align-items: center;
        justify-content: space-between;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .card-isi{
        display: flex;
        max-width: 100vh;
        align-items: center;
        justify-content: space-between;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    
    .custom-list {
        list-style-type: none;
        padding: 0;
    }
    .custom-list li {
        margin-bottom: 10px; /* Adjust as needed */
        padding: 10px;
        background-color: #f2f2f2;
        border-radius: 4px;
        font-size: 16px;
        color: #333;
    }
    .col-md-12{
        display: flex;
    }
    header {
        text-align: center;
    }
    header img {
        width: 800px;
    }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('dist/img/kop surat.png') }}" alt="">
    </header>
    <div class="container">
        <div class="header-title1">
            <h5>BERITA ACARA PEMINJAMAN ALAT PERAGA LABORATORIUM</h5>
        </div>
        <div class="header-title1" style="margin-top: -50px;">
            <h6>Nomor Peminjaman : {{ $peminjaman->no_peminjaman }}</h6>
        </div>
        <table  width="100%" style="margin-top: 30px;">
            <tr>
                <td style="width: 25%;  font-size: 10pt; vertical-align: top;">Berikut Identitas Peminjam Alat Peraga : </td>
            </tr>
        </table>
         <table  width="100%" style="margin-top: 15px;" style="padding-left: 5%;">
            <tr>
                <td style="width: 25%;  font-size: 9pt; vertical-align: top;">STATUS PEMINJAM</td>
                <td style="width: 1%;  font-size: 9pt; vertical-align: top;"><b>:</b></td>
                <td style=" font-size: 9pt; vertical-align: top;"><b>{{ $peminjaman->status_peminjaman }}</b></td>
            </tr>
            <tr>
                <td style="width: 25%;  font-size: 9pt; vertical-align: top;">NIK / NOTAR</td>
                <td style="width: 1%;  font-size: 9pt; vertical-align: top;"><b>:</b></td>
                <td style=" font-size: 9pt; vertical-align: top;"><b>{{ $peminjaman->nik_notar }}</b></td>
            </tr>
            <tr>
                <td style="width: 25%;  font-size: 9pt; vertical-align: top;">NAMA LENGKAP</td>
                <td style="width: 1%;  font-size: 9pt; vertical-align: top;"><b>:</b></td>
                <td style=" font-size: 9pt; vertical-align: top;"><b>{{ $peminjaman->nama_peminjam }}</b></td>
            </tr>
            <tr>
                <td style="width: 25%;  font-size: 9pt; vertical-align: top;">TELEPON</td>
                <td style="width: 1%;  font-size: 9pt; vertical-align: top;"><b>:</b></td>
                <td style=" font-size: 9pt; vertical-align: top;"><b>{{ $peminjaman->telepon }}</b></td>
            </tr>
        </table>
        <table  width="100%" style="margin-top: 30px;">
            <tr>
                <td style="width: 25%;  font-size: 10pt; vertical-align: top;">Masa waktu peminjaman alat peraga dari Tanggal <b>{{ date('d-m-Y', strtotime($peminjaman->tgl_peminjaman)) }}</b> sampai dengan <b>{{ date('d-m-Y', strtotime($peminjaman->tgl_pengembalian)) }}</b> bertempat di laboratorium</td>
            </tr>
        </table>
        <table  width="100%" style="margin-top: 30px;">
            <tr>
                <td style="width: 25%;  font-size: 10pt; vertical-align: top;">Telah dilakukan serah terima alat-alat peraga tersebut dibawah ini dengan jumlah ({{ count($dtPeminjaman) }}) alat:</td>
            </tr>
        </table>
        <table id="tableInfo" style="font-size: 10px;">
            <thead >
                <tr>     
                    <th style="width:10%; text-align: center;" >NO</th>
                    <th style="width:50%;" >NAMA ALAT</th>
                    <th style="width:20%; text-align: center;" >KODE ALAT</th>    
                    <th style="width:20%; text-align: center;" >JUMLAH</th>    
                </tr>
            </thead>
            <tbody>
                @foreach ($dtPeminjaman as $no => $item)
                    <tr>
                        <td align="center">{{ $no+1 }}</td>
                        <td >{{ $item->alat->nama_alat_peraga }}</td>
                        <td align="center">{{ $item->alat->kode_alat_peraga }}</td>
                        <td align="center">{{ $item->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table style="margin-top: 20px;">
            <tr>
                <td align="left" valign="left" style="font-size: 13px; text-align:left; padding: 0 10px 10px 10px; font-weight: 500; color: #1f1f20; font-family:Arial,Helvetica,sans-serif">
                    <span>
                        <i>Demikian dapat disampaikan dalam berita acara ini, diharapkan alat-alat yang dipinjam dapat dijaga dengan baik dan dikembalikan sesuai dengan jadwal yang telah disepakati.</i>
                    </span>
                </td>
            </tr>
        </table>
        <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding"  style="margin-top: 20px;">
            <tbody>
                <tr>
                    <td style="display: flex;align-items: center;">
                        {{-- <table width="300" border="0" cellpadding="0" cellspacing="0" align="left" class="col">
                            <tbody>
                                <tr>
                                    <td style="text-align: center; font-size: 12px; color: #1d1c1c; font-family: 'Open Sans', sans-serif; line-height: 18px; vertical-align: top;padding-bottom: 2%;">
                                        <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($peminjaman->no_peminjaman,'QRCODE') }}" height="100" width="100" /><br />
                                    </td>
                                </tr>
                            </tbody>
                        </table> --}}
                        <table width="300" border="0" cellpadding="0" cellspacing="0" align="right" class="col">
                            <tbody>
                                <tr>
                                    <td style="text-align: center; font-size: 12px; color: #1d1c1c; font-family: 'Open Sans', sans-serif; line-height: 18px; vertical-align: top;padding-bottom: 2%;">
                                        <h3  style="font-size: 14px;">
                                            <span>Tabanan, {{ date('d-m-Y') }}</span><br>
                                            <span>Yang menyerahkan, </span>
                                        </h3>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                        <h3  style="font-size: 14px;text-decoration: underline;">
                                            <span>(Pengelola Laboratorium)</span>
                                        </h3>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>