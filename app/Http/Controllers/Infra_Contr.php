<?php

namespace App\Http\Controllers;

use App\Models\Infra;
use Illuminate\Http\Request;
use App\Models\Operateur;
use App\Models\Source_energie;
use App\Models\Technologie;
use App\Models\Type_site;
use App\Models\Commune;
use App\Models\Infra_collocation;
use App\Models\Infra_source;
use App\Models\Infra_technologie;
use App\Models\Proprietaire_site;
use Illuminate\Support\Facades\DB;
use App\Models\Logs;
use App\Models\Type_action;
use Exception;

class Infra_Contr extends Controller
{
    public function liste()
    {
        if (auth()->check()) {
            // $tempsDebut = microtime(true);
            $utilisateur = auth()->user();
            $liste = Infra::select('id','id_operateur','nom_site','id_type_site','id_proprietaire','id_commune','en_service','date_upload')
            ->with('technologies','operateur','type_site','proprietaire','commune')->get();
            // dd($liste);
            $action=new Logs();
            $action->id_utilisateur=$utilisateur->id;
            $idtypeaction=Type_action::where('action','=','liste')->pluck('id')->first();
            $action->id_type_action=$idtypeaction;
            $action->detail='Liste des Infrastructures';
            $action->newLogs();
            $page='infra';
            // $tempsFin = microtime(true);
            // dd($tempsFin - $tempsDebut);
            return view('Admin/liste_infra', compact('page','utilisateur', 'liste'));
        } else {
            return redirect()->route('login');
        }
    }
    public function info($idUpdate){
        if (auth()->check()) {
            $utilisateur = auth()->user();
            $infra=Infra::find($idUpdate);
            $listeinfratech=$infra->get_technologie();
            $listeinfrasrc=$infra->get_source();
            $listeop=Operateur::where('operateur','!=','Non defini')->get();
            $listetype=Type_site::where('type','!=','Non defini')->get();
            $listesrc=Source_energie::where('source','!=','Non defini')->get();
            $listetech=Technologie::all();
            $listeop1=Operateur::all();
           
            // dd($listecoloc);
            $action=new Logs();
        $action->id_utilisateur=auth()->user()->id;
        $idtypeaction=Type_action::where('action','=','Recherche')->pluck('id')->first();
        $action->id_type_action=$idtypeaction;
        $action->detail='Info infrastructure :'.$infra->code_site.' '.$infra->nom_site;
        $action->newLogs();
        $page='infra';
            return view('Admin/info_infra', compact('page','utilisateur','listetech','listesrc','infra','listeop','listetype','listeinfrasrc','listeinfratech','listeop1'));
        } else {
            return redirect()->route('login');
        }
    }

    public function auto(Request $request){
        $term = $request->input('term');

    // Effectuez une recherche en fonction du terme et renvoyez les suggestions au format JSON
    $suggestions = Commune::where('commune', 'like', $term.'%')->pluck('commune');

    return response()->json($suggestions);
    }

    public function get_commune(Request $request){
        $code_c=$request->input('commune');
        $com=Commune::where('commune', 'like', $code_c.'%')->first();
        // if($com==null){
        //     $com=$code_c;
        // }
        $resp=$com->code_c;
        return response()->json($resp);

    }
    public function form(){
        if (auth()->check()) {
            $utilisateur = auth()->user();
           $listeop=Operateur::all();
           $listetype=Type_site::all();
           $listesource=Source_energie::all();
           $listetech=Technologie::all();
           $page='infra';
            return view('Admin/new_infra', compact('page','utilisateur','listeop','listetype','listesource','listetech'));
        } else {
            return redirect()->route('login');
        }
    } 

