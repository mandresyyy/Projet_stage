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
            dd("alava");
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
        $res = Type_site::where('type', '=', $data)->first();
        if ($res === null) {
            $res = Type_site::where('type', '=', 'Non defini')->first();
        }
        return $res->id;
    }

    public function source_energie($data)
    {
        $tab = explode(',', $data);
        $liste = [];
        if(count($tab)==0){
            $tab[0]='Non defini';
        }
        foreach ($tab as $t) {
            if ($t == 'GE') {
                $t = 'Groupe electrogene';
            }
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
        return $liste;
    }

    public function get_colloc($data)
    {
        if ($data == '') {
            $res = Operateur::where('operateur', '=', 'Non defini')->first();
            return $res->id;
        } else {
            $res = Operateur::where('operateur', '=', $data)->first();
            if ($res === null) {
                return 0;
            }
            return $res->id;
        }
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
            'fichier_csv' => 'required|mimes:csv',
           
        ]);


        if ($request->hasFile('fichier_csv')) {
            $file = $request->file('fichier_csv');
            $filePath = $file->getPathname();
            $extension = $file->getClientOriginalExtension();
            // dd($extension);
            if ($extension != 'csv') {
                return back()->withErrors(['erreur' => 'Veuillez importer un fichier de type csv']);
            } else {
                // dd("mety");
                $delimiter = ','; // Le séparateur utilisé dans le fichier CSV
                $index = 0;
                $premier = true;
                if (($handle = fopen($filePath, 'r')) !== false) {
                    DB::beginTransaction();
                    while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                       
                        $index++;
                        if ($premier) {
                            $premier = false;
                            if (!$this->check_first($data)) {
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'Le modele de fichier est invalide']);
                            }
                        } else {
                            $id_op = $this->get_operateur($data[1]);
                            if ($id_op == 0) {
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'L\operateur n\'existe pas a la ligne' . $index]);
                            }
                            $liste_tec = $this->get_techno($data[5]);
                            if (count($liste_tec) == 0) {
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'Technologie non existant a la ligne' . $index]);
                            }
                            $type_site = $this->get_type_site($data[6]);
                            $liste_source = $this->source_energie($data[7]);
                            if ($data[9] == '') {
                                $data[9] = 'NON';
                            } //mutualise
                            $colloc = $this->get_colloc($data[10]);
                            if ($colloc == 0) {
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'Colocataire non existant a la ligne' . $index]);
                            }
                            $proprio = $this->get_proprio($data[8]);
                            $commune = $this->get_commune($data[15]);
                            if ($commune == 0) {
                                DB::rollBack();
                                return back()->withErrors(['erreur' => 'Veuillez verifier le code commune a la ligne' . $index]);
                            }
                            for ($x = 0; $x < count($data); $x++) {
                                if ($x == 4 || $x == 16) {
                                    if ($data[$x] == '') {
                                        $data[$x] = 'Non defini';
                                    }
                                }
                                if ($x == 13 || $x == 14) {
                                    if ($data[$x] == '') {
                                        $data[$x] = NULL;
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
                                    'id_colloc' => $colloc,
                                    'latitude' => $data[11],
                                    'longitude' => $data[12],
                                    'hauteur' => $data[13],
                                    'largeur_canaux' => $data[14],
                                    'id_commune' => $commune,
                                    'annee_mise_service' => $data[16]
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
                    DB::commit();
                    fclose($handle); // Close the file after reading
                }
                return back()->with(['upload' => 'Upload effectué']);
            }
        }
    }
}
