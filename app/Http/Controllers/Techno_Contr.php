<?php

namespace App\Http\Controllers;

use App\Models\Technologie;
use Illuminate\Http\Request;

class Techno_Contr extends Controller
{
   public function liste(){
    if(auth()->check()){
        $utilisateur=auth()->user();
        $liste=Technologie::paginate(7);
        return view ('Admin/liste_tech',compact('utilisateur','liste'));
    }
    else{
        return redirect()->route('login');
    }
   
   }
   public function form(){
    if(auth()->check()){
        $utilisateur=auth()->user();
        return view ('Admin/new_tech',compact('utilisateur'));
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
        return view ('Admin/update_tech',compact('utilisateur','tech'));
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
