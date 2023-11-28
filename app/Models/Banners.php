<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
    public $table = 'silab_banners_slides';

    protected $guarded = [];
    
    use HasFactory;
}
