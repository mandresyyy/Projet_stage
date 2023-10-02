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
use App\Models\Releve;
use GeoJSON\GeoJSON;
use GeoJSON\Feature\FeatureCollection;

class Contr_general extends Controller
{
    public function to_login(){
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
            return view ('Admin/map_liste',compact('liste','utilisateur','operateur','technologie','type','source','region'));
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
            return view ('Utilisateur/map_liste',compact('liste','utilisateur','operateur','technologie','type','source','region'));
        }
        else{
            return redirect()->route('login');
        }

    }
    public function create_user(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $type_user=Type_util::all();
            return view ('Admin/new_user',compact('utilisateur','type_user'));
        }
        else{
            return redirect()->route('login');
        }

    }
    public function profile(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            return view ('Admin/Profile',compact('utilisateur'));
        }
        else{
            return redirect()->route('login');
        }

    }
    public function user_profile(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            return view ('Utilisateur/Profile',compact('utilisateur'));
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
        
        // $operateur=[3,2];
        // $region=[11,12];
        // $techno=[2,3];
        // $type=[2,3];
        // $source=[2];

        $infr=new Infra();
        $resultat=$infr->to_geoSon($operateur,$region,$techno,$type,$source,$mutualise,$user);
        $tab=[];
        $tab[]=$resultat;
        $tab[]=$user->id;
        $action = new Logs();
        $action->id_utilisateur = auth()->user()->id;
        $idtypeaction = Type_action::where('action', '=', 'Recherche')->pluck('id')->first();
        $action->id_type_action = $idtypeaction;
        $action->detail = 'Recherche sur la carte ';
        $action->newLogs();
        return $tab;
    }
  
//     public function test(){
//         // $geojsonFilePath = public_path('geojson/region.geojson');
//         // $file = fopen($geojsonFilePath, 'r');
//         // $nb=1;
//         // while (($line = fgets($file)) !== false) {
//         //     if($nb==6){
//         //         dd($line);
//         //     }
//         //     $nb++;
//         // }
//         $pythonScriptPath = public_path('test.py');
// $command = "python $pythonScriptPath";
// $output = shell_exec($command);
// dd($output);
// // Traitez la sortie du script Python (supposons que le script renvoie un tableau associatif JSON)
// $pythonOutput = json_decode($output, true);
// dd($pythonOutput);
// // Utilisez les données dans Laravel
// if (!empty($pythonOutput)) {
//     // Faites quelque chose avec les données, par exemple, les afficher
//     dd($pythonOutput);
// } else {
//     // Le script Python n'a pas renvoyé de données valides
//     echo "Le script Python n'a pas renvoyé de données valides.";
// }
//     }





    
}
