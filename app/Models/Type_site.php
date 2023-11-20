<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_site extends Model
{
    use HasFactory;
    protected $table = 'type_site';
    public $timestamps = false;
    protected $fillable = ['id','type'];

    public function check(){
        $type=Type_site::where('type','=',$this->type)->first();
        if($type==null){
            return true;
        }
        else{
            return false;
        }
    }
    public function check2(){
        $type=Type_site::where('type','=',$this->type)
        ->where('id','!=',$this->id)
        ->first();
        if($type==null){
            return true;
        }
        else{
            return false;
        }
    }
}
