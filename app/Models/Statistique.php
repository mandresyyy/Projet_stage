<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Statistique extends Model
{
    use HasFactory;

    public $result;


    public function checkD($element,$liste){
        $v=false;
        foreach($liste as $l){
            if($element==$l->district){
                $v=true;
                break;
            }
        }
       if(!$v){
        
       }
    }
    public function remplir(){
        $districtsAbsents = DB::table('Liste_District')
                            ->select('code_d','district','code_r','region')
                            ->whereNotIn('code_d', function($query) {
                                $query->select('code_d')
                                    ->from('v_Infra_Commune_Operateur_Type');
                            })
                            ->get();
       
        foreach($districtsAbsents as $res){
            $collect=new Collection();
            $collect=['district'=>$res->district,'region'=>$res->region,'nb'=>'0'];
            $this->result->push($collect);
        }
        // dd($this->result);
    }
}
