<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\Shortcut;
use App\Http\Controllers\Controller;
use App\Models\JadwalPraktek;
use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class JadwalPraktekController extends Controller
{
    public function index()
    {
        $data['header_title'] = 'Data Jadwal Praktek';   
        return view('backend.admin.jadwal_praktek', $data);
    }
    public function data(Request $request)
    {
        $query = JadwalPraktek::orderBy('tanggal', 'DESC')->orderBy('fid_lab')->whereStatus(0);
        $data = $query->get();
        return Datatables::of($data)->addIndexColumn()
            ->editColumn('waktu', function ($row) {
                return date('d-m-Y', strtotime($row->tanggal)).' '.$row->jam;
            })
            ->editColumn('laboratorium', function ($row) {
                return $row->lab->nama_laboratorium;
            })
            ->addColumn('action', function($row){
                $btnTolak = '<button type="button" class="btn btn-icon btn-sm btn-danger mb-1 ms-1" data-bs-toggle="tooltip" title="Tolak Pengajuan Praktek!" onclick="_actionPermohonana('."'".$row->id."'".', '."'TOLAK'".', '."'".$row->nama_instruktur."'".', '."'".$row->nik_instruktur."'".');"><i class="bi bi-x fs-3"></i></button>';
                $btnTerima = '<button type="button" class="btn btn-icon btn-sm btn-success mb-1 ms-1" data-bs-toggle="tooltip" title="Terima Pengajuan Praktek!" onclick="_actionPermohonana('."'".$row->id."'".', '."'TERIMA'".', '."'".$row->nama_instruktur."'".', '."'".$row->nik_instruktur."'".');"><i class="bi bi-check2-all fs-3"></i></button>';
                return $btnTolak.$btnTerima;
            })
            ->rawColumns(['laboratorium', 'waktu', 'action'])
            ->make(true);
    }
    public function sendAction(Request $request)
    {
        date_default_timezone_set("Asia/Makassar");

        $data_id = $request->input('idp_jadwal');

        $errors					= [];
        $validator = Validator::make($request->all(), [
            'keterangan' => 'required|max:250',
        ],[
            'keterangan.required' => 'Catatan masih kosong...',
            'keterangan.max' => 'Catatan tidak lebih dari 250 karakter...',
        ]);
        if($validator->fails()){
            foreach ($validator->errors()->getMessages() as $item) {
                $errors[] = $item;
            }
            $output = array("status" => FALSE, "pesan_code" => 'format_inputan', "pesan_error" => $errors);
        } else {
            if($request->jenis_aksi == 'TERIMA'){
                $status = 1;
                $statusSendWa = '*_Diterima_*';
            }else{
                $status = 2;
                $statusSendWa = '*_Ditolak_*';
            }
            JadwalPraktek::whereId($data_id)->update([
                'catatan' => $request->input('keterangan'),
                'status' => $status,
                'user_approved' => session()->get('nama'),
                'updated_at' => Carbon::now()
            ]);
            $jadwal = JadwalPraktek::whereId($data_id)->first();
            try{ 
				$pesan_ = 'Salam Hormat, Pengajuan Praktek 
Telah di tindaklanjuti :

*Instruktur*         : '.$jadwal->nama_instruktur.'
*Judul Praktek*     : '.$jadwal->judul_praktek.'
*Tanggal-Jam*      : '.date('d-m-Y', strtotime($jadwal->tanggal)).' - '.$jadwal->jam.'
*Status*              : Permohonan '.$statusSendWa.'
*Catatan*            : '.$request->input('keterangan').'

_Terimakasih telah mengajukan by sistem._';
				 
				$client = new Client(); 
				$body = [  
					'userkey' => "6172fe0f7968",
					'passkey' => "0a3eb6ab0ab36ac514bc7212", 
					'to' => $jadwal->no_wa,
					'message' => $pesan_
				];
				$api_response = $client->request('POST', 'https://console.zenziva.net/wareguler/api/sendWA/', ['verify' => false, 'form_params' => $body]);
				$response=$api_response->getBody()->getContents();
				$get_contents = json_decode($response);
					
			}catch(\GuzzleHttp\Exception\ConnectException $e){
			}
            $output = array("status" => TRUE);
        }

        return response()->json($output);
    }
}