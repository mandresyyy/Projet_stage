<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class Releve extends Model
{
    use HasFactory;
    protected $table = 'releve';
    public $timestamps = false;
    protected $fillable = ['id','id_upload','date_upload','description','date_releve','longitude','latitude','id_operateur','operateur_capter','tech','level','speed','altitude','couleur'];

    public function operateur(){
        return $this->belongsTo(Operateur::class,'id_operateur');
    }

    
    public function listefichier($operateur)
    {
        // Remplacez 'dossier' par le chemin du dossier que vous souhaitez lister
        $directory = public_path('storage/releve/'.$operateur);
        $liste = [];
        if (File::isDirectory($directory)) {
            $files = File::files($directory);
            // $liste = [];
    
            foreach ($files as $file) {
                $liste[] = $file->getFilename();
            }
            return $liste;
            
        } else {
            
            // dd("Tsy dossier");
            return $liste;
        }
    }

    public function get_Color($level){
        if($level<=-50 && $level>-71){
            return 'orange';
        }
        else if($level<=-71 && $level>=-81){ return 'yellow'; }
        else if($level<-81 && $level>=-91){ return 'green' ;}
        else if($level<-91 && $level>=-101){ return '#81dae6';}
        else if($level<-101 && $level>=-111){ return '#0374b0';}
        else if($level<-111 && $level>=-120){return '#a3b7c2';}
        else if(($level<-120 && $level>=-200)){return '#0f1112';}
    }
    
        public function getReleveFichier($operateur,$fichier)
        {
            // dd("ato");
            $filePath = public_path('storage/releve/'.$operateur.'/'.$fichier);
            
            if (file_exists($filePath)) {
                
                $file = fopen($filePath, 'r');
                $data=[];
                if ($file) {
                    $header = null; // Initialisez l'en-tête à null
                    while (($line = fgets($file)) !== false) {
                        $fields = explode("\t", trim($line)); // Divisez la ligne en utilisant des tabulations comme délimiteur
                        
                        if (!$header) {
                            // Si l'en-tête n'est pas encore défini, définissez-le à partir de la première ligne
                            $header = $fields;
                            $header[]='couleur';
                            // dd($header);
                        } else {
                            if (count($fields) != count($header)) {
                                // Si la longueur est différente, remplissez les valeurs manquantes avec des valeurs par défaut
                                $fields = array_pad($fields, count($header), '-');
                            }
                            
                            // Sinon, combinez les champs avec l'en-tête pour créer un tableau associatif
                            $data[] = array_combine($header, $fields);
                            $indice=count($data);
                            $data[$indice-1]['couleur']=$this->get_Color($data[$indice-1]['Level']);
                            // dd($data);
                        }
                    }
                    // dd($data);
                    fclose($file);
                }
                return $data;
              
            } else {
                dd('Tsy fichier');
            }
        }

        public function getAllReleve(){
            $operateur=Operateur::where('operateur','!=','Non defini')->get();
            $tableau_releve=[];
            foreach($operateur as $op){
                $listefichier=$this->listefichier($op->operateur);
                $data = [];
                if (count($listefichier) != 0) {
                    // dd($listefichier)
                    foreach ($listefichier as $fichier) {
                        $data[] = $this->getReleveFichier($op->operateur, $fichier);
                        // $jsonData = json_encode($data);
                    }
                }
                $tableau_releve[$op->operateur] = $data;
                
            }
            // dd($tableau_releve['Orange']);
           
            return $tableau_releve;
        }
        public function saveReleve($filePath)
        {

            // dd($filePath);
            if (file_exists($filePath)) {
                
                $file = fopen($filePath, 'r');
                
                if ($file) {
                    $header = null; // Initialisez l'en-tête à null
                    $erreur=null;
                    $index=1;
                    $actuel=Carbon::now()->addHours(3);
                    $tab_valide=['NO_COVERAGE','Aucun_service','Urgences_seulement',$this->operateur->operateur];
                    DB::beginTransaction();

                    while (($line = fgets($file)) !== false) {
                        $fields = explode("\t", trim($line)); // Divisez la ligne en utilisant des tabulations comme délimiteur
                        $data=[];
                        if (!$header) {
                            // Si l'en-tête n'est pas encore défini, définissez-le à partir de la première ligne
                            $header = $fields;
                            $header[]='couleur';
                            // dd($header);
                        } else {
                            if (count($fields) != count($header)) {
                                // Si la longueur est différente, remplissez les valeurs manquantes avec des valeurs par défaut
                                $fields = array_pad($fields, count($header), '-');
                            }
                            
                            // Sinon, combinez les champs avec l'en-tête pour créer un tableau associatif
                            
                            $data[] = array_combine($header, $fields);
                            $indice=count($data);
                            $data[$indice-1]['couleur']=$this->get_Color($data[$indice-1]['Level']);
                            $tmp=$data[0]['Timestamp'];
                            $operateur_a_verifier=[$data[0]['Operatorname']];
                            $checkoperateur=array_diff($operateur_a_verifier,$tab_valide);
                            // dd(count($checkoperateur));
                            if( count($checkoperateur)!=0){
                                $erreur="Operateur non identique à l'operateur sélectionné:".$index;
                                DB::rollBack();
                                break;
                            }

                            $date=explode('_',$tmp);
                            $datef=explode('.',$date[0]);
                            $tempsf=explode('.',$date[1]);
                            $timestamps=$datef[0].'-'.$datef[1].'-'.$datef[2].' '.$tempsf[0].':'.$tempsf[1].':'.$tempsf[2];
                            
                            $couleur=$this->get_Color($data[0]['Level']);
                            // if($couleur==null){
                            //     dd($data[0]['Level']);
                            // }
                        Releve::create([
                            'description' => $this->description,
                            'date_releve' =>  $timestamps,
                            'longitude' => $data[0]['Longitude'],
                            'latitude' => $data[0]['Latitude'],
                            'id_operateur' => $this->id_operateur,
                            'operateur_capter'=>$data[0]['Operatorname'],
                            'tech' => $data[0]['NetworkTech'],
                            'level' =>  $data[0]['Level'],
                            'speed' =>  $data[0]['Speed'],
                            'altitude' =>  $data[0]['Altitude'],
                            'couleur' => $couleur,
                            'id_upload'=>'UP-'.$actuel
                        ]);
                        }
                        $index++;
                    }
                    // dd($data);
                    fclose($file);
                    DB::commit();
                }
                return $erreur;
              
            } else {
                dd('Tsy fichier');
            }
        }

        public function get_All(){
            $listeOperateur=Operateur::where('operateur','!=','Non defini')->get();
            foreach($listeOperateur as $operateur){
                $filename=$operateur->operateur;
                $L_releve=Releve::where('id_operateur','=',$operateur->id)->get();
                $geojson=["type"=>"FeatureCollection","features"=>[]];


                foreach($L_releve as $releve){
                            //  dd($releve);
                $feature=[
                    "type"=>"Feature",
                    "geometry"=>[
                        "type"=>"Point",
                        "coordinates"=> [$releve->longitude,$releve->latitude]
                    ],
                    "properties"=>[
                        'date_upload'=>$releve->date_upload,
                        'description'=>$releve->description,
                        'date_releve'=>$releve->date_releve,
                        'longitude'=>$releve->longitude,
                        'latitude'=>$releve->latitude,
                        'operateur'=>$releve->operateur->operateur,
                        'capter'=>$releve->operateur_capter,
                        'technologie'=>$releve->tech,
                        'level'=>$releve->level,
                        'speed'=>$releve->speed,
                        'altitude'=>$releve->altitude,
                        'couleur'=>$releve->couleur
                    ]
                    ];

                    $geojson["features"][]=$feature;
                          
                }
                $geojson=json_encode($geojson);
                $filename=public_path('geojson') . '/releve/' .$filename.".geojson";
                file_put_contents($filename,$geojson);
            }

            
            return $listeOperateur;
        }
        public function get_All_Tech(){
            $listeTech=Technologie::where('generation','!=','Non defini')->get();
            foreach($listeTech as $tech){
                $filename=$tech->generation;
                $L_releve=Releve::where('tech','=',$tech->generation)->get();
                $geojson=["type"=>"FeatureCollection","features"=>[]];


                foreach($L_releve as $releve){
                            //  dd($releve);
                $feature=[
                    "type"=>"Feature",
                    "geometry"=>[
                        "type"=>"Point",
                        "coordinates"=> [$releve->longitude,$releve->latitude]
                    ],
                    "properties"=>[
                        'date_upload'=>$releve->date_upload,
                        'description'=>$releve->description,
                        'date_releve'=>$releve->date_releve,
                        'longitude'=>$releve->longitude,
                        'latitude'=>$releve->latitude,
                        'operateur'=>$releve->operateur->operateur,
                        'capter'=>$releve->operateur_capter,
                        'technologie'=>$releve->tech,
                        'level'=>$releve->level,
                        'speed'=>$releve->speed,
                        'altitude'=>$releve->altitude,
                        'couleur'=>$releve->couleur
                    ]
                    ];

                    $geojson["features"][]=$feature;
                          
                }
                $geojson=json_encode($geojson);
                $filename=public_path('geojson') . '/releve/' .$filename.".geojson";
                file_put_contents($filename,$geojson);
            }
            
            
            return $listeTech;
        }

        public function get_date_releve(){
            $date=Releve::where('id_upload','=',$this->id_upload)->first();
            return $date->date_releve;
        }
}
