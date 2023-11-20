<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;
    protected $table = 'commune';
    public $timestamps = false;
    protected $fillable = ['id','commune','code_c','district','code_d','region','code_r'];    
    
}
