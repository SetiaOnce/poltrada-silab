<?php

namespace App\Http\Controllers\Backend\Common;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\BukuTamu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class DataBukuTamuController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Buku Tamu';   
        return view('backend.common.data_buku_tamu', $data);
    }
    public function data(Request $request)
    {
        $query = BukuTamu::orderBy('id', 'DESC');
        if($request->input('tgl_start') AND $request->input('tgl_end')){
            $tgl_start = Carbon::createFromFormat('d/m/Y', $request->input('tgl_start'))->format('Y-m-d');
            $tgl_end = Carbon::createFromFormat('d/m/Y', $request->input('tgl_end'))->format('Y-m-d');

            $query = BukuTamu::whereBetween('tanggal_kunjungan', [$tgl_start, $tgl_end]);
        }
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('tanggal_kunjungan', function ($row) {
                return Shortcut::tanggalLower($row->tanggal_kunjungan);
            })
            ->editColumn('foto', function ($row) {
                $file_image = $row->foto;
                if($file_image==''){
                    $url_file = asset('dist/img/default-placeholder.png');
                } else {
                    if (!file_exists(public_path(). '/dist/img/buku-tamu/'.$file_image)){
                        $url_file = asset('dist/img/default-placeholder.png');
                        $file_image = NULL;
                    }else{
                        $url_file = url('dist/img/buku-tamu/'.$file_image);
                    }
                }
                $fileCustom = '<a class="d-block overlay w-100 image-popup" href="'.$url_file.'" title="'.$file_image.'">
                    <img src="'.$url_file.'" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded w-100" alt="'.$file_image.'" />
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                    </div>    
                </a>';
                return $fileCustom;
            })
            ->rawColumns(['tanggal_kunjungan', 'foto'])
            ->make(true);
    }
    public function detail(Request $request)
    {
        $data = BukuTamu::where('id', $request->input('idp'))->first();
        $output = '
            <!--begin::Row-->
            <div class="row mb-4">
                <!--begin::Label-->
                <label class="col-lg-3 fw-semibold text-muted">Nama Pengunjung</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9">                    
                    <span class="fw-bold fs-6 text-gray-800">'.$data->nama_pengunjung.'</span>                
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-4">
                <!--begin::Label-->
                <label class="col-lg-3 fw-semibold text-muted">No Telepon/Whatsapp</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9">                    
                    <span class="fw-bold fs-6 text-gray-800">'.$data->telp.'</span>                
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-4">
                <!--begin::Label-->
                <label class="col-lg-3 fw-semibold text-muted">Alamat</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9">                    
                    <span class="fw-bold fs-6 text-gray-800">'.$data->alamat.'</span>                
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-4">
                <!--begin::Label-->
                <label class="col-lg-3 fw-semibold text-muted">Keperluan</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9">                    
                    <span class="fw-bold fs-6 text-gray-800">'.$data->keperluan.'</span>                
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-4">
                <!--begin::Label-->
                <label class="col-lg-3 fw-semibold text-muted">Tanggal Masuk</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9">                    
                    <span class="fw-bold fs-6 text-gray-800">'.Shortcut::tanggalLower($data->tanggal_jam_masuk).'</span>                
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        ';
        return response()->json($output);
    }
}
