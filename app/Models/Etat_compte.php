<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etat_compte extends Model
{
    use HasFactory;
    protected $table = 'etat_compte';
    public $timestamps = false;
    protected $fillable =['id','etat'];

}
