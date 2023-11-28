<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    public $table = 'silab_buku_tamu';

    protected $guarded = [];
    
    use HasFactory;
}
