<?php

namespace App\Http\Controllers;

use App\Models\Type_site;
use Illuminate\Http\Request;

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
