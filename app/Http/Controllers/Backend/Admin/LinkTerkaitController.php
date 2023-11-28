<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\LinkTerkait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class LinkTerkaitController extends Controller
{
    public function index()
    {
        if(!session()->get('login_akses')) { 
            return redirect('/login'); 
        }
        $data['header_title'] = 'Link Terkait';
        return view('backend.admin.link_terkait', $data);
    }

    public function data(Request $request)
    {
        $data = LinkTerkait::orderBy('id', 'DESC')->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('name', function ($row) {
                return '<strong>'. $row->name .'</strong>';
            })
            ->editColumn('thumnail', function ($row) {
                $file_name = $row->thumnail;
                if($file_name==''){
                    $url_file = asset('dist/img/default-placeholder.png');
                } else {
                    if (!file_exists(public_path(). '/dist/img/link-terkait/'.$file_name)){
                        $url_file = asset('dist/img/default-placeholder.png');
                        $file_name = NULL;
                    }else{
                        $url_file = url('dist/img/link-terkait/'.$file_name);
                    }
                }
                $fileCustom = '<a class="d-block overlay image-popup" href="'.$url_file.'" title="'.$file_name.'">
                    <img src="'.$url_file.'" class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover rounded" width="50%" alt="'.$file_name.'" />
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                        <span class="badge badge-dark"><i class="las la-search fs-3 text-light"></i></span>
                    </div>    
                </a>';
                return $fileCustom;
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
                $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteData('."'".$row->id."'".');"><i class="las la-trash-alt fs-3"></i></button>';
                return $btnEdit.$btnDelete;
            })
            ->rawColumns(['name', 'thumnail', 'status', 'action'])
            ->make(true);
    }
    
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'thumnail' => 'mimes:png,jpg,jpeg|max:2048',
            'link_url' => 'required|max:500',
        ],[
            'name.required' => 'Nama link terkait tidak boleh kosong.',
            'name.max' => 'Nama link terkait tidak lebih dari 250 karakter.',
            'link_url.required' => 'Link url tidak boleh kosong.',
            'link_url.max' => 'Link url tidak lebih dari 250 karakter.',

            'thumnail.required' => 'Thumnail tidak boleh kosong.',
            'thumnail.max' => 'Thumnail tidak lebih dari 2MB.',
            'thumnail.mimes' => 'Thumnail berekstensi jpg jepg png.',

        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $mainImage = $request->file('thumnail');
            $filename = time() . '-links.' . $mainImage->extension();
            Image::make($mainImage)->resize(358,79)->save(public_path('dist/img/link-terkait/'.$filename));
            
            LinkTerkait::create([
                'name' => $request->input('name'),
                'thumnail' => $filename,
                'link_url' => $request->input('link_url'),
                'created_at' => Carbon::now()
            ]);
            $output = array("status" => TRUE);
        }
        return response()->json($output);
    }
    
    public function edit(Request $request)
    {
        $data_id = $request->input('idp');
        $data_link = LinkTerkait::where('id', $data_id)->first();
        $banner_image = asset('dist/img/link-terkait/'.$data_link->thumnail); 
        return response()->json([
            'status' => TRUE,
            'row' =>$data_link,
            'thumnail' =>$banner_image,
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

        if($request->file('thumnail')){
            $validator = Validator::make($request->all(), [
                'thumnail' => 'mimes:png,jpg,jpeg|max:2048',
            ],[
                'thumnail.required' => 'Thumnail tidak boleh kosong.',
                'thumnail.max' => 'Thumnail tidak lebih dari 2MB.',
                'thumnail.mimes' => 'Thumnail berekstensi jpg jepg png.',
            ]);

            if($validator->fails()){
                foreach ($validator->errors()->getMessages() as $item) {
                    $errors[] = $item;
                }
                $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
            } else {
                $mainImage = $request->file('thumnail');
                $filename = time() . '-links.' . $mainImage->extension();
                Image::make($mainImage)->resize(358,79)->save(public_path('dist/img/link-terkait/'.$filename));
                LinkTerkait::where('id', $data_id)->update([
                    'thumnail' => $filename
                ]);
            }
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'link_url' => 'required|max:500',
        ],[
            'name.required' => 'Nama link terkait tidak boleh kosong.',
            'name.max' => 'Nama link terkait tidak lebih dari 250 karakter.',
            'link_url.required' => 'Link url tidak boleh kosong.',
            'link_url.max' => 'Link url tidak lebih dari 250 karakter.',
        ]);

        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            LinkTerkait::where('id', $data_id)->update([
                'name' => $request->input('name'),
                'link_url' => $request->input('link_url'),
                'status' => $status,
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

        LinkTerkait::where('id', $data_id)->update([
            'status' => $value,
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'status' => TRUE
        ]);
    }
    
    public function destroy(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('idp');
        LinkTerkait::where('id', $data_id)->delete();
        return response()->json(['status' => TRUE]);
    }
}
