<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\NamaLaboratorium;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Intervention\Image\Facades\Image;

class NamaLabController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Nama Laboratorium';   
        return view('backend.admin.nama_laboratorium', $data);
    }
    public function data(Request $request)
    {
        $data = NamaLaboratorium::orderBy('id', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('prodi', function ($row) {
                return $row->prodi->nama_prodi;
            })
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
            ->rawColumns(['prodi', 'status', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'fid_prodi' => 'required',
            'nama_laboratorium' => 'required|max:120',
            'alamat' => 'required|max:250',
            'icon' => 'required|mimes:png,jpg,jpeg|max:2048',
        ],[
            'fid_prodi.required' => 'Program studi masih kosong.',
            'nama_laboratorium.required' => 'Nama laboratorium masih kosong.',
            'nama_laboratorium.max' => 'Nama laboratorium tidak lebih dari 120 karakter.',
            'alamat.required' => 'Alamat masih kosong.',
            'alamat.max' => 'Alamat tidak lebih dari 250 karakter.',
            'icon.max' => 'Icon tidak lebih dari 2MB.',
            'icon.mimes' => 'Icon berekstensi jpg jepg png.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            // save image to directory
            $mainImage = $request->file('icon');
            $ImageName = strtolower(trim(preg_replace('/[^a-zA-Z]/', '', $request->input('nama_laboratorium')))).'-'.strtolower(md5(Shortcut::random_strings(15))). '.' . $mainImage->extension();
            Image::make($mainImage)->save(public_path('dist/img/icons/'.$ImageName));

            NamaLaboratorium::create([
                'fid_prodi' => $request->input('fid_prodi'),
                'nama_laboratorium' => $request->input('nama_laboratorium'),
                'alamat' => $request->input('alamat'),
                'icon' => $ImageName,
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
        $namaLab = NamaLaboratorium::where('id', $data_id)->first();
        $urlIcon = asset('dist/img/icons/'.$namaLab->icon); 
        return response()->json([
            'status' => TRUE,
            'row' =>$namaLab,
            'url_icon' =>$urlIcon,
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
            'fid_prodi' => 'required',
            'nama_laboratorium' => 'required|max:120',
            'alamat' => 'required|max:250',
        ],[
            'fid_prodi.required' => 'Program studi masih kosong.',
            'nama_laboratorium.required' => 'Nama laboratorium masih kosong.',
            'nama_laboratorium.max' => 'Nama laboratorium tidak lebih dari 120 karakter.',
            'alamat.required' => 'Alamat masih kosong.',
            'alamat.max' => 'Alamat tidak lebih dari 250 karakter.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            if($request->file('icon')){
                $validator = Validator::make($request->all(), [
                    'icon' => 'required|mimes:png,jpg,jpeg|max:2048',
                ],[
                    'icon.max' => 'Icon tidak lebih dari 2MB.',
                    'icon.mimes' => 'Icon berekstensi jpg jepg png.',
                ]);
                if($validator->fails()){
                    foreach ($validator->errors()->getMessages() as $item) {
                        $errors[] = $item;
                    }
                    $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
                    return response()->json($output);
                } else {
                    // save image to directory
                    $mainImage = $request->file('icon');
                    $ImageName = strtolower(trim(preg_replace('/[^a-zA-Z]/', '', $request->input('nama_laboratorium')))).'-'.strtolower(md5(Shortcut::random_strings(15))). '.' . $mainImage->extension();
                    Image::make($mainImage)->save(public_path('dist/img/icons/'.$ImageName));
                    NamaLaboratorium::where('id', $data_id)->update([
                        'icon' => $ImageName,
                    ]);
                }   
            }
            NamaLaboratorium::where('id', $data_id)->update([
                'fid_prodi' => $request->input('fid_prodi'),
                'nama_laboratorium' => $request->input('nama_laboratorium'),
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
        $message = 'Nama laboratorium berhasil dinonaktifkan';
        if($value == 1){
        $message = 'Nama laboratorium berhasil diaktfikan';
        }
        NamaLaboratorium::where('id', $data_id)->update([
            'status' => $value,
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => TRUE,
            'message' => $message
        ]);
    }
}
