<?php

namespace App\Http\Controllers;

use App\Models\Technologie;
use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\Type_action;
use Illuminate\Support\Facades\DB;

class Techno_Contr extends Controller
{
   public function liste(){
    if(auth()->check()){
        $utilisateur=auth()->user();
        $liste=Technologie::all();
            $action = new Logs();
            $action->id_utilisateur = auth()->user()->id;
            $idtypeaction = Type_action::where('action', '=', 'liste')->pluck('id')->first();
            $action->id_type_action = $idtypeaction;
            $action->detail = 'Liste technologies ';
            $action->newLogs();
            $page='tech';
        return view ('Admin/liste_tech',compact('page','utilisateur','liste'));
    }
    else{
        return redirect()->route('login');
    }
   
   }
   public function form(){
    if(auth()->check()){
        $utilisateur=auth()->user();
        $page='tech';
        return view ('Admin/new_tech',compact('page','utilisateur'));
    }
    else{
        return redirect()->route('login');
    }
   
   }
   public function save(Request $request){
    $request->validate([
        'techno' => 'required',
    ]);
    if(auth()->check()){
        $utilisateur=auth()->user();
        $tech=new Technologie();
        $tech->generation=$request->input('techno');
        $bool=$tech->check();
        if($bool){
            Technologie::create([
                'generation' => $tech->generation,
            ]);
            $action = new Logs();
            DB::table('mise_a_jour')->where("domaine",'=','technologie')->update([
                "domaine"=>'technologie',
                "etat"=>'1'
            ]);
            DB::table('mise_a_jour')->where("domaine",'=','technologie_releve')->update([
                "domaine"=>'technologie_releve',
                "etat"=>'1'
            ]);
            $action->id_utilisateur = auth()->user()->id;
            $idtypeaction = Type_action::where('action', '=', 'insertion')->pluck('id')->first();
            $action->id_type_action = $idtypeaction;
            $action->detail = 'Ajout technologies :' .$request->input('techno');
            $action->newLogs();
            return redirect()->back()->with('success', 'Technologie ajoutée avec succès.');
        }
        else{
            return back()->withErrors(['Erreur_creation'=>'Technologie déja existant.']);
        }
        
    }
    else{
        return redirect()->route('login');
    }
   
   }
   public function modif($idUpdate){
    if(auth()->check()){
        $utilisateur=auth()->user();
        $tech=Technologie::find($idUpdate);
        $page='tech';
        return view ('Admin/update_tech',compact('page','utilisateur','tech'));
    }
    else{
        return redirect()->route('login');
    }
   
   }
   public function update(Request $request){
    $request->validate([
        'techno' => 'required',
    ]);
    if(auth()->check()){
        $utilisateur=auth()->user();

        $tech=new Technologie();
        $tech=Technologie::find($request->input('id'));
        $tech->generation=$request->input('techno');
        $bool=$tech->check2();
        if($bool){
           $tech->save();
           $action = new Logs();
           $action->id_utilisateur = auth()->user()->id;
           $idtypeaction = Type_action::where('action', '=', 'modification')->pluck('id')->first();
           $action->id_type_action = $idtypeaction;
           $action->detail = 'Modification technologie :' .$request->input('techno');
           $action->newLogs();
           DB::table('mise_a_jour')->where("domaine",'=','technologie')->update([
            "domaine"=>'technologie',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','technologie_releve')->update([
            "domaine"=>'technologie_releve',
            "etat"=>'1'
        ]);
            return redirect()->back()->with('success', 'Technologie modifiée avec succès.');
        }
        else{
            return back()->withErrors(['Erreur_update'=>'Technologie déja existant.']);
        }
        
    }
    else{
        return redirect()->route('login');
    }
   
   }
   
}
