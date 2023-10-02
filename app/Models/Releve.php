<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Releve extends Model
{
    use HasFactory;

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
        else if($level<-71 && $level>=-81){ return 'yellow'; }
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
}
