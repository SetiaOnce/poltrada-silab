<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPinjaman extends Model
{
    public $table = 'silab_detail_peminjaman';
    
    public $timestamps = false;
    
    protected $guarded = [];
    
    use HasFactory;

    public function peminjaman()
    {
    	return $this->belongsTo('App\Models\Peminjaman', 'fid_peminjaman', 'id'); 
    } 
    public function alat()
    {
    	return $this->belongsTo('App\Models\DataAlatPeraga', 'fid_alat_peraga', 'id'); 
    } 
}
