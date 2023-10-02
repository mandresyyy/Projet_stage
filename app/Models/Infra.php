<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infra extends Model
{
    use HasFactory;
    protected $table = 'infra';
    public $timestamps = false;
    protected $fillable = ['id','id_operateur','nom_site','code_site','technologie_generation','id_type_site',
    'id_proprietaire','mutualise','id_colloc','latitude','longitude','hauteur',
    'largeur_canaux','id_commune','annee_mise_service','date_upload','en_service'];

    public function operateur(){
        return $this->belongsTo(Operateur::class,'id_operateur');
    }

    public function type_site(){
        return $this->belongsTo(Type_site::class,'id_type_site');
    }

    public function proprietaire(){
        return $this->belongsTo(Proprietaire_site::class,'id_proprietaire');
    }

    public function coloc(){
        return $this->belongsTo(Operateur::class,'id_colloc');
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

    public function to_geoSon($op, $region, $tech, $type, $source, $mutualise, $user)
    {
        if (count($op) == 0 && count($region) == 0 && count($tech) == 0 && count($type) == 0 && count($source) == 0 && $mutualise == 'tous') {
            $resultats = collect();
        } else {
            if ($mutualise != 'tous') {
                $resultats = V_filtre_infra::select('id')
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
                ->where('mutualise', '=', $mutualise)
                ->groupBy('id')
                ->get();
            } else {
                $resultats = V_filtre_infra::select('id')
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
                ->groupBy('id')
                ->get();
            }
        }

       

        $filename=$user->id;
        $geojson=["type"=>"FeatureCollection","features"=>[]];

        foreach($resultats as $marqueur){
            $liste_src=Infra_source::where('id_infra','=',$marqueur->id)->get();
            $liste_source=[];
            
                foreach($liste_src as $ls){
                    $liste_source[]=$ls->source->source;
                }
            $liste_tech=Infra_technologie::where('id_infra','=',$marqueur->id)->get();
            $liste_techno=[];
            
            foreach($liste_tech as $l){
                $liste_techno[]=$l->technologie->generation;
            }
            $feature=[
                "type"=>"Feature",
                "geometry"=>[
                    "type"=>"Point",
                    "coordinates"=> [$marqueur->infra->longitude,$marqueur->infra->latitude]
                ],
                "properties"=>[
                    "couleur"=>$marqueur->infra->operateur->couleur,
                    "operateur"=>$marqueur->infra->operateur->operateur,
                    "logo"=>$marqueur->infra->operateur->logo,
                    "nom_site"=>$marqueur->infra->nom_site,
                    "code_site"=>$marqueur->infra->code_site,
                    "technologie_generation"=>$liste_techno,
                    "techno"=>$marqueur->infra->technologie_generation,
                    "type_du_site"=>$marqueur->infra->type_site->type,
                    "proprietaire"=>$marqueur->infra->proprietaire->proprietaire,
                    "mutualise"=>$marqueur->infra->mutualise,
                    "latitude"=>$marqueur->infra->latitude,
                    "longitude"=>$marqueur->infra->longitude,
                    "colloc"=>$marqueur->infra->coloc->operateur,
                    "hauteur_antenne"=>$marqueur->infra->hauteur,
                    "largeur_canaux"=>$marqueur->infra->largeur_canaux,
                    "commune"=>$marqueur->infra->commune->commune,
                    "code_commune"=>$marqueur->infra->commune->code_c,
                    "district"=>$marqueur->infra->commune->district,
                    "code_district"=>$marqueur->infra->commune->code_d,
                    "region"=>$marqueur->infra->commune->region,
                    "code_region"=>$marqueur->infra->commune->code_r,
                    "annee_mise_en_service"=>$marqueur->infra->annee_mise_service,
                    "source_energie"=>$liste_source
                ]
                ];
    
                $geojson["features"][]=$feature;
        }
    
                $geojson=json_encode($geojson);
                 $filename=public_path('geojson') . '/' .$filename.".geojson";
                file_put_contents($filename,$geojson);
    
                return $resultats;
    }

    
    public function all_to_geoSon()
    {
                $resultats = V_filtre_infra::select('id')
                ->where('en_service', '=', 1)
                ->groupBy('id')
                ->get();

        $filename='infrastructure';
        $geojson=["type"=>"FeatureCollection","features"=>[]];

        foreach($resultats as $marqueur){
            $liste_src=Infra_source::where('id_infra','=',$marqueur->id)->get();
            $liste_source=[];
            
                foreach($liste_src as $ls){
                    $liste_source[]=$ls->source->source;
                }
            $liste_tech=Infra_technologie::where('id_infra','=',$marqueur->id)->get();
            $liste_techno=[];
            
            foreach($liste_tech as $l){
                $liste_techno[]=$l->technologie->generation;
            }
            $feature=[
                "type"=>"Feature",
                "geometry"=>[
                    "type"=>"Point",
                    "coordinates"=> [$marqueur->infra->longitude,$marqueur->infra->latitude]
                ],
                "properties"=>[
                    "couleur"=>$marqueur->infra->operateur->couleur,
                    "operateur"=>$marqueur->infra->operateur->operateur,
                    "logo"=>$marqueur->infra->operateur->logo,
                    "nom_site"=>$marqueur->infra->nom_site,
                    "code_site"=>$marqueur->infra->code_site,
                    "technologie_generation"=>$liste_techno,
                    "techno"=>$marqueur->infra->technologie_generation,
                    "type_du_site"=>$marqueur->infra->type_site->type,
                    "proprietaire"=>$marqueur->infra->proprietaire->proprietaire,
                    "mutualise"=>$marqueur->infra->mutualise,
                    "latitude"=>$marqueur->infra->latitude,
                    "longitude"=>$marqueur->infra->longitude,
                    "colloc"=>$marqueur->infra->coloc->operateur,
                    "hauteur_antenne"=>$marqueur->infra->hauteur,
                    "largeur_canaux"=>$marqueur->infra->largeur_canaux,
                    "commune"=>$marqueur->infra->commune->commune,
                    "code_commune"=>$marqueur->infra->commune->code_c,
                    "district"=>$marqueur->infra->commune->district,
                    "code_district"=>$marqueur->infra->commune->code_d,
                    "region"=>$marqueur->infra->commune->region,
                    "code_region"=>$marqueur->infra->commune->code_r,
                    "annee_mise_en_service"=>$marqueur->infra->annee_mise_service,
                    "source_energie"=>$liste_source
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
