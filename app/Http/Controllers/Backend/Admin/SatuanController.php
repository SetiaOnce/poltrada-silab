<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SatuanController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Satuan';
        return view('backend.admin.satuan', $data);
    }
    public function data(Request $request)
    {
        $data = Satuan::orderBy('id', 'DESC')->get();
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
            'satuan' => 'required|max:120',
        ],[
            'satuan.required' => 'Nama satuan tidak boleh kosong.',
            'satuan.max' => 'Nama satuan tidak lebih dari 120 karakter.',

        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            Satuan::create([
                'satuan' => $request->input('satuan'),
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
        $dataSatuan = Satuan::where('id', $data_id)->first(); 
        return response()->json([
            'status' => TRUE,
            'row' =>$dataSatuan,
        ]);
    }
    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('id');
        $status = 0;
        if($request->input('status')){
            $status = 1;
        }
        $errors					= [];
        $validator = Validator::make($request->all(), [
            'satuan' => 'required|max:120',
        ],[
            'satuan.required' => 'Nama satuan tidak boleh kosong.',
            'satuan.max' => 'Nama satuan tidak lebih dari 120 karakter.',
        ]);

        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            Satuan::where('id', $data_id)->update([
                'satuan' => $request->input('satuan'),
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
        $message = 'Satuan berhasil dinonaktifkan';
        if($value == 1){
        $message = 'Satuan berhasil diaktfikan';
        }
        Satuan::where('id', $data_id)->update([
            'status' => $value,
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => TRUE,
            'message' => $message
        ]);
    }
}
