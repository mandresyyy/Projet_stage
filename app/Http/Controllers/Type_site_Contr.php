<?php

namespace App\Http\Controllers;

use App\Models\Type_site;
use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\Type_action;

class Type_site_Contr extends Controller
{
    public function form(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            return view ('Admin/new_type_site',compact('utilisateur'));
        }
        else{
            return redirect()->route('login');
        }
       
    }

    public function liste()
    {
        if (auth()->check()) {
            $utilisateur = auth()->user();
            $liste = Type_site::where('type', '!=', 'Non defini')->paginate(7);
            $action = new Logs();
            $action->id_utilisateur = auth()->user()->id;
            $idtypeaction = Type_action::where('action', '=', 'liste')->pluck('id')->first();
            $action->id_type_action = $idtypeaction;
            $action->detail = 'Liste type de site';
            $action->newLogs();
            return view('Admin/liste_type_site', compact('utilisateur', 'liste'));
        } else {
            return redirect()->route('login');
        }
    }

    public function save(Request $request){
        $request->validate([
            'type' => 'required',
        ]);
        if(auth()->check()){
            $utilisateur=auth()->user();
            $type=new Type_site();
            $type->type=$request->input('type');
            $bool=$type->check();
            if($bool){
                Type_site::create([
                    'type' => $type->type,
                ]);
                $action = new Logs();
                $action->id_utilisateur = auth()->user()->id;
                $idtypeaction = Type_action::where('action', '=', 'insertion')->pluck('id')->first();
                $action->id_type_action = $idtypeaction;
                $action->detail = 'Ajout nouveau type de site '.$type->type;
                $action->newLogs();
                return redirect()->back()->with('success', 'Type de site ajoutée avec succès.');
            }
            else{
                return back()->withErrors(['Erreur_creation'=>'Type de site déja existant.']);
            }
            
        }
        else{
            return redirect()->route('login');
        }
       
       }

       public function modif($idUpdate){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $type=Type_site::find($idUpdate);
            return view ('Admin/update_type_site',compact('utilisateur','type'));
        }
        else{
            return redirect()->route('login');
        }
       
       }
       public function update(Request $request){
        $request->validate([
            'type' => 'required',
        ]);
        if(auth()->check()){
            // $utilisateur=auth()->user();
    
            $type=new Type_site();
            $type=Type_site::find($request->input('id'));
            $type->type=$request->input('type');
            $bool=$type->check2();
            if($bool){
               $type->save();
               $action = new Logs();
               $action->id_utilisateur = auth()->user()->id;
               $idtypeaction = Type_action::where('action', '=', 'modification')->pluck('id')->first();
               $action->id_type_action = $idtypeaction;
               $action->detail = 'Modification type de site '.$type->type;
               $action->newLogs();
                return redirect()->back()->with('success', 'Type de site ajoutée avec succès.');
            }
            else{
                return back()->withErrors(['Erreur_creation'=>'Type de site déja existant.']);
            }
            
        }
        else{
            return redirect()->route('login');
        }
       
       }
}
