<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infra_source extends Model
{
    use HasFactory;
    protected $table = 'infra_source';
    public $timestamps = false;
    protected $fillable = ['id_infra','id_source'];

    public function infra(){
        return $this->belongsTo(Infra::class,'id_infra');
    }

    public function source(){
        return $this->belongsTo(Source_energie::class,'id_source');
    }
}
