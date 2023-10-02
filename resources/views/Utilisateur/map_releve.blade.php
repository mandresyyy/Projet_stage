@extends("Utilisateur.Layouts.master")
@section('contenu')
<link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{asset('geojson/Rgion_1.js')}}"></script>
<script src="{{asset('geojson/District_2.js')}}"></script>

<style>
    .custom-label-icon {
        text-align: center;
        margin: 0;
        padding: 0;
        pointer-events: none;
        /* Évite que l'étiquette ne bloque les interactions avec la carte */
    }

    .custom-label {
        background-color: transparent;
        /* Fond transparent */
        border: none;
        /* Pas de bordure */
        padding: 0;
        /* Aucune marge intérieure */
        font-size: 11px;
        /* Taille de la police */
    }
</style>

<div class="row">


    <div class="col-md-12">

        <!-- BEGIN WORLD PORTLET-->
        <div class="portlet light portlet-fit bordered" style="height: 500px;">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-green"></i>
                    <span class="caption-subject font-green bold uppercase">Carte</span>
                </div>


                <div class="dropdown" style="margin-left:90%;margin-top:15px;width:300px">
                    <div>
                        <a class="caption-subject font-green bold uppercase" data-toggle="modal" href="#responsive"><i class=" fa fa-table"></i> </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <!-- Tableau de données -->

                        </div>



                    </div>


                    <!-- </div> -->
                </div>
                <div class="portlet-body">
                    <div id="map" class="vmaps" style="height: 400px"> </div>
                </div>
            </div>
            <!-- END WORLD PORTLET-->
        </div>

        <!-- responsive -->
        <div id="responsive" class="modal fade" tabindex="-1" data-width="500" style="width:75%;height:70%;background-color:#e2ebeb;margin-left:15%">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Infrastructures</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table  table-hover table-checkable ">
                            <thead>
                                <tr>

                                    <th>Operateur</th>
                                    <!-- <th>Latitude</th>
                                <th>Longitude</th> -->
                                    <th>Site</th>
                                    <th>Commune</th>
                                    <th>District</th>
                                    <th>Region</th>

                                </tr>
                            </thead>
                            <tbody id="table_body">



                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-outline dark" id="close">Close</button>
            </div>
        </div>




    </div>
    <script>
         var signal = @json($data);
            var operateur = @json($operateur);
    </script>

    <script src="{{asset('js/map_releve.js')}}"></script>
    @endsection