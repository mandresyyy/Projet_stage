<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infra_technologie extends Model
{
    use HasFactory;
    protected $table = 'infra_technologie';
    public $timestamps = false;
    protected $fillable = ['id_infra','id_technologie'];

    public function infra(){
        return $this->belongsTo(Infra::class,'id_infra');
    }

    public function technologie(){
        return $this->belongsTo(Technologie::class,'id_technologie');
    }
}
