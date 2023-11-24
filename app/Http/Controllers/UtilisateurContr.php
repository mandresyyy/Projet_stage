<?php

namespace App\Http\Controllers;

use App\Models\Etat_compte;
use App\Models\Type_util;
use Illuminate\Http\Request;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Logs;
use App\Models\Type_action;
use App\Mail\Sender_Mail;
use Illuminate\Support\Facades\Mail;
use Exception;

class UtilisateurContr extends Controller
{
    public function se_Connecter(Request $request){
        $utilisateur=new Utilisateur();
        $utilisateur->email=$request->input('email');
        $utilisateur->motdepasse=$request->input('motdepasse');

        $check = $utilisateur->SeConnecter();
            if($check instanceof Utilisateur){
                
                auth()->login($check);
                $action=new Logs();
                $action->id_utilisateur=$check->id;
                $idtypeaction=Type_action::where('action','=','connexion')->pluck('id')->first();
                $action->id_type_action=$idtypeaction;
                $action->detail='Connexion reussi';
                $action->newLogs();

                if ($check->id_type_util==1){
                    return redirect()->route('admin.acceuil');
                }
                else{
                   return redirect()->route('user.acceuil');
                }
               
               
            }

            else{
                return back()->withErrors(['erreur' => $check])->withInput();
            }
    }

    public function create_user(Request $request){
        if(auth()->check()){
           
                $utilisateur=new Utilisateur();
                $utilisateur->email=$request->input('mail');
                $utilisateur->motdepasse=$utilisateur->generate();
                $utilisateur->matricule=$request->input('matricule');
                $utilisateur->nom=$request->input('nom');
                $utilisateur->prenom=$request->input('prenom');
                $utilisateur->id_type_util=$request->input('type');
                // dd($utilisateur);
                DB::beginTransaction();
                $boolean=$utilisateur->SInscrire();

                if($boolean){
                    $sujet="Confirmation de votre compte";
                    $view="Mail.confirmation";
        
                    $data = ['code' => $utilisateur->motdepasse];
                    $mail=new Sender_Mail($sujet,$view,15);
                    $mail->with($data);
                    try{
                        Mail::to($utilisateur->email)->send($mail);
                    }
                    catch(Exception $e){
                        DB::rollBack();
                        return back()->withErrors(['erreur_inscri' => 'Mail non envoyé'])->withInput();
                    }
                    $action = new Logs();
                    $action->id_utilisateur = auth()->user()->id;
                    $idtypeaction = Type_action::where('action', '=', 'insertion')->pluck('id')->first();
                    $action->id_type_action = $idtypeaction;
                    $action->detail = 'Creation utilisateur :'.' '. $utilisateur->prenom.' '.$utilisateur->email.' '.$utilisateur->matricule;
                    $action->newLogs();
                    DB::commit();
                    return back()->with(['success' => 'Utilisateur crée']);
                }
                else{
                    $erreur="Cet Adresse mail a déjà un compte";
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
            $action = new Logs();
                    $action->id_utilisateur = auth()->user()->id;
                    $idtypeaction = Type_action::where('action', '=', 'liste')->pluck('id')->first();
                    $action->id_type_action = $idtypeaction;
                    $action->detail = 'Liste utilisateur';
                    $action->newLogs();
                    $page='util';
            return view ('Admin/liste_user',compact('page','utilisateur','liste'));
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
            $page='util';
            return view ('Admin/update_user',compact('page','utilisateur','util','type_user','etat'));
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
                $action = new Logs();
                $action->id_utilisateur = auth()->user()->id;
                $idtypeaction = Type_action::where('action', '=', 'modification')->pluck('id')->first();
                $action->id_type_action = $idtypeaction;
                $action->detail = 'Modification utilisateur : '.$util->email.' '.$util->matricule;
                $action->newLogs();
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
                $action = new Logs();
                $action->id_utilisateur = auth()->user()->id;
                $idtypeaction = Type_action::where('action', '=', 'modification')->pluck('id')->first();
                $action->id_type_action = $idtypeaction;
                $action->detail = 'Modification profil : '.$util->email.' '.$util->matricule;
                $action->newLogs();
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
                        $action = new Logs();
                        $action->id_utilisateur = auth()->user()->id;
                        $idtypeaction = Type_action::where('action', '=', 'modification')->pluck('id')->first();
                        $action->id_type_action = $idtypeaction;
                        $action->detail = 'Modification mot de passe : '.$util->email.' '.$util->matricule;
                        $action->newLogs();
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

    public function search(Request $request){
        // session(['search_utilisateur'=>$request->input('search')]);
       
        if (auth()->check()) {
            

                $utilisateur = auth()->user();
                $list=DB::table('v_all_info_utilisateur')
                        ->whereRaw("concat(email,' ',matricule,' ',nom,' ',prenom,' ',etat) 
                        like ?",['%'.$request->input('search').'%'])
                        ->pluck('id');
            
                    $liste=Utilisateur::whereIn('id',$list)->paginate(20);
                    $action = new Logs();
                        $action->id_utilisateur = auth()->user()->id;
                        $idtypeaction = Type_action::where('action', '=', 'Recherche')->pluck('id')->first();
                        $action->id_type_action = $idtypeaction;
                        $action->detail = 'Recherche utilisateur :'.$request->input('search');
                        $action->newLogs();
                        $page='util';
                    return view ('Admin/liste_user',compact('page','utilisateur','liste'));
            
           
        }
        else {
            return redirect()->route('login');
        }
        
       
    }

}
