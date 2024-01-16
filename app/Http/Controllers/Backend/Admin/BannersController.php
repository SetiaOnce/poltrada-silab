<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\Banners;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class BannersController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Banners';   
        return view('backend.admin.banners', $data);
    }
    public function data(Request $request)
    {
        $data = Banners::latest()->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('file_image', function ($row) {
                $file_image = $row->file_image;
                if($file_image==''){
                    $url_file = asset('dist/img/default-placeholder.png');
                } else {
                    if (!file_exists(public_path(). '/dist/img/banner/'.$file_image)){
                        $url_file = asset('dist/img/default-placeholder.png');
                        $file_image = NULL;
                    }else{
                        $url_file = url('dist/img/banner/'.$file_image);
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
            ->editColumn('status', function ($row) {
                if($row->status == 1){
                    $activeCustom = '<button type="button" class="btn btn-sm btn-info mb-1" data-bs-toggle="tooltip" title="Status Aktif, Nonaktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'0'".');"><i class="fas fa-toggle-on fs-2"></i></button>';
                } else {
                    $activeCustom = '<button type="button" class="btn btn-sm btn-light mb-1" data-bs-toggle="tooltip" title="Status Tidak Aktif, Aktifkan ?" onclick="_updateStatus('."'".$row->id."'".', '."'1'".');"><i class="fas fa-toggle-off fs-2"></i></button>';
                }
                return $activeCustom;
            })
            ->addColumn('action', function($row){
                $btnEdit = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-dark mb-1 ms-1" data-bs-toggle="tooltip" title="Edit data!" onclick="_editSlide('."'".$row->id."'".');"><i class="la la-edit fs-3"></i></button>';
                $btnDelete = '<button type="button" class="btn btn-icon btn-circle btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Hapus data!" onclick="_deleteSlide('."'".$row->id."'".');"><i class="las la-trash-alt fs-3"></i></button>';
                return $btnEdit.$btnDelete;
            })
            ->rawColumns(['file_image', 'status', 'action'])
            ->make(true);
    }
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'file_image' => 'required|mimes:png,jpg,jpeg|max:2048',
        ],[
            'file_image.required' => 'File banner masih kosong.',
            'file_image.max' => 'File banner tidak lebih dari 2MB.',
            'file_image.mimes' => 'File banner berekstensi jpg jepg png.',

        ]);
    
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            $mainImage = $request->file('file_image');
            $filename = md5(Shortcut::random_strings(20)) . '.' . $mainImage->extension();
            Image::make($mainImage)->resize(2610,700)->save(public_path('dist/img/banner/'.$filename));
            
            Banners::create([
                'file_image' => $filename,
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
        $data_banner = Banners::where('id', $data_id)->first();
        $banner_image = asset('dist/img/banner/'.$data_banner->file_image); 
        return response()->json([
            'status' => TRUE,
            'row' =>$data_banner,
            'file_image' =>$banner_image,
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

        if($request->file('file_image')){
            $validator = Validator::make($request->all(), [
                'file_image' => 'required|mimes:png,jpg,jpeg|max:2048',
            ],[
                'file_image.required' => 'File banner masih kosong.',
                'file_image.max' => 'File banner tidak lebih dari 2MB.',
                'file_image.mimes' => 'File banner berekstensi jpg jepg png.',
            ]);

            if($validator->fails()){
                foreach ($validator->errors()->getMessages() as $item) {
                    $errors[] = $item;
                }
                $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
            } else {
                $mainImage = $request->file('file_image');
                $filename = md5(Shortcut::random_strings(20)) . '.' . $mainImage->extension();
                Image::make($mainImage)->resize(2610,700)->save(public_path('dist/img/banner/'.$filename));
                Banners::where('id', $data_id)->update([
                    'file_image' => $filename,
                    'status' => $status,
                    'user_updated' => session()->get('nama'),
                    'updated_at' => Carbon::now()
                ]);
                $output = array("status" => TRUE);
            }
        }else{
            Banners::where('id', $data_id)->update([
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
        $message = 'Banner berhasil dinonaktifkan';
        if($value == 1){
        $message = 'Banner berhasil diaktfikan';
        }
        Banners::where('id', $data_id)->update([
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
        Banners::where('id', $data_id)->delete();
        return response()->json(['status' => TRUE]);
    }
}
