<?php

namespace App\Http\Controllers;

use App\Models\AkademikProdi;
use App\Models\DataAlatPeraga;
use App\Models\LokasiLab;
use App\Models\NamaLaboratorium;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SelectController extends Controller
{
    public function satuan()
    {
        $query = Satuan::orderBy('satuan', 'ASC')->where('status', 1)->get();
        return response()->json($query);
    }
    public function laboratorium()
    {
        $query = NamaLaboratorium::orderBy('nama_laboratorium', 'ASC')->where('status', 1)->get();
        return response()->json($query);
    }
    public function lokasi()
    {
        $query = LokasiLab::orderBy('nama_lokasi', 'ASC')->where('status', 1)->get();
        return response()->json($query);
    }
    public function prodi()
    {
        $query = AkademikProdi::orderBy('nama_prodi', 'ASC')->where('status', 1)->get();
        return response()->json($query);
    }
    public function alatPeraga()
    {
        $query = DataAlatPeraga::orderBy('nama_alat_peraga', 'ASC')->where('status', 1)->get();
        return response()->json($query);
    }
    public function statusPeminjaman()
    {
        $query = [
            [
                'id' => 'DOSEN',
                'status' => 'DOSEN',
            ],
            [
                'id' => 'TARUNA',
                'status' => 'TARUNA',
            ],
            [
                'id' => 'INSTRUKTUR',
                'status' => 'INSTRUKTUR',
            ],
            [
                'id' => 'PESERTA DIKLAT',
                'status' => 'PESERTA DIKLAT',
            ],
            [
                'id' => 'UMUM',
                'status' => 'UMUM',
            ],
        ];
        return response()->json($query);
    }
}
