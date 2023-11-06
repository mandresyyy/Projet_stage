<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Releve;
use App\Models\Operateur;
use Illuminate\Support\Facades\File;
use App\Models\Infra;
use Illuminate\Support\Facades\DB;
use App\Models\Technologie;

class Contr_releve_signal extends Controller
{
    public function FormReleve(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $operateur=Operateur::where('operateur','!=','Non defini')->get();
            $page='releve';
            return view ('Admin/newReleve',compact('page','utilisateur','operateur'));
        }
        else{
            return redirect()->route('login');
        }
    }

    public function upload(Request $request){
        $request->validate([
            'fichier' => 'required|mimes:txt',
            'description'=>'required'
        ]);
        $file = $request->file('fichier');
        $filePath = $file->getPathname();
        $releve=new Releve();
        $releve->id_operateur=$request->input('operateur');
        $releve->description=$request->input('description');
        $check=$releve->saveReleve($filePath);
       
        if($check!=null){
            return back()->withErrors(['erreur' => $check]);
        }
        else{
            DB::table('mise_a_jour')->where("domaine",'=','releve')->update([
                "domaine"=>'releve',
                "etat"=>'1'
            ]);
            return back()->with(['success'=>'Fichier importé avec succès ']);
        }
    }
    public function toMapReleve(){
       
        if(auth()->check()){
            $utilisateur = auth()->user();
            if($utilisateur->type->type_util=='Admin'){ $action='Admin/map_releve';}
            else{ $action='Utilisateur/map_releve';}
            $checkreleve=DB::table('mise_a_jour')->select('etat')->where('domaine','releve')->first();
            $checkinfra=DB::table('mise_a_jour')->select('etat')->where('domaine','infra_releve')->first();
            $checkop=DB::table('mise_a_jour')->select('etat')->where('domaine','operateur_releve')->first();
            $checktech=DB::table('mise_a_jour')->select('etat')->where('domaine','technologie_releve')->first();
            $opinfra=$checkop->etat;
            // dd($checkreleve->etat);
           
            if($checkreleve->etat==1 || $checkop->etat==1 || $checktech->etat==1){
                $releve = new Releve();
                $liste_op = $releve->get_All();
                DB::table('mise_a_jour')->where("domaine",'=','releve')->update([
                    "domaine"=>'releve',
                    "etat"=>'0'
                ]);

                $liste_tech=$releve->get_All_Tech();
                DB::table('mise_a_jour')->where("domaine",'=','operateur_releve')->update([
                    "domaine"=>'operateur_releve',
                    "etat"=>'0'
                ]);
                DB::table('mise_a_jour')->where("domaine",'=','technologie_releve')->update([
                    "domaine"=>'technologie_releve',
                    "etat"=>'0'
                ]);
            }
            else{
                $liste_op =Operateur::where('operateur','!=','Non defini')->get();
                $liste_tech=Technologie::where('generation','!=','Non defini')->get();
            }
            if( $checkinfra->etat==1 || $opinfra==1){
                $infra=new Infra();
                $infra->all_to_geoSon();
                DB::table('mise_a_jour')->where("domaine",'=','infra_releve')->update([
                    "domaine"=>'infra_releve',
                    "etat"=>'0'
                ]);
            }
            $page='releve';
            return view ($action,compact('page','utilisateur','liste_op','liste_tech'));
        }
        else{
            return redirect()->route('login');
        }
    }

    public function liste(){
        if(auth()->check()){
            $utilisateur = auth()->user();
            if($utilisateur->type->type_util=='Admin'){ $action='Admin/liste_releve';}
            else{ $action='Utilisateur/liste_releve';}

            $liste=Releve::select('id_upload','date_upload','id_operateur','description')
            ->groupBy('id_upload','date_upload','id_operateur','description')
            ->get();
            $page='releve';
            return view ($action,compact('page','utilisateur','liste'));

        }
        else{
            return redirect()->route('login');
        }
    }

    public function delete($id_rel){
        if(auth()->check()){
            Releve::where('id_upload','=',$id_rel)->delete();
            DB::table('mise_a_jour')->where("domaine",'=','releve')->update([
                "domaine"=>'releve',
                "etat"=>'1'
            ]);
            return redirect()->route('admin.releve.liste');
        }
        else{
            return redirect()->route('login');
        }
    }


}
