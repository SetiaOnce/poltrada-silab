<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAlatPeraga extends Model
{
    public $table = 'silab_data_alat_peraga';
    
    use HasFactory;

    protected $fillable = [
        'kode_alat_peraga',
        'nama_alat_peraga',
        'jumlah',
        'fid_satuan',
        'kondisi',
        'fid_lab',
        'fid_lokasi',
        'foto',
        'keterangan',
        'status',
        'user_add',
        'user_updated',
    ];

    public function lokasi()
    {
    	return $this->belongsTo('App\Models\LokasiLab', 'fid_lokasi', 'id'); 
    } 
    public function lab()
    {
    	return $this->belongsTo('App\Models\NamaLaboratorium', 'fid_lab', 'id'); 
    } 
    public function satuan()
    {
    	return $this->belongsTo('App\Models\Satuan', 'fid_satuan', 'id'); 
    } 
}
