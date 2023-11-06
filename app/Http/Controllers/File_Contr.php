<?php

namespace App\Http\Controllers;

use App\Models\Commune;
use App\Models\Operateur;
use App\Models\Proprietaire_site;
use App\Models\Source_energie;
use App\Models\Technologie;
use App\Models\Type_site;
use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\Type_action;
use Illuminate\Support\Facades\DB;
use App\Models\Infra;
use App\Models\Infra_collocation;

class File_Contr extends Controller
{
    public function get_model()
    {
        $path = resource_path('modele.csv');
        $name = 'modele.csv';

        return response()->download($path, $name);
    }

    public function check_first($data)
    {
        if (count($data) != 17) {
            // dd("alava");
            return false;
        } else {
            return true;
        }
    }

    public function get_operateur($op)
    {
        $result = Operateur::where('operateur', '=', $op)->first();
        if ($result === null) {
            return 0;
        }
        return $result->id;
    }

    public function get_techno($data)
    {
        $tab = explode('-', $data);
        $liste = [];
        foreach ($tab as $t) {
            $res = Technologie::where('generation', '=', $t)->first();
            if ($res === null) {
                $liste = [];
                return $liste;
            } else {
                $liste[] = $res->id;
            }
        }
        return $liste;
    }

    public function get_type_site($data)
    {   
        if($data=='COW'){$data = 'Zebu';}
        if($data==''){
             $res = Type_site::where('type', '=', 'Non defini')->first();
        }

        else{
            $res = Type_site::where('type', '=', $data)->first();
            if ($res === null) {
                return 0;
            }
        }
        return $res->id;
    }

    public function source_energie($data)
    {
        // dd($data);
        $data=str_replace('+',',',$data);
        $tab = explode(',', $data);
        // dd($tab);
        $liste = [];
        if($data==''){
            $tab[0]='Non defini';
            // dd(  $tab[0]);
        }
        foreach ($tab as $t) {
            if (trim($t) == 'GE') {
                $t = 'Groupe electrogene';
                // dd('GE');
            }
            // dd($t);
            $res = Source_energie::where('source', '=', $t)->first();
            if ($res === null) {
                $id = DB::table('source_energie')->insertGetId(
                    ['source' => $t],
                );
                $liste[] = $id;
            } else {
                $liste[] = $res->id;
            }
        }
        if (count($tab) == 0) {
            $liste[] = 1;
        }
        // dd('TYsy tao');
        return $liste;
    }

   

    public function get_proprio($data)
    {
        if ($data == '') {
            $res = Proprietaire_site::where('proprietaire', '=', 'Non defini')->first();
            return $res->id;
        } else {
            $res = Proprietaire_site::where('proprietaire', '=', $data)->first();
            if ($res === null) {
                $id = DB::table('proprietaire_site')->insertGetId(
                    ['proprietaire' => $data],
                );
                return $id;
            }
            return $res->id;
        }
    }

    public function get_commune($data)
    {
        $res = Commune::where('code_c', '=', $data)->first();
        if ($res === null) {
            return 0;
        } else {
            return $res->id;
        }
    }

