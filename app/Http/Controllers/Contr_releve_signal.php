<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Releve;
use App\Models\Operateur;
use Illuminate\Support\Facades\File;
use App\Models\Infra;

class Contr_releve_signal extends Controller
{
    public function MapReleve(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $releve = new Releve();
            $data = $releve->getAllReleve();
            // dd($data[0][0]['Timestamp']); //fichier // ligne txt // colonne
            // dd($data['Orange']);
            // dd($data);
            $infra=new Infra();
            $infra->all_to_geoSon();
            $operateur=Operateur::where('operateur','!=','Non defini')->get();
            return view ('Admin/map_releve',compact('utilisateur','data','operateur'));
        }
        else{
            return redirect()->route('login');
        }
    }
    public function MapReleveUser(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $releve = new Releve();
            $data = $releve->getAllReleve();
            // dd($data[0][0]['Timestamp']); //fichier // ligne txt // colonne
            // dd($data['Orange']);
            // dd($data);
            $infra=new Infra();
            $infra->all_to_geoSon();
            $operateur=Operateur::where('operateur','!=','Non defini')->get();
            return view ('Utilisateur/map_releve',compact('utilisateur','data','operateur'));
        }
        else{
            return redirect()->route('login');
        }
    }

    public function FormReleve(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $operateur=Operateur::where('operateur','!=','Non defini')->get();
            return view ('Admin/newReleve',compact('utilisateur','operateur'));
        }
        else{
            return redirect()->route('login');
        }
    }

    public function upload(Request $request){
        $request->validate([
            'fichier' => 'required|mimes:txt|max:2048',
        ]);
        // $tempFilePath = $request->file('fichier')->getRealPath();

        $chemin_dossier = storage_path('app/public/Releve/'.$request->input('operateur'));
        // dd(File::exists($chemin_dossier));

        if (!File::exists($chemin_dossier)) {

            File::makeDirectory($chemin_dossier, 0777, true);

        }
        $fileName = $request->file('fichier')->getClientOriginalName();

        $filePath = $request->file('fichier')->storeAs('public/Releve/'.$request->input('operateur'), $fileName);
        return redirect()->back()->with('success', 'Fichier ajouté avec succès.');
    }
}
