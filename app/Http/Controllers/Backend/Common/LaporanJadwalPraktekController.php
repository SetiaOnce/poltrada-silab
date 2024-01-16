<?php

namespace App\Http\Controllers\Backend\Common;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\HeaderPinjaman;
use App\Models\JadwalPraktek;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class LaporanJadwalPraktekController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Laporan Jadwal Praktek';   
        return view('backend.common.laporan_jadwal_praktek', $data);
    }
    public function data(Request $request)
    {
        $query = JadwalPraktek::orderBy('tanggal', 'DESC')->orderBy('fid_lab');
        if($request->input('tgl_start') AND $request->input('tgl_end')){
            $tgl_start = Carbon::createFromFormat('d/m/Y', $request->input('tgl_start'))->format('Y-m-d');
            $tgl_end = Carbon::createFromFormat('d/m/Y', $request->input('tgl_end'))->format('Y-m-d');
            $query = $query->whereBetween('tanggal', [$tgl_start, $tgl_end]);
        }if($request->filter_laboratorium){
            $query->whereFidLab($request->filter_laboratorium);
        }if($request->filter_laboratorium || $request->input('tgl_start') AND $request->input('tgl_end')){
            $tgl_start = Carbon::createFromFormat('d/m/Y', $request->input('tgl_start'))->format('Y-m-d');
            $tgl_end = Carbon::createFromFormat('d/m/Y', $request->input('tgl_end'))->format('Y-m-d');
            $query = $query->whereBetween('tanggal', [$tgl_start, $tgl_end])->whereFidLab($request->filter_laboratorium);
        }
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('waktu', function ($row) {
                return date('d-m-Y', strtotime($row->tanggal)).' '.$row->jam;
            })
            ->editColumn('laboratorium', function ($row) {
                return $row->lab->nama_laboratorium;
            })
            ->addColumn('status', function($row){
                if($row->status == 0){
                    $status = '<span class="badge badge-info">DALAM PROSES</span>';
                }else if($row->status == 1){
                    $status = '<span class="badge badge-success">DISETUJUI</span>';
                }else{
                    $status = '<span class="badge badge-danger">DITOLAK</span>';
                }
                return $status;
            })
            ->rawColumns(['laboratorium', 'waktu', 'status'])
            ->make(true);
    }
}
