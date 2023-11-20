<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recuperation extends Model
{
    use HasFactory;
    protected $table = 'recuperation';
    public $timestamps = false;
    protected $fillable = ['id','id_utilisateur','code','date_envoie'];    

    public function utilisateur(){
        return $this->belongsTo(Utilisateur::class,'id_utilisateur');
    }

    public function generate(){
        $code='';
        for($i=0;$i<6;$i++){
            $type=mt_rand(0,1);
            if($type==0){
                $code=$code.mt_rand(0,9);
            }
           else{ 
            $code=$code.chr(rand(65, 90));
           }
        }
        return $code;
    }
}
