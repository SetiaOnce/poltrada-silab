<!DOCTYPE html>
@php
    $profiles = App\Models\ProfileApp::where('id', 1)->first();
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow" />
    <title>Barcode - {{ $profiles->nama_alias }}</title>
    <!-- Meta -->
    <meta name="description" content="{{ $profiles->deskripsi }}" />
    <meta name="keywords" content="{{ $profiles->keyword }}" />
    <meta name="author" content="@Yogasetiaonce" />
    <meta name="email" content="gedeyoga1126@gmail.com" />
    <meta name="website" content="{{ url('/') }}" />
    <meta name="Version" content="1"/>
    <meta name="docsearch:language" content="id">
    <meta name="docsearch:version" content="1">
    <link rel="canonical" href="{{ url('/') }}">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
    <link rel="manifest" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
    <link rel="mask-icon" href="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}" color="#6CC4A1">
    <meta name="msapplication-TileColor" content="#b91d47">
    <meta name="theme-color" content="#6CC4A1">
    <meta name="application-name" content="{{ $profiles->nama_alias }}">
    <meta name="msapplication-TileImage" content="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="HandheldFriendly" content="true"/>
    <!-- Twitter -->
    <meta name="twitter:widgets:csp" content="on">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:site" content="{{ $profiles->nama_alias }}">
    <meta name="twitter:creator" content="@Yogasetiaonce">
    <meta name="twitter:title" content="{{ $profiles->nama_alias }}">
    <meta name="twitter:description" content="{{ $profiles->deskripsi }}">
    <meta name="twitter:image" content="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
    <!-- Facebook -->
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $profiles->nama_alias }}">
    <meta property="og:description" content="{{ $profiles->deskripsi }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('/dist/img/logo/'.$profiles->backend_logo_icon) }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1000">
    <meta property="og:image:height" content="500">
    <style>
    .wrapper {
        display: grid;
        grid-template-columns: 300px 300px 300px;
        grid-gap: 10px;
        background-color: #fff;
        color: #444;
    }
    @media print {
        @page {
            size: landscape;
        }
    }
    </style>
</head>
<body>
    @if ($ukuran == 1)  
        <table style="margin-left: 10px;" width="300" height="113" cellpadding="2" cellspacing="0" border="1" style="border-color:#4b494971; border: dashed;">
            <tr>
                <td width="30%">
                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:10px">
                        <tr>
                            <td align="center">
                                <div style="border: 2px solid rgb(0, 195, 255);">
                                    {!! QrCode::size(100)->generate( url('view/'.$alat->kode_alat_peraga) ) !!}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="70%">
                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:10px">
                        <tr>
                            <td align="center">
                                <img src="{{ asset('dist/img/logo/'.$profileApp->backend_logo_icon) }}" width="45px" alt="">
                            </td>
                        </tr>
                        <tr><td align="center" style="font-size: 13px"><strong>POLTRADA BALI</strong><br/>KODE ALAT PERAGA <br><i>{{ $alat->kode_alat_peraga }}</i> <br></td></tr>
                    </table>
                </td>
            </tr>
        </table>
    @else
        <table style="margin-left: 10px;" width="200" height="113" cellpadding="2" cellspacing="0" border="1" style="border-color:#4b494971; border: dashed;">
            <tr>
                <td width="80%">
                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:10px">
                        <tr>
                            <td align="center">
                                <div style="border: 2px solid rgb(0, 195, 255);">
                                    {!! QrCode::size(200)->generate( url('view/'.$alat->kode_alat_peraga) ) !!}
                                </div>
                            </td> 
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-size:10px">
                        <tr>
                            <td align="center">
                                <img src="{{ asset('dist/img/logo/'.$profileApp->backend_logo_icon) }}" width="45px" alt="">
                            </td>
                        </tr>
                        <tr><td align="center" style="font-size: 15px"><strong>POLTRADA BALI</strong><br/>KODE ALAT PERAGA <br><i>{{ $alat->kode_alat_peraga }}</i> <br></td></tr>
                    </table>
                </td>
            </tr>
        </table>
    @endif
</body>
</html>