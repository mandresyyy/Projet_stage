@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="#">Releve</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Map</span>
</li>
@endsection
@section('contenu')
<link href="{{asset('css/fontMap.css')}}" rel="stylesheet">
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/topojson-client@3"></script>
<link rel="stylesheet" href="{{asset('css/leaflet.css')}}"  />

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

<div class="row" style="height:100%">


    <div class="col-md-12">

        <!-- BEGIN WORLD PORTLET-->
        <div class="portlet light portlet-fit bordered" style="height:100%">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-green"></i>
                    <span class="caption-subject font-green bold uppercase">Carte</span>
                </div>


            </div>
                <div class="portlet-body">
                    <div id="map" class="vmaps" style="height: 800px"> </div>
                </div>
            </div>
            <!-- END WORLD PORTLET-->
        </div>

        <!-- responsive -->
        




    </div>
    <script>
            var operateur = @json($liste_op);
            var tech = @json($liste_tech);
    </script>

    <script src="{{asset('js/map_releve.js')}}"></script>
    @endsection