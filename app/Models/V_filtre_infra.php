<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class V_filtre_infra extends Model
{
    use HasFactory;
    protected $table = 'filtre_infra';
    public $timestamps = false;
    protected $fillable = ['id'];

    public function infra(){
        return $this->belongsTo(Infra::class,'id');
    }
}
