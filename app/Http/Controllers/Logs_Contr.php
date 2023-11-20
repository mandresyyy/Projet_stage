<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\Type_action;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Utilisateur;


class Logs_Contr extends Controller
{
    public function liste(){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $liste=Logs::orderBy('date','DESC')->paginate(7);
            $action=Type_action::all();
            $page='logs';
            return view ('Admin/logs_user',compact('page','utilisateur','liste','action'));
        }
        else{
            return redirect()->route('login');
        }
        
    }

    public function search(Request $request){
        if(auth()->check()){
            $utilisateur=auth()->user();
            $idutil=0;
            $action=Type_action::all();
            
            if($request->input('action')=='' && $request->input('date')=='' && $request->input('utilisateur')==''){
                return redirect()->route('logs');
            }
            if($request->input('utilisateur')!=''){
                $idutil=Utilisateur::orWhere('nom','=',$request->input('utilisateur'))
                    ->orWhere('prenom','=',$request->input('utilisateur'))
                    ->orWhere('email','=',$request->input('utilisateur'))
                    ->orWhere('matricule','=',$request->input('utilisateur'))
                    ->pluck('id')->first();
            }
            if($request->input('action')!='' && $request->input('date')=='' && $idutil==0){
                $liste=Logs::where('id_type_action','=',$request->input('action'))
                        ->paginate(7);
            }
            else if($request->input('action')=='' && $request->input('date')!='' && $idutil==0){
                $liste=Logs::where('date','like','%'.$request->input('date').'%')
                        
                        ->paginate(7);
            }
            else if($request->input('action')=='' && $request->input('date')=='' && $idutil!=0){
                $liste=Logs::where('id_utilisateur','=',$idutil)
                        ->paginate(7);
            }

            else if($request->input('action')!='' && $request->input('date')!='' && $idutil==0){
                $liste=Logs::where('id_type_action','=',$request->input('action'))
                        ->where('date','like','%'.$request->input('date').'%')
                        ->paginate(7);
            }
            else if($request->input('action')!='' && $request->input('date')=='' && $idutil!=0){
                $liste=Logs::where('id_type_action','=',$request->input('action'))
                        
                        ->where('id_utilisateur','=',$idutil)
                        ->paginate(7);
            }
            else if($request->input('action')=='' && $request->input('date')!='' && $idutil!=0){
                $liste=Logs::where('date','like','%'.$request->input('date').'%')
                        ->where('id_utilisateur','=',$idutil)
                        ->paginate(7);
            }
           
           else if($request->input('action')!='' && $request->input('date')!='' && $idutil!=0){
                $liste=Logs::where('id_type_action','=',$request->input('action'))
                        ->where('date','like','%'.$request->input('date').'%')
                        ->where('id_utilisateur','=',$idutil)
                        ->paginate(7);
            }
            $page='logs';
            return view ('Admin/logs_user',compact('page','utilisateur','liste','action'));
        }
        else{
            return redirect()->route('login');
        }
    }
   
        
        
       
    

}
