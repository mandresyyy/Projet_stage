<?php

namespace App\Http\Controllers;

use App\Models\Etat_compte;
use App\Models\Type_util;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UtilisateurContr extends Controller
{
    public function se_Connecter(Request $request){
        $utilisateur=new Utilisateur();
        $utilisateur->email=$request->input('email');
        $utilisateur->motdepasse=$request->input('motdepasse');

        $check = $utilisateur->SeConnecter();
            if($check instanceof Utilisateur){
                
                auth()->login($check);

                if ($check->id_type_util==1){
                    return redirect()->route('admin.acceuil');
                }
                else{
                    dd("ato");
                    auth()->logout();
                }
               
               
            }

            else{
                return back()->withErrors(['erreur' => $check])->withInput();
            }
    }

    public function create_user(Request $request){
        if(auth()->check()){
            if($request->input('mdp')==$request->input('mdpc')){
                $utilisateur=new Utilisateur();
                $utilisateur->email=$request->input('mail');
                $utilisateur->motdepasse=$request->input('mdpc');
                $utilisateur->matricule=$request->input('matricule');
                $utilisateur->nom=$request->input('nom');
                $utilisateur->prenom=$request->input('prenom');
                $utilisateur->id_type_util=$request->input('type');
                // dd($utilisateur);
                $boolean=$utilisateur->SInscrire();

                if($boolean){
                    return back()->with(['success' => 'Utilisateur crée']);
                }
                else{
                    $erreur="Cet Adresse mail a déjà un compte";
                    return back()->withErrors(['erreur_inscri' => $erreur])->withInput();
                }
            }
            else{
                $erreur="Mot de passe non identique";
                return back()->withErrors(['erreur_inscri' => $erreur])->withInput();
            }
            return view ('Admin/new_user',compact('utilisateur','type_user'));
        }
        else{
            return redirect()->route('login');
        }

    }
    public function liste_user(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $liste=Utilisateur::paginate(7);
            return view ('Admin/liste_user',compact('utilisateur','liste'));
        }
        else{
            return redirect()->route('login');
        }

    }
    

    public function to_modif($idUpdate){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $util=Utilisateur::find($idUpdate);
            $type_user=Type_util::all();
            $etat=Etat_compte::all();
            return view ('Admin/update_user',compact('utilisateur','util','type_user','etat'));
        }
        else{
            return redirect()->route('login');
        }
    }

    public function update_user(Request $request){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $util=new Utilisateur();
            $util->matricule=$request->input('matricule');
            $util->nom=$request->input('nom');
            $util->prenom=$request->input('prenom');
            $util->email=$request->input('mail');
            $util->id=$request->input('id');
            $util->id_type_util=$request->input('type');
            $util->id_etat_compte=$request->input('etat');
            $bool=$util->modifier();
            if($bool){
                return back()->with(['success' => 'Modification effectuée']);
            }
            else{
                return back()->withErrors(['erreur_modif' => 'Cet mail a déja un compte']);
            }
        }
        else{
            return redirect()->route('login');
        }
    }

    public function log_out(){
        auth()->logout();
        return redirect()->route('login');
    }

    public function update_profile(Request $request){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $util=new Utilisateur();
            $util->matricule=$utilisateur->matricule;
            $util->nom=$request->input('nom');
            $util->prenom=$request->input('prenom');
            $util->email=$request->input('mail');
            $util->id=$utilisateur->id;
            $util->id_type_util=$utilisateur->id_type_util;
            $util->id_etat_compte=$utilisateur->id_etat_compte;
            $bool=$util->modifier();
            if($bool){
                return back()->with(['success' => 'Modification effectuée']);
            }
            else{
                return back()->withErrors(['erreur_modif' => 'Cet mail a déja un compte']);
            }
        }
        else{
            return redirect()->route('login');
        }
    }

    public function change_password(Request $request){
        if(auth()->check()){
            $request->validate([
                'current_mdp' => 'required',
                'motdepasse' => 'required|min:4',
                'confirmation' => 'required|min:4',
            ]);
            $utilisateur=auth()->user();
            $util=new Utilisateur();
            $check=Hash::check($request->input('current_mdp'), $utilisateur->motdepasse);
            if($utilisateur->id==1 && $check==false){
              $check=$request->input('current_mdp')==$utilisateur->motdepasse;
            }
            if($check){
                if($request->input('motdepasse')==$request->input('confirmation')){
                    $util->email=$utilisateur->id;
                    $util->id=$utilisateur->id;
                    $util->motdepasse=$request->input('confirmation');
                    $bool=$util->modifier();
                    if($bool){
                        return back()->with(['success' => 'Mot de passe changé avec succés']);
                    }
                    else{
                        return back()->withErrors(['erreur_modif' => 'Cet mail a déja un compte']);
                    }
                }
                else{
                    return back()->withErrors(['erreur_pwd' => 'Mot de passe non identique']);
                }    
             }
             else{
                return back()->withErrors(['erreur_pwd' => 'Mot de passe incorrect']);
             }
        }
        else{
            return redirect()->route('login');
        }
    }

}
