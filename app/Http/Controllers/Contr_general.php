<?php

namespace App\Http\Controllers;

use App\Models\Infra;
use App\Models\Operateur;
use App\Models\Source_energie;
use Illuminate\Http\Request;
use App\Models\Technologie;
use App\Models\Type_site;
use App\Models\Type_util;
use Illuminate\Support\Facades\DB;
use App\Models\Logs;
use App\Models\Type_action;
use Illuminate\Support\Facades\Cache;

class Contr_general extends Controller
{
    public function to_login(){
        if(Cache::get('district')===null){
            
            Cache::rememberForever('district', function(){
                $geojson=public_path('geojson');
                return  file_get_contents($geojson.'/district.topojson');
            });
        }
        if(Cache::get('region')===null){
            Cache::rememberForever('region', function(){
                $geojson=public_path('geojson');
                return  file_get_contents($geojson.'/region.topojson');
            });
        }
       
        return view('login');
    }
    public function to_acceuil_admin(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $operateur=Operateur::where('operateur','!=','Non defini')->get();
            $technologie=Technologie::all();
            $type=Type_site::where('type','!=','Non defini')->get();
            $source=Source_energie::all();
            $region=DB::table('v_Liste_Region')->get();
            $liste=collect();
            $page='map';
            return view ('Admin/map_liste',compact('page','liste','utilisateur','operateur','technologie','type','source','region'));
        }
        else{
            return redirect()->route('login');
        }
    }
    public function to_acceuil_user(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $operateur=Operateur::where('operateur','!=','Non defini')->get();
            $technologie=Technologie::all();
            $type=Type_site::where('type','!=','Non defini')->get();
            $source=Source_energie::all();
            $region=DB::table('v_Liste_Region')->get();
            $liste=collect();
            $page='map';
            return view ('Utilisateur/map_liste',compact('page','liste','utilisateur','operateur','technologie','type','source','region'));
        }
        else{
            return redirect()->route('login');
        }

    }
    public function create_user(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $type_user=Type_util::all();
            $page='util';
            return view ('Admin/new_user',compact('page','utilisateur','type_user'));
        }
        else{
            return redirect()->route('login');
        }

    }
    public function profile(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $page='';
            return view ('Admin/Profile',compact('page','utilisateur'));
        }
        else{
            return redirect()->route('login');
        }

    }
    public function user_profile(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $page='';
            return view ('Utilisateur/Profile',compact('page','utilisateur'));
        }
        else{
            return redirect()->route('login');
        }

    }

    public function loadData(Request $request){
        $user=auth()->user();
        $operateur=$request->input('operateur');
        $region=$request->input('region');
        $techno=$request->input('techno');
        $type=$request->input('type');
        $source=$request->input('source');
        $mutualise=$request->input('mutualise');
        if($operateur==null){
            $operateur=[];
        }
        if($techno==null){
            $techno=[];
        }
        if($region==null){
            $region=[];
        }
        if($type==null){
            $type=[];
        }
        if($source==null){
            $source=[];
        }
        

        $infr=new Infra();
        $resultat=$infr->to_geoSon($operateur,$region,$techno,$type,$source,$mutualise,$user);
        $request->session()->put('liste_search', $resultat); 
        
        $action = new Logs();
        $action->id_utilisateur = auth()->user()->id;
        $idtypeaction = Type_action::where('action', '=', 'Recherche')->pluck('id')->first();
        $action->id_type_action = $idtypeaction;
        $action->detail = 'Recherche sur la carte ';
        $action->newLogs();
        // dd($resultat);
        return $resultat;
    }
    
}
