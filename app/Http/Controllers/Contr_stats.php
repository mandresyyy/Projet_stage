<?php

namespace App\Http\Controllers;

use App\Models\Statistique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Contr_stats extends Controller
{
    public function to_pivot($type){
        if(auth()->check()){
            $utilisateur=auth()->user();
            if($utilisateur->type->type_util=='Admin'){ $action='Admin/Pivottable';}
            else{ $action='Utilisateur/Pivottable';}
            $stats=collect();
            $checkinfra=DB::table('mise_a_jour')->select('etat')->where('domaine','infra')->first();
            $checkinfra_tech=DB::table('mise_a_jour')->select('etat')->where('domaine','infra_tech')->first();
            $checkinfra_source=DB::table('mise_a_jour')->select('etat')->where('domaine','infra_source')->first();
            $checktech=DB::table('mise_a_jour')->select('etat')->where('domaine','technologie')->first();
            $checktype=DB::table('mise_a_jour')->select('etat')->where('domaine','type')->first();
            $checktype_src=DB::table('mise_a_jour')->select('etat')->where('domaine','type_source')->first();
            $checktype_tech=DB::table('mise_a_jour')->select('etat')->where('domaine','type_tech')->first();
            // dd($checktype);
            $checksrc=DB::table('mise_a_jour')->select('etat')->where('domaine','source')->first();
            $checkprop=DB::table('mise_a_jour')->select('etat')->where('domaine','proprietaire')->first();
            $checkoperateur=DB::table('mise_a_jour')->select('etat')->where('domaine','operateur')->first();
            $checkoperateur_src=DB::table('mise_a_jour')->select('etat')->where('domaine','op_source')->first();
            $checkoperateur_tech=DB::table('mise_a_jour')->select('etat')->where('domaine','op_tech')->first();

            
            if($type==0){
                if( $checktype->etat==1 ||  Cache::get('infra_pv')===null || $checkoperateur->etat==1 || $checkinfra->etat==1 ||$checkprop->etat==1){
                    // dd('no');
                    Cache::forget('infra_pv');  
                $stats = Cache::remember('infra_pv',480, function () {
                    return DB::table('v_Infra_Commune_Operateur_Type_CD') 
                    ->get();
                });
               
                DB::table('mise_a_jour')->where("domaine",'=','proprietaire')->update([
                    "domaine"=>'proprietaire',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','type')->update([
                    "domaine"=>'type',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','operateur')->update([
                    "domaine"=>'operateur',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','infra')->update([
                    "domaine"=>'infra',
                    "etat"=>'0'
                ]);
                }
            else{
                $stats = Cache::get('infra_pv');
            }
            }
            else if($type==1){
                if( $checktech->etat==1 ||  Cache::get('tech_pv')===null || $checkoperateur_tech->etat==1 || $checkinfra_tech->etat==1 || $checktype_tech->etat==1){
                    // dd('no');
                    Cache::forget('tech_pv');  
                // $stats=DB::table('v_Technologie_Infra')->get();
                $stats = Cache::remember('tech_pv',480, function () {
                    return DB::table('v_Infra_Technologie_CD') 
                    ->get();
                });
                DB::table('mise_a_jour')->where("domaine",'=','type_tech')->update([
                    "domaine"=>'type_tech',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','technologie')->update([
                    "domaine"=>'technologie',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','op_tech')->update([
                    "domaine"=>'op_tech',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','infra_tech')->update([
                    "domaine"=>'infra_tech',
                    "etat"=>'0'
                ]);
                }
                else{
                $stats = Cache::get('tech_pv');}
            }
            else if($type==2){
                // $stats=DB::table('v_Source_Infra')->get();
                if( $checksrc->etat==1 || Cache::get('src_pv')===null || $checkoperateur_src->etat==1 || $checkinfra_source->etat==1 || $checktype_src->etat==1){
                    // dd('no');
                    Cache::forget('src_pv');  
                // dd('ato');
                $stats = Cache::remember('src_pv',480, function () {
                    return DB::table('v_Infra_Source_CD') 
                    ->get();
                });
                DB::table('mise_a_jour')->where("domaine",'=','type_source')->update([
                    "domaine"=>'type_source',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','source')->update([
                    "domaine"=>'source',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','op_source')->update([
                    "domaine"=>'op_source',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','infra_source')->update([
                    "domaine"=>'infra_source',
                    "etat"=>'0'
                ]);
                }
                else{
                $stats = Cache::get('src_pv');}
            }
            // else if($type==3){
            //     $stats=DB::table('v_Infra_Commune_Operateur_Type_CD')->get();
            // }
            else if($type==4){
                // $stats=DB::table('v_Infra_Commune_Operateur_Type_CD')->get();
                if( $checktype->etat==1 ||  Cache::get('infra_pv')===null || $checkoperateur->etat==1 || $checkinfra->etat==1 ||$checkprop->etat==1){
                    // dd('no');
                    Cache::forget('infra_pv'); 
                $stats = Cache::remember('type_pv',480, function () {
                    return DB::table('v_Infra_Commune_Operateur_Type_CD') 
                    ->get();
                });
                DB::table('mise_a_jour')->where("domaine",'=','proprietaire')->update([
                    "domaine"=>'proprietaire',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','type')->update([
                    "domaine"=>'type',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','operateur')->update([
                    "domaine"=>'operateur',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','infra')->update([
                    "domaine"=>'infra',
                    "etat"=>'0'
                ]);
                }
                else{
                $stats = Cache::get('infra_pv');}
            }
            else{
                // $stats=DB::table('v_Infra_Commune_Operateur_Type_CD')->get();
                if( $checktype->etat==1 ||  Cache::get('infra_pv')===null || $checkoperateur->etat==1 || $checkinfra->etat==1 ||$checkprop->etat==1){
                    // dd('no');
                    Cache::forget('infra_pv'); 
                $stats = Cache::remember('type_pv',480, function () {
                    return DB::table('v_Infra_Commune_Operateur_Type_CD') 
                    ->get();
                });
                DB::table('mise_a_jour')->where("domaine",'=','proprietaire')->update([
                    "domaine"=>'proprietaire',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','type')->update([
                    "domaine"=>'type',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','operateur')->update([
                    "domaine"=>'operateur',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','infra')->update([
                    "domaine"=>'infra',
                    "etat"=>'0'
                ]);
                }
                else{
                $stats = Cache::get('infra_pv');}
            }
            // dd($stats);
            $page="stats";
            return view ($action,compact('page','utilisateur','stats','type'));
        }
        else{
            return redirect()->route('login');
        }
    

    }
    public function stats(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            if($utilisateur->type->type_util=='Admin'){ $action='Admin/dashboard';}
            else{ $action='Utilisateur/dashboard';}
            $stats=DB::table('v_Stats_InfraByOperateur') 
            ->get();
            
            $byTech=[];
            $operateur=DB::table('Liste_Operateur')->get();
            $techno=DB::table('Liste_Techno')->get();
            $i=0;
            foreach($operateur as $op){
                foreach($techno as $tec){
                    $byTech[$i][]=DB::table('v_Stats_TechByOperateur')->where('id_tech','=',$tec->id_tech)
                    ->where('id','=',$op->id)->first();
                }
                $i++;
            }
            $page="stats";

            $mutualis=DB::table('v_Stats_mutualise')->get();
            if(count($mutualis)==1){
               $mutualise=0;
            }
            else if(count($mutualis)==0){
                $mutualise=0;
            }
            
            else{
                $mutualise=$mutualis[1]->nb;
            }
            $proprio=DB::table('v_Stats_proprio')->get();
            $type=DB::table('v_Stats_type')->get();
            $nbInfra=DB::table('nb_infra')->first();
            $nbOperateur=DB::table('nb_operateur')->first();
            $nbTechnologie=DB::table('nb_technologie')->first();
            return view ($action,compact('nbTechnologie','nbOperateur','nbInfra','page','utilisateur','stats','byTech','operateur','techno','mutualise','proprio','type'));
        }
        else{
            return redirect()->route('login');
        }
    

    }
}
