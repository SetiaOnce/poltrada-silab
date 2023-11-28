<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeriksaanAlat extends Model
{
    public $table = 'silab_data_pemeriksaan_alat_peraga';
    
    public $timestamps = false;

    protected $guarded = ['id'];

    use HasFactory;
    
    public function alat()
    {
    	return $this->belongsTo('App\Models\DataAlatPeraga', 'fid_alat_peraga', 'id'); 
    } 
}
