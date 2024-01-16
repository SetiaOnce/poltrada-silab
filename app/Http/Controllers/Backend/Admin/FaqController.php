<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class FaqController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Frequesntly Asked Questions';   
        return view('backend.admin.faq', $data);
    }
    public function data(Request $request)
    {
        $data = Faq::orderBy('id', 'DESC')->get();
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
                $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteData('."'".$row->id."'".');"><i class="las la-trash-alt fs-3"></i></button>';
                return $btnEdit.$btnDelete;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'judul' => 'required|max:250',
            'isi' => 'required',
        ],[
            'judul.required' => 'Pertanyaan faq tidak boleh kosong.',
            'judul.max' => 'Pertanyaan faq tidak lebih dari 250 karakter.',
            'isi.required' => 'Jawaban tidak boleh kosong.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            Faq::create([
                'judul' => $request->input('judul'),
                'isi' => $request->input('isi'),
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
        $data_faq = Faq::where('id', $data_id)->first();
        return response()->json([
            'status' => TRUE,
            'row' =>$data_faq,
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
            'judul' => 'required|max:250',
            'isi' => 'required',
        ],[
            'judul.required' => 'Pertanyaan faq tidak boleh kosong.',
            'judul.max' => 'Pertanyaan faq tidak lebih dari 250 karakter.',
            'isi.required' => 'Jawaban tidak boleh kosong.',
        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            Faq::where('id', $data_id)->update([
                'judul' => $request->input('judul'),
                'isi' => $request->input('isi'),
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
        $message = 'Faq berhasil dinonaktifkan';
        if($value == 1){
        $message = 'Faq berhasil diaktfikan';
        }
        Faq::where('id', $data_id)->update([
            'status' => $value,
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => TRUE,
            'message' => $message
        ]);
    }
    public function destroy(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('idp');
        Faq::where('id', $data_id)->delete();
        return response()->json(['status' => TRUE]);
    }
}
