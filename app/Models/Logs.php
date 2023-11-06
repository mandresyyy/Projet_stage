<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Logs extends Model
{
    use HasFactory;
    protected $table = 'logs';
    public $timestamps = false;
    protected $fillable = ['id','id_utilisateur','id_type_action','detail','date'];  

    public function Utilisateur(){
        return $this->belongsTo(Utilisateur::class,'id_utilisateur');
    }  

    public function Type_action(){
        return $this->belongsTo(Type_action::class,'id_type_action');
    }  

    public function newLogs(){
        //  DB::table('logs')->insert(
        //     ['id_utilisateur' => $this->id_utilisateur,
        //     'id_type_action' => $this->id_type_action,
        //     'detail' => $this->detail,
        //     ],
        // );
    }

}