    public function suggest_proprio(Request $request){
        $prop=$request->input('prop');
        $proprio=Proprietaire_site::where('proprietaire', 'like', $prop.'%')->pluck('proprietaire');
        return response()->json($proprio);
    }
    public function display(Request $request){
        $infr=new Infra();
        
        $infr->code_site=$request->input('code_site');
        $infr->nom_site=$request->input('nom_site');

        $op=Operateur::find($request->input('operateur'));
        $infr->id_operateur=$op->id;
        $proprio=new Proprietaire_site();
        if($request->input('proprio')!=''){
           
            $proprio->proprietaire=$request->input('proprio');
        }
        else{
            $proprio->proprietaire="Non defini";
        }
       
        if($request->input('commune')!=''){
            $commune=Commune::where('commune','=',$request->input('commune'))->first();
            if($commune==null){
                $commune = new Commune();
                $commune->commune ='Non defini';
            }
            $infr->id_commune=$commune->id;
        }
        else{
            $commune=new Commune();
            $commune->commune='Non defini';
        }

      
        $infr->annee_mise_service=$request->input('annee');
       

        $infr->latitude=str_replace(',','.',$request->input('latitude'));
        $infr->longitude=str_replace(',','.',$request->input('longitude'));

        $type=Type_site::find($request->input('type'));
        $infr->id_type_site=$type->id;

        $mut=$request->input('mutualise');
            if($mut==0){
                $mut='NON';
            }
            else{
                $mut='OUI';
            }
        $infr->mutualise= $mut;
       
        $colocataire=$request->input('coloc');
        $infr->coloc= $colocataire;
      
        
       

        $listetech=[];
        $techno='';
        if($request->input('tech')!=''){
            $technol=$request->input('tech');
           
            foreach($technol as $l){
                $tech=new Technologie();
                $tech=Technologie::find($l);
                $listetech[]=$tech;
                $techno=$techno.' '.$tech->generation;
            }
        }
       

        if($request->input('info')!=''){
            $infr->technologie_generation=$request->input('info');
        }
        else{
            $infr->technologie_generation='';
        }

        $listesource=[];
        $source='';
        if($request->input('source')!=''){
        $listesource=explode('-', $request->input('source'));
            
            foreach($listesource as $s){
                $source=$source.' '.$s;
            }
            // dd($source);
    
        }

        $infr->hauteur= $request->input('hauteur');
        $infr->largeur_canaux= $request->input('largeur');
        
  
        $data = array(
            "code_site" => $infr->code_site,
            "nom_site" => $infr->nom_site,
            "operateur" => $op->operateur,
            "proprietaire" => $proprio->proprietaire,
            "annee" =>  $infr->annee_mise_service,
            "commune" => $commune->commune,
            "longitude" => $infr->longitude,
            "latitude" => $infr->latitude,
            "type_site" => $type->type,
            "mutualise" => $infr->mutualise,
            "coloc" =>  $colocataire,
            "source"=> $source,
            "techno"=>$techno,
            "info_techno"=>$infr->technologie_generation,
            "largeur_canaux"=> $infr->largeur_canaux,
            "hauteur"=>$infr->hauteur
        );
        
        // Convertissez le tableau en JSON
        $json = json_encode($data);
        // dd($json);
        
        session(['infra'=>$infr]);
        session(['techno'=>$listetech]);
        session(['source'=>$listesource]);
        session(['proprio'=>$proprio]);

        return ($json);

    }
    public function save(Request $request){
        $request->validate([
            'code_site' => 'required',
            'nom_site' => 'required',
            'proprio' => 'required',
            'commune' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'tech' => 'required',
        ]);
        $infra=session('infra');
            if($infra->id_commune==null){
                return back()->withErrors(['Erreur_commune'=>'Commune non existant.'])->withInput();

            }
            if($infra->mutualise=='NON' && $infra->coloc!=''){
                
                return back()->withErrors(['Erreur'=>'Presence de colocataire dans une infrastructure non mutualise.'])->withInput();
    
            }
        $listesrc=session('source');
        $listetech=session('techno');
        $proprio=session('proprio');
        DB::beginTransaction();
        $infra->id_proprietaire=$proprio->getId();    

        // dd($listetech);
       
        $infra->save();

        $id=$infra->id;
      
      
        // insert infra source
        foreach($listesrc as $s){
                $src=Source_energie::where('source','=',$s)->first();
                if($src==null){
                    $src=new Source_energie();
                    $id = DB::table('source_energie')->insertGetId(
                        ['source' => $s],
                    );
                    $src->id=$id;
                    $src->source=$s;
                }
                DB::table('infra_source')->insert(
                    [
                        'id_infra' => $id,
                        'id_source' => $src->id
                    ]
                );
            }

        // insert infra tech
        foreach ($listetech as $gen) {
            DB::table('infra_technologie')->insert(
                [
                    'id_infra' => $id,
                    'id_technologie' => $gen->id
                ]
            );
        }

      
        $action=new Logs();
        $action->id_utilisateur=auth()->user()->id;
        $idtypeaction=Type_action::where('action','=','insertion')->pluck('id')->first();
        $action->id_type_action=$idtypeaction;
        $action->detail='Ajout infrastructure'.$infra->nom_site.'('.$infra->code_site.')';
        $action->newLogs();
        DB::table('mise_a_jour')->where("domaine",'=','infra')->update([
            "domaine"=>'infra',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_releve')->update([
            "domaine"=>'infra_releve',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_source')->update([
            "domaine"=>'infra_source',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_tech')->update([
            "domaine"=>'infra_tech',
            "etat"=>'1'
        ]);
        DB::commit();
        session()->forget('infra');
        session()->forget('source');
        session()->forget('techno');
        session()->forget('proprio');
        session()->forget('coloc');
        return back()->with('success','Infrastructure ajoutée avec succès');
    }