    public function import_csv(Request $request)
    {
        $request->validate([
            'fichier_csv' => 'required',
           
        ]);
        // dd($request->input('action'));


        if ($request->hasFile('fichier_csv')) {
            $file = $request->file('fichier_csv');
            $filePath = $file->getPathname();
            $extension = $file->getClientOriginalExtension();
            // dd($extension);
            if ($extension != 'csv') {
                return back()->withErrors(['erreur' => 'Veuillez importer un fichier de type csv']);
            } else {
                // dd("mety");
                $delimiter = ';'; // Le séparateur utilisé dans le fichier CSV
                $index = 0;
                $premier = true;
                $operateur=$request->input('operateur');
                $action=$request->input('action');
                // dd($action);
                if (($handle = fopen($filePath, 'r')) !== false) {
                    DB::beginTransaction();
                    if($action===null){
                        // dd("miala anh");
                        Infra::where('id_operateur',$operateur)->delete();
                    }
                    while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                       
                        $index++;
                        if ($premier) {
                            $premier = false;
                            if (!$this->check_first($data)) {
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'Le modele de fichier est invalide']);
                            }
                        } else {
                            // dd($data);
                            $id_op = $this->get_operateur($data[1]);
                            // dd($id_op==$operateur);
                            if ($id_op == 0) {
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'L\operateur n\'existe pas a la ligne' . $index]);
                            }
                            if($id_op!=$operateur){
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'L\'operateur n\'est pas identique à celle que vous avez mentionné à la ligne : ' . $index]);
                            }
                            if($data[5]==''){$data[5]='Non defini';}
                            $liste_tec = $this->get_techno($data[5]);
                            if (count($liste_tec) == 0) {
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'Technologie non existant a la ligne' . $index]);
                            }
                            $type_site = $this->get_type_site($data[6]);
                            if($type_site==0){
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'Type de site non reconnu ' . $index.':'.$data[6]]);
                       
                            }
                            $liste_source = $this->source_energie($data[7]);
                            if ($data[9] == '') {
                                $data[9] = 'NON';
                            } //mutualise
                            if($data[9] != 'OUI'){
                                if($data[10]!=''){
                                    DB::rollBack();
                                    return back()->withErrors(['erreur' => 'Presence de colocataire dans un infrastructure non mutualise' . $index]);
                                }
                            }
                            $proprio = $this->get_proprio($data[8]);
                            $commune = $this->get_commune($data[15]);
                            if ($commune == 0) {
                                // DB::rollBack();
                                // return back()->withErrors(['erreur' => 'Veuillez verifier le code commune a la ligne' . $index]);
                                continue;
                            }
                            for ($x = 0; $x < count($data); $x++) {
                                if ($x == 4 || $x == 16) {
                                    if ($data[$x] == '') {
                                        $data[$x] = 'Non defini';
                                    }
                                }
                                if ($x == 13 || $x == 14 || $x == 11 || $x == 12) {
                                    if ($data[$x] == '') {
                                        $data[$x] = NULL;
                                    }
                                    else{
                                        $data[$x]=str_replace(',','.',$data[$x]);
                                        if (!is_numeric($data[$x])){
                                            $data[$x] = NULL; 
                                        }
                                    }
                                }
                            }
                            // insert infra 
                            $id = DB::table('infra')->insertGetId(
                                [
                                    'id_operateur' => $id_op,
                                    'nom_site' => $data[2],
                                    'code_site' => $data[3],
                                    'technologie_generation' => $data[4],
                                    'id_type_site' => $type_site,
                                    'id_proprietaire' => $proprio,
                                    'mutualise' => $data[9],
                                    'latitude' =>  $data[11],
                                    'longitude' => $data[12],
                                    'hauteur' => $data[13],
                                    'largeur_canaux' => $data[14],
                                    'id_commune' => $commune,
                                    'annee_mise_service' => $data[16],
                                    'coloc'=>$data[10]
                                ],
                            );
                            // insert infra source
                            foreach ($liste_source as $source) {
                                DB::table('infra_source')->insert(
                                    [
                                        'id_infra' => $id,
                                        'id_source' => $source
                                    ]
                                );
                            }

                            // insert infra tech
                            foreach ($liste_tec as $gen) {
                                DB::table('infra_technologie')->insert(
                                    [
                                        'id_infra' => $id,
                                        'id_technologie' => $gen
                                    ]
                                );
                            }

                            
                            
                        }
                    }
                $action=new Logs();
                $action->id_utilisateur=auth()->user()->id;
                $idtypeaction=Type_action::where('action','=','insertion')->pluck('id')->first();
                $action->id_type_action=$idtypeaction;
                $action->detail='Import fichier csv '.$request->file('fichier_csv');
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
                    fclose($handle); // Close the file after reading
                }
                return back()->with(['upload' => 'Upload effectué']);
            }
        }
    }
 
    public function export(Request $request)
    {
        $data=$request->session()->get('liste_search');
        // dd( $data);
        $csv="Numero;Operateur;Nom du site;Code site;Technologie;type;Source d'energie;Proprietaire;Mutualise;Colocataire;Coordonne;Hauteur antenne;Largeur des canaux;Commune;District;Region;Annee mise en service\n";
        $num=1;
       
        foreach($data as $ligne){
            $inf=Infra::find($ligne->id);
            // dd($inf);
            $op=$inf->operateur->operateur;
            $liste_tech=$inf->get_technologie();
            $liste_source=$inf->get_source();
            $csv=$csv.$num.";".$op.";".$inf->nom_site.";".$inf->code_site.";";

                foreach($liste_tech as $tech){
                    $csv=$csv.$tech->technologie->generation." ";
                }
                $csv=$csv.";";
            $csv=$csv.$inf->type_site->type.";";
                foreach($liste_source as $src){
                    $csv=$csv.$src->source->source." ";
                }
                $csv=$csv.";";
            
            $csv=$csv.$inf->proprietaire->proprietaire.";";
            $csv=$csv.$inf->mutualise.";";
            $csv=$csv.$inf->coloc.";";
            $csv=$csv.$inf->longitude.",".$inf->latitude.";";
            $csv=$csv.$inf->hauteur.";";
            $csv=$csv.$inf->largeur_canaux.";";
            $csv=$csv.$inf->commune->commune.";";
            $csv=$csv.$inf->commune->district.";";
            $csv=$csv.$inf->commune->region.";";
            $csv=$csv.$inf->annee_mise_service.";";
            $csv=$csv."\n";
            // dd($csv);
            $num++;
        }
        

        // Créer un nom de fichier unique
        $fileName = 'Export_Infra' . date('Y-m-d_H:i:s') . '.csv';


        // Définir les en-têtes de la réponse
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // Renvoyer le fichier CSV en réponse
        return response($csv, 200, $headers);
    }
}
