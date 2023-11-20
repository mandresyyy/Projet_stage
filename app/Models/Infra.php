<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Infra extends Model
{
    use HasFactory;
    protected $table = 'infra';
    public $timestamps = false;
    protected $fillable = ['id','id_operateur','nom_site','code_site','technologie_generation','id_type_site',
    'id_proprietaire','mutualise','latitude','longitude','hauteur',
    'largeur_canaux','id_commune','annee_mise_service','date_upload','en_service','coloc'];

    public function operateur(){
        return $this->belongsTo(Operateur::class,'id_operateur');
    }

    public function type_site(){
        return $this->belongsTo(Type_site::class,'id_type_site');
    }

    public function proprietaire(){
        return $this->belongsTo(Proprietaire_site::class,'id_proprietaire');
    }

    

    public function commune(){
        return $this->belongsTo(Commune::class,'id_commune');

    }
    public function get_technologie(){
        $liste=Infra_technologie::where('id_infra','=',$this->id)->get();
        return $liste;
    }

    public function get_source(){
        $liste=Infra_source::where('id_infra','=',$this->id)->get();
        // dd($liste);
        return $liste;
    }
    public function technologies() {
        return $this->belongsToMany(Technologie::class, 'infra_technologie', 'id_infra', 'id_technologie');
    }
    public function sources() {
        return $this->belongsToMany(Source_energie::class, 'infra_source', 'id_infra', 'id_source');
    }
   

    public function to_geoSon($op, $region, $tech, $type, $source, $mutualise, $user)
    {   
        // $tempsDebut = microtime(true);
        if (count($op) == 0 && count($region) == 0 && count($tech) == 0 && count($type) == 0 && count($source) == 0 && $mutualise == 'tous') {
            $resultats = collect();
        } else {
                $resultats = V_filtre_infra::with(['infra','infra.type_site' ,'infra.operateur', 'infra.commune', 'infra.technologies', 'infra.proprietaire', 'infra.sources'])
                ->select('id')
                ->where(function ($query) use ($op) {
                    foreach ($op as $oper)
                        $query->orwhere('id_operateur', $oper);
                })
                ->where(function ($query) use ($tech) {
                    foreach ($tech as $tec)
                        $query->orwhere('id_technologie', $tec);
                })
                ->where(function ($query) use ($region) {
                    foreach ($region as $reg)
                        $query->orwhere('code_r', $reg);
                })
                ->where(function ($query) use ($type) {
                    foreach ($type as $t)
                        $query->orwhere('id_type_site', $t);
                })
                ->where(function ($query) use ($source) {
                    foreach ($source as $t)
                        $query->orwhere('id_source', $t);
                })
                ->where('en_service', '=', 1)
                ->groupBy('id');

                if($mutualise!='tous'){
                    $resultats= $resultats->where('mutualise', '=', $mutualise);
                }
                $resultats= $resultats->get();
            
        }
      
        $geojson=["type"=>"FeatureCollection","features"=>[]];

        $feature = [];
        foreach($resultats as $marqueur){
           $infra=$marqueur->infra;
           $oper=$infra->operateur;
           $commune=$infra->commune;
           $type_site=$infra->type_site->type;
           $proprietaire=$infra->proprietaire->proprietaire;
           $liste_tech=$infra->technologies->pluck('generation')->toArray();
           $liste_src=$infra->sources->pluck('source')->toArray();
            $feature=[
                "type"=>"Feature",
                "geometry"=>[
                    "type"=>"Point",
                    "coordinates"=> [$infra->longitude, $infra->latitude]
                ],
                "properties"=>[
                    "couleur"=> $oper->couleur,
                    "operateur"=>$oper->operateur,
                    "logo"=> $oper->logo,
                    "nom_site"=>$infra->nom_site,
                    "code_site"=>$infra->code_site,
                    "technologie_generation"=>$liste_tech,
                    "techno"=>$infra->technologie_generation,
                    "type_du_site"=>$type_site,
                    "proprietaire"=> $proprietaire,
                    "mutualise"=>$infra->mutualise,
                    "latitude"=>$infra->latitude,
                    "longitude"=> $infra->longitude,
                    "colloc"=>  $infra->coloc,
                    "hauteur_antenne"=>$infra->hauteur,
                    "largeur_canaux"=>$infra->largeur_canaux,
                    "commune"=>$commune->commune,
                    "code_commune"=>$commune->code_c,
                    "district"=>$commune->district,
                    "code_district"=>$commune->code_d,
                    "region"=>$commune->region,
                    "code_region"=>$commune->code_r,
                    "annee_mise_en_service"=>$infra->annee_mise_service,
                    "source_energie"=> $liste_src
                ]
                ];
    
                $geojson["features"][]=$feature;
        }
                $geojson=json_encode($geojson);
              
                $tab[0]=$resultats;
                  $tab[1]= $geojson;
                return $tab;
    }

    
    public function all_to_geoSon()
    {
                $resultats = V_filtre_infra::with(['infra','infra.type_site' ,'infra.operateur', 'infra.commune', 'infra.technologies', 'infra.proprietaire', 'infra.sources'])
                ->select('id')
                ->where('en_service', '=', 1)
                ->groupBy('id')
                ->get();

        $filename='infrastructure';
        $geojson=["type"=>"FeatureCollection","features"=>[]];

        $feature = [];
        foreach($resultats as $marqueur){
           $infra=$marqueur->infra;
           $oper=$infra->operateur;
           $commune=$infra->commune;
           $liste_tech=$infra->technologies->pluck('generation')->toArray();
            $feature=[
                "type"=>"Feature",
                "geometry"=>[
                    "type"=>"Point",
                    "coordinates"=> [$infra->longitude, $infra->latitude]
                ],
                "properties"=>[
                    "couleur"=> $oper->couleur,
                    "operateur"=>$oper->operateur,
                    "logo"=> $oper->logo,
                    "nom_site"=>$infra->nom_site,
                    "technologie_generation"=>$liste_tech,
                    "details"=>$infra->technologie_generation,
                    "latitude"=>$infra->latitude,
                    "longitude"=> $infra->longitude,
                    "hauteur_antenne"=>$infra->hauteur,
                    "largeur_canaux"=>$infra->largeur_canaux,
                    "commune"=>$commune->commune,
                    "district"=>$commune->district,
                    "region"=>$commune->region,
                  
                ]
                ];
    
                $geojson["features"][]=$feature;
        }
    
                $geojson=json_encode($geojson);
                // dd( $geojson);
               
                 $filename= storage_path('app/public/geojson/'.$filename.".geojson");
                file_put_contents($filename,$geojson);
    
                // return $resultats;
    }
}
