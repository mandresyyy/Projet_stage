<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Proprietaire_site extends Model
{
    use HasFactory;
    protected $table = 'proprietaire_site';
    public $timestamps = false;
    protected $fillable = ['id','proprietaire'];

    public function getId(){
        $p=Proprietaire_site::where('proprietaire','=',$this->proprietaire)->first();
        if($p==null){
            $id = DB::table('proprietaire_site')->insertGetId(
                ['proprietaire' => $this->proprietaire],
            );
            return $id;
        }
        else{
            return $p->id;
        }
    }
}
