<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operateur extends Model
{
    use HasFactory;
    protected $table = 'operateur';
    public $timestamps = false;
    protected $fillable = ['id','operateur','couleur','logo'];

    public function check(){
        $check=Operateur::where('operateur','=',$this->operateur)->first();
        if($check==null){
            return true;
        }
        else{
            return false;
        }
    }
    public function check2(){
        $check=Operateur::where('operateur','=',$this->operateur)
        ->where('id','!=',$this->id)
        ->first();
        if($check==null){
            return true;
        }
        else{
            return false;
        }
    }
    public function notIn($liste){
        $check=true;
        foreach($liste as $l){
            if($l->operateur->id==$this->id){
                $check=false;
                break;
            }
        }
        return $check;
    }
}
