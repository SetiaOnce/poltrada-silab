<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiLab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LokasiLabController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Lokasi Laboratorium';   
        return view('backend.admin.lokasi_lab', $data);
    }
    public function data(Request $request)
    {
        $data = LokasiLab::orderBy('id', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('status', function ($row) {
                if($row->status == 1){
                    $activeCustom = '<button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="tooltip" title="Status Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'0'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                } else {
                    $activeCustom = '<button type="button" class="btn btn-sm btn-light mb-1" data-bs-toggle="tooltip" title="Status Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'1'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                }
                return $activeCustom;
            })
            ->addColumn('action', function($row){
                $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editData('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                return $btnEdit;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required|max:120',
            'alamat' => 'required|max:250',
        ],[
            'nama_lokasi.required' => 'Nama lokasi masih kosong.',
            'nama_lokasi.max' => 'Nama lokasi tidak lebih dari 120 karakter.',
            'alamat.required' => 'Alamat masih kosong.',
            'alamat.max' => 'Alamat tidak lebih dari 250 karakter.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            LokasiLab::create([
                'nama_lokasi' => $request->input('nama_lokasi'),
                'alamat' => $request->input('alamat'),
                'user_add' => session()->get('nama'),
                'created_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }
        return response()->json($output);
    }
    public function edit(Request $request)
    {
        $data_id = $request->input('idp');
        $lokasiLab = LokasiLab::where('id', $data_id)->first();
        return response()->json([
            'status' => TRUE,
            'row' =>$lokasiLab,
        ]);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('id');
        $status = 0;
        if($request->input('status')){$status = 1;}
        
        $errors					= [];
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required|max:120',
            'alamat' => 'required|max:250',
        ],[
            'nama_lokasi.required' => 'Nama lokasi masih kosong.',
            'nama_lokasi.max' => 'Nama lokasi tidak lebih dari 120 karakter.',
            'alamat.required' => 'Alamat masih kosong.',
            'alamat.max' => 'Alamat tidak lebih dari 250 karakter.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            LokasiLab::where('id', $data_id)->update([
                'nama_lokasi' => $request->input('nama_lokasi'),
                'alamat' => $request->input('alamat'),
                'status' => $status,
                'user_updated' => session()->get('nama'),
                'updated_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }
        return response()->json($output);
    }
    public function updateStatus(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('idp');
        $value = $request->input('value');
        $message = 'Lokasi laboratorium berhasil dinonaktifkan';
        if($value == 1){
        $message = 'Lokasi laboratorium berhasil diaktfikan';
        }
        LokasiLab::where('id', $data_id)->update([
            'status' => $value,
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => TRUE,
            'message' => $message
        ]);
    }
}