    public function update(Request $request){
        $request->validate([
            'code_site' => 'required',
            'nom_site' => 'required',
            'proprio' => 'required',
            'commune' => 'required',
        ]);

        $infr=Infra::find($request->input('id_infra'));
        $infr->code_site=$request->input('code_site');
        $infr->nom_site=$request->input('nom_site');
        $infr->id_operateur=$request->input('operateur');

        $proprio=new Proprietaire_site();
        $proprio->proprietaire=$request->input('proprio');
        $infr->id_proprietaire=$proprio->getId(); 

        $commune=Commune::where('commune','=',$request->input('commune'))->first();
        $infr->id_commune=$commune->id;

        $infr->annee_mise_service=$request->input('annee');
        $infr->id_type_site=$request->input('type');

        
        if($request->input('source')!=''){
        $sourc=explode('-', $request->input('source'));
        }
        else{
            $sourc=[];
        }
        $mut=$request->input('mutualise');
            if($mut==0){
                $mut='NON';
            }
            else{
                $mut='OUI';
            }
        $infr->mutualise= $mut;
        if($request->input('mutualise')==0 && $request->input('coloc')!=''){
            return back()->withErrors(['Erreur'=>'Presence de colocataire dans une infrastructure non mutualise.']);
        }
        $infr->coloc=$request->input('coloc');
        
       

        DB::beginTransaction();
        try{
            Infra_source::where('id_infra',$infr->id)->delete();
            $infr->save();
            foreach ($sourc as $source) {
                $energie=new Source_energie();
                $energie->source=$source;
                $idsource=$energie->getOrcreateID();
                DB::table('infra_source')->insert(
                    [
                        'id_infra' => $infr->id,
                        'id_source' => $idsource
                    ]
                );
            }
           


        }
        catch(Exception $e){
            DB::rollBack();
            dd($e);
            return back()->withErrors(['Erreur'=>'La modification a échouée.']);
        }
        $action=new Logs();
        $action->id_utilisateur=auth()->user()->id;
        $idtypeaction=Type_action::where('action','=','modification')->pluck('id')->first();
        $action->id_type_action=$idtypeaction;
        $action->detail='Modification infrastructure'.$infr->nom_site.'('.$infr->code_site.')';
        $action->newLogs();
        DB::table('mise_a_jour')->where("domaine",'=','infra')->update([
            "domaine"=>'infra',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_releve')->update([
            "domaine"=>'infra_releve',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_source')->update([
            "domaine"=>'infra_source',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_tech')->update([
            "domaine"=>'infra_tech',
            "etat"=>'1'
        ]);
        DB::commit();
        return redirect()->back()->with('success', 'Modification éffectuée');
    }

