<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\JadwalPraktek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InformasiController extends Controller
{
    public function index()
    {
        return view('informasi.index');
    }
    public function systemInformation()
    {
        $countData = JadwalPraktek::orderBy('tanggal', 'ASC')->orderBy('fid_lab')->whereDate('tanggal', '=', date('Y-m-d'))->whereStatus(1)->count();
        $response = array(
            'status' => TRUE,
            'jmlhJadwal' => $countData,
            'video_url' => asset('dist/video-backgroun.mp4'),
        );
        return response()->json($response);
    }
    public function listDataPraktekNow()
    {
        date_default_timezone_set("Asia/Makassar");
        $query = JadwalPraktek::orderBy('tanggal', 'ASC')->orderBy('fid_lab')->whereDate('tanggal', '=', date('Y-m-d'))->whereStatus(1)->limit(10);
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('jam', function ($row) {
                $data = explode('-', $row->jam);
                return '<span class="badge badge-info">'.$data[0].' - '.$data[1].'</span>';
            })
            ->editColumn('judul_praktek', function ($row) {
                return strtoupper($row->judul_praktek);
            })
            ->editColumn('laboratorium', function ($row) {
                return $row->lab->nama_laboratorium;
            })
            ->addColumn('status', function($row){
                $data = explode('-', $row->jam);
                $status = '<span class="badge badge-primary">TERJADWALKAN</span>';
                if(date("H:i") > $data[0] AND date("H:i") <  $data[1]){
                    $status = '<span class="badge badge-success">TERJADWALKAN</span>';
                }if(date("H:i") >  $data[1]){
                    $status = '<span class="badge badge-danger">SELESAI</span>';
                }
                return $status;
            })
            ->rawColumns(['laboratorium', 'jam', 'status', 'judul_praktek'])
            ->make(true);
    }
    public function listDataPraktekSchedule()
    {
        date_default_timezone_set("Asia/Makassar");
        $query = JadwalPraktek::orderBy('tanggal', 'ASC')->orderBy('fid_lab')->whereDate('tanggal', '=', date('Y-m-d'))->whereStatus(1)->limit(10)->get();
        $data = [];
        foreach($query as $row){
            $jam = explode('-', $row->jam);
            if(date("H:i") >= $jam[0] AND date("H:i") <=  $jam[1]){
                $data[] = [
                    'jam' => $row->jam,
                    'laboratorium' => $row->lab->nama_laboratorium,
                ];
            }
        }
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('jam', function ($row) {
                $jam = explode('-', $row['jam']);
                return '<span class="badge badge-info">'.$jam[0].' - '.$jam[1].'</span>';
            })
            ->editColumn('laboratorium', function ($row) {
                return $row['laboratorium'];
            })
            ->rawColumns(['laboratorium', 'jam'])
            ->make(true);
    }
}


