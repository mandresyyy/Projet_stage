<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technologie extends Model
{
    use HasFactory;
    protected $table = 'technologie';
    public $timestamps = false;
    protected $fillable = ['id','generation'];

    public function check(){
        $tech=Technologie::where('generation','=',$this->generation)
        ->first();

        if($tech ==null){
            return true;
        }
        else{
            return false;
        }
    }
    public function check2(){
        $tech=Technologie::where('generation','=',$this->generation)
        ->where('id','!=',$this->id)
        ->first();

        if($tech ==null){
            return true;
        }
        else{
            return false;
        }
    }
    public function not_in($liste){
        $check=true;
        foreach($liste as $l){
            if($this->id==$l->technologie->id){
                $check=false;
                break;
            }
        }
        return $check;
    }
}
