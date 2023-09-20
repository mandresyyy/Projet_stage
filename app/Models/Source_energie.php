<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source_energie extends Model
{
    use HasFactory;
    protected $table = 'source_energie';
    public $timestamps = false;
    protected $fillable = ['id','source'];

    public function not_in($liste){
        $check=true;
        foreach($liste as $l){
            if($this->id==$l->source->id){
                $check= false;
                break;
            }
        }
        return $check;
    }
}
