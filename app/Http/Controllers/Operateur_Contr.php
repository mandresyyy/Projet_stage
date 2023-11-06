<?php

namespace App\Http\Controllers;

use App\Models\Operateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Logs;
use App\Models\Type_action;
use Illuminate\Support\Facades\DB;
class Operateur_Contr extends Controller
{
    public function liste()
    {
        if (auth()->check()) {
            $utilisateur = auth()->user();
            $liste = Operateur::where('operateur', '!=', 'Non defini')->get();
            $action = new Logs();
            $action->id_utilisateur = auth()->user()->id;
            $idtypeaction = Type_action::where('action', '=', 'liste')->pluck('id')->first();
            $action->id_type_action = $idtypeaction;
            $action->detail = 'Liste operateurs ';
            $action->newLogs();
            $page='op';
            return view('Admin/liste_operateur', compact('page','utilisateur', 'liste'));
        } else {
            return redirect()->route('login');
        }
    }
    public function form()
    {
        if (auth()->check()) {
            $utilisateur = auth()->user();
            $page='op';
            return view('Admin/new_operateur', compact('page','utilisateur'));
        } else {
            return redirect()->route('login');
        }
    }

    public function save(Request $request)
    {
        if (auth()->check()) {
            // $utilisateur = auth()->user();
            $op = new Operateur();
            $op->operateur = $request->input('operateur');
            $op->couleur = $request->input('couleur');
            // upload photo
            if($op->check()){
                $request->validate([
                    'photo' => 'required|image|max:2048',
                ]);

                $tempFilePath = $request->file('photo')->getRealPath();

                $fileName = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();

                $filePath = $request->file('photo')->storeAs('public/photos', $fileName);


                Operateur::create([
                    'logo' => $fileName,
                    'operateur' => $op->operateur,
                    'couleur' => $op->couleur
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','operateur')->update([
                    "domaine"=>'operateur',
                    "etat"=>'1'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','operateur_releve')->update([
                    "domaine"=>'operateur_releve',
                    "etat"=>'1'
                ]);
                $action=new Logs();
                $action->id_utilisateur=auth()->user()->id;
                $idtypeaction=Type_action::where('action','=','insertion')->pluck('id')->first();
                $action->id_type_action=$idtypeaction;
                $action->detail='Ajout operateur :'.$request->input('operateur');
                $action->newLogs();

                // Redirigez l'utilisateur avec un message de succès
                return redirect()->back()->with('success', 'Opérateur crée avec succès.');
            }
            else{
                return back()->withErrors(['Erreur_creation'=>'Operateur déja existant.']);
            }
        } else {
            return redirect()->route('login');
        }
    }
    public function modif($idUpdate){
        if (auth()->check()) {
            $utilisateur = auth()->user();
            $oper=Operateur::find($idUpdate);
            $page='op';
            return view('Admin/update_operateur', compact('page','utilisateur','oper'));
        } else {
            return redirect()->route('login');
        }
    }
    public function update(Request $request){
        if (auth()->check()) {
            // $utilisateur = auth()->user();
            $op = new Operateur();
            $op=Operateur::find($request->input('id'));
            $op->operateur = $request->input('operateur');
            $op->couleur = $request->input('couleur');
            // upload photo
            if($op->check2()){
                if($request->input('photo')!=null){
                    $request->validate([
                        'photo' => 'required|image|max:2048',
                    ]);
                    
                $tempFilePath = $request->file('photo')->getRealPath();

                $fileName = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();

                $filePath = $request->file('photo')->storeAs('public/photos', $fileName);

                $op->logo=$fileName;
                }

                $op->save();
                $action=new Logs();
                $action->id_utilisateur=auth()->user()->id;
                $idtypeaction=Type_action::where('action','=','modification')->pluck('id')->first();
                $action->id_type_action=$idtypeaction;
                $action->detail='Modification operateur :'.$request->input('operateur');
                $action->newLogs();
                DB::table('mise_a_jour')->where("domaine",'=','operateur')->update([
                    "domaine"=>'operateur',
                    "etat"=>'1'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','operateur_releve')->update([
                    "domaine"=>'operateur_releve',
                    "etat"=>'1'
                ]);
                // Redirigez l'utilisateur avec un message de succès
                return redirect()->back()->with('success', 'opérateur modifier avec succès.');
            }
            else{
                return back()->withErrors(['Erreur_creation'=>'Operateur déja existant.']);
            }
        } else {
            return redirect()->route('login');
        }
    }
}
