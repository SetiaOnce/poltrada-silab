<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkademikMahasiswa extends Model
{
    public $table = 'akademik_mahasiswa';
    
    public $timestamps = false;

    use HasFactory;

    public function prodi()
    {
    	return $this->belongsTo('App\Models\AkademikProdi', 'kode_prodi', 'kode_prodi'); 
    } 
}
