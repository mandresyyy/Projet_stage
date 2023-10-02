<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function getOrcreateID(){
        $src=Source_energie::where('source','=',$this->source)->first();
        
        if($src==null){
            $id = DB::table('source_energie')->insertGetId(
                ['source' => $this->source],
            );
        }
        else{
            $id=$src->id;
        }
        return $id;
    }
}