    public function update_technique(Request $request){
        $request->validate([
            'id_infra' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $infr=Infra::find($request->input('id_infra'));

        $infr->latitude=$request->input('latitude');
        $infr->longitude=$request->input('longitude');
        $infr->hauteur= $request->input('hauteur');
        $infr->largeur_canaux= $request->input('largeur');
        $infr->technologie_generation=$request->input('info');

        $listetech=[];
       
        if($request->input('tech')!=''){
            $technol=$request->input('tech');    
        }
        else{
            $technol=[];
        }
        DB::beginTransaction();
        try{
            Infra_technologie::where('id_infra',$infr->id)->delete();
            $infr->save();
            foreach ($technol as $tech) {
                DB::table('infra_technologie')->insert(
                    [
                        'id_infra' => $infr->id,
                        'id_technologie' => $tech
                    ]
                );
            }
        }
        catch(Exception $e){
            DB::rollBack();
            return back()->withErrors(['Erreur'=>'La modification a échouée.']);
        }
        $action=new Logs();
        $action->id_utilisateur=auth()->user()->id;
        $idtypeaction=Type_action::where('action','=','modification')->pluck('id')->first();
        $action->id_type_action=$idtypeaction;
        $action->detail='Modification information technique '.$infr->nom_site.'('.$infr->code_site.')';
        $action->newLogs();
        DB::table('mise_a_jour')->where("domaine",'=','infra')->update([
            "domaine"=>'infra',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_releve')->update([
            "domaine"=>'infra_releve',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_source')->update([
            "domaine"=>'infra_source',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_tech')->update([
            "domaine"=>'infra_tech',
            "etat"=>'1'
        ]);
        DB::commit();
        return redirect()->back()->with('success', 'Modification éffectuée');
    }

    public function changer_etat($id){
        $infra=Infra::find($id);
        if($infra->en_service==true){
            $infra->en_service=false;
        }
        else{
            $infra->en_service=true;
        }
        $infra->save();
        $action=new Logs();
        $action->id_utilisateur=auth()->user()->id;
        $idtypeaction=Type_action::where('action','=','modification')->pluck('id')->first();
        $action->id_type_action=$idtypeaction;
        $action->detail='Modification etat infrastructure '.$infra->nom_site.'('.$infra->code_site.')';
        $action->newLogs();
        DB::table('mise_a_jour')->where("domaine",'=','infra')->update([
            "domaine"=>'infra',
            "etat"=>'1'
        ]);
        DB::table('mise_a_jour')->where("domaine",'=','infra_releve')->update([
            "domaine"=>'infra_releve',
            "etat"=>'1'
        ]);
        return back();
    }

    public function search(Request $request){
        session(['search_infra'=>$request->input('search')]);
        $action=new Logs();
        $action->id_utilisateur=auth()->user()->id;
        $idtypeaction=Type_action::where('action','=','Recherche')->pluck('id')->first();
        $action->id_type_action=$idtypeaction;
        $action->detail='Recherche infrastructure :'.$request->input('search');
        $action->newLogs();
        if (auth()->check()) {
            
            $page='infra';
                $utilisateur = auth()->user();
                $list=DB::table('v_all_infra_info')
                        ->whereRaw("concat(nom_site,' ',operateur,' ',type,' ',proprietaire,' ',commune,' ',region,' ',etat) 
                        like ?",['%'.session('search_infra').'%'])
                        ->pluck('id');
            
                    $liste=Infra::whereIn('id',$list)->paginate(20);
                return view('Admin/liste_infra', compact('page','utilisateur', 'liste'));
            
           
        }
        else {
            return redirect()->route('login');
        }
        
       
    }
    public function formupload(){
        if (auth()->check()) {
            $utilisateur = auth()->user();
            $page='infra';
            $operateur=Operateur::where('operateur','!=','Non defini')->get();
            return view('Admin/Upload', compact('page','utilisateur','operateur'));      
    }
    else {
        return redirect()->route('login');
    }
    
    }

    public function delete($id){
        if (auth()->check()) {
            $action=new Logs();
            $action->id_utilisateur=auth()->user()->id;
            $infra=Infra::find($id);
            $idtypeaction=Type_action::where('action','=','suppression')->pluck('id')->first();
            $action->id_type_action=$idtypeaction;
            $action->detail='Suppression infra '.$infra->nom_site.'('.$infra->code_site.')';
            $action->newLogs();
            Infra::destroy($id);
            return back()->with('delete','Infra supprimer');      
    }
    else {
        return redirect()->route('login');
    }
    }
}

