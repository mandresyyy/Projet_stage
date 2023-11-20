<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_action extends Model
{
    use HasFactory;
    protected $table = 'type_action';
    public $timestamps = false;
    protected $fillable = ['id','action'];
}
