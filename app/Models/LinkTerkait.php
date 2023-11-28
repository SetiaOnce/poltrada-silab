<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkTerkait extends Model
{
    public $table = 'silab_link_terkait';
    
    use HasFactory;
    
    protected $guarded = [];
}
