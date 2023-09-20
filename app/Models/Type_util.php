<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_util extends Model
{
    use HasFactory;
    protected $table = 'type_util';
    public $timestamps = false;
    protected $fillable =['id','type_util'];
}
