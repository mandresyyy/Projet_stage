<?php

namespace App\Http\Controllers;

use App\Models\Operateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Logs;
use App\Models\Type_action;

class Operateur_Contr extends Controller
{
    public function liste()
    {
        if (auth()->check()) {
            $utilisateur = auth()->user();
            $liste = Operateur::where('operateur', '!=', 'Non defini')->paginate(7);
            $action = new Logs();
            $action->id_utilisateur = auth()->user()->id;
            $idtypeaction = Type_action::where('action', '=', 'liste')->pluck('id')->first();
            $action->id_type_action = $idtypeaction;
            $action->detail = 'Liste operateurs ';
            $action->newLogs();
            return view('Admin/liste_operateur', compact('utilisateur', 'liste'));
        } else {
            return redirect()->route('login');
        }
    }
    public function form()
    {
        if (auth()->check()) {
            $utilisateur = auth()->user();
            return view('Admin/new_operateur', compact('utilisateur'));
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
            return view('Admin/update_operateur', compact('utilisateur','oper'));
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
                $request->validate([
                    'photo' => 'required|image|max:2048',
                ]);

                $tempFilePath = $request->file('photo')->getRealPath();

                $fileName = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();

                $filePath = $request->file('photo')->storeAs('public/photos', $fileName);

                $op->logo=$fileName;
                $op->save();
                $action=new Logs();
                $action->id_utilisateur=auth()->user()->id;
                $idtypeaction=Type_action::where('action','=','modification')->pluck('id')->first();
                $action->id_type_action=$idtypeaction;
                $action->detail='Modification operateur :'.$request->input('operateur');
                $action->newLogs();
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
