<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasFactory;
    protected $table = 'utilisateur';
    public $timestamps = false;
    protected $fillable =['id','matricule','nom','prenom','motdepasse','email','id_type_util','id_etat_compte'];

    public function type(){
        return $this->belongsTo(Type_util::class,'id_type_util');
    }
    public function etat(){
        return $this->belongsTo(Etat_compte::class,'id_etat_compte');
    }

    function checkMail(){
        $util=Utilisateur::where('email','=',$this->email)->first();
        if($util == null){
            // dd(true);
            return true;
        }
        else{
            // dd(false);
            return false;
        }
    }
    function checkMail2(){ 
        $util=Utilisateur::where('email','=',$this->email)
        ->where('id','!=',$this->id)
        ->first();
        if($util == null){
            // dd(true);
            return true;
        }
        else{
            // dd(false);
            return false;
        }
    }
    function SInscrire(){
        if($this->checkMail()){
            DB::table('utilisateur')->insert(
                [
                    "id" => $this->id,
                    "nom" => $this->nom,
                    "prenom" =>$this->prenom,
                    "motdepasse" =>bcrypt($this->motdepasse),
                    "email" =>$this->email,
                    "matricule" =>$this->matricule,
                    "id_type_util"=>$this->id_type_util
                ]
            );
            return true;
        }
        else{
            return false;
        }
    }

    function SeConnecter(){
        $check=[]; 
        $util=Utilisateur::where('email','=',$this->email)
        ->where('id_etat_compte','!=','2')
        ->first();
        
            if($util == null){
                $check="Email introuvable";
            }
            
            else{
                if ($util->id == 1) { // admin par  defaut
                    $c=$util->motdepasse ==$this->motdepasse;
                    if ($c) { // si mbola tsy niova mdp 
                        $check = $util;
                       
                    } else { 
                        if(Hash::check($this->motdepasse, $util->motdepasse)){ // raha efa nanova dia efa hashe
                            $check = $util;
                        }
                        else{
                            $check = "Mot de passe incorrect";
                        }
                    }
                } else {
                    if (Hash::check($this->motdepasse, $util->motdepasse)) {
                        $check = $util;
                    } else {
                        $check = "Mot de passe incorrect";
                    }
                }
            }
        return $check;
    }

    public function modifier(){
        if($this->checkMail2()){
            if($this->motdepasse==null){
                $u=Utilisateur::find($this->id);
                $u->nom=$this->nom;
                $u->prenom=$this->prenom;
                $u->email=$this->email;
                $u->id_type_util=$this->id_type_util;
                $u->id_etat_compte=$this->id_etat_compte;
                $u->matricule=$this->matricule;
               
                $u->save();
                return true;
            }
            else{ 
                $u=Utilisateur::find($this->id);
                $u->motdepasse=$this->motdepasse;
                $u->save();
                return true;
            }
        }
        else{
            // dd("tsy mety");
            return false;
        }
    }
    
    
}
