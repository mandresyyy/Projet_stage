@extends("Utilisateur.Layouts.master")
@section('contenu')
<link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.7.5/proj4.js"></script> -->
<script src="{{asset('geojson/Rgion_1.js')}}"></script>
<script src="{{asset('geojson/District_2.js')}}"></script>

<style>
       .custom-label-icon {
    text-align: center;
    margin: 0;
    padding: 0;
    pointer-events: none; /* Évite que l'étiquette ne bloque les interactions avec la carte */
}

.custom-label {
    background-color: transparent; /* Fond transparent */
    border: none; /* Pas de bordure */
    padding: 0; /* Aucune marge intérieure */
    font-size: 11px; /* Taille de la police */
}
    </style>
<div class="row">

    <div class="col-md-12 " style="height: 150px; ">
        <div class="col-md-2">
           
                <span class="caption-subject font-green-sharp bold uppercase">Operateur </span>

                <div class="portlet-body todo-project-list-content">
                    <div class="todo-project-list">
                        <ul class="nav nav-stacked">
                            <select multiple class="form-control input-small" name="operateur">
                                @foreach($operateur as $op)
                                <option value="{{$op->id}}">{{$op->operateur}}</option>
                                @endforeach
                            </select>
                        </ul>

                    </div>

                </div>

            
        </div>
        <div class="col-md-2">
            

                <span class="caption-subject font-green-sharp bold uppercase">Operateur </span>

                <div class="portlet-body todo-project-list-content">
                    <div class="todo-project-list">
                        <ul class="nav nav-stacked">
                            <select multiple class="form-control input-small" name="technologie">
                                @foreach($technologie as $tech)
                                <option value="{{$tech->id}}">{{$tech->generation}}</option>
                                @endforeach
                            </select>
                        </ul>

                    </div>

                </div>

            
        </div>
        <div class="col-md-2">
            

                <span class="caption-subject font-green-sharp bold uppercase">Region </span>

                <div class="portlet-body todo-project-list-content">
                    <div class="todo-project-list">
                        <ul class="nav nav-stacked">
                            <select multiple class="form-control input-small" name="region">
                                @foreach($region as $r)
                                <option value="{{$r->code_r}}">{{$r->region}}</option>
                                @endforeach
                            </select>

                        </ul>

                    </div>

                </div>

            
        </div>
        <div class="col-md-2">
          

                <span class="caption-subject font-green-sharp bold uppercase">Energie </span>

                <div class="portlet-body todo-project-list-content">
                    <div class="todo-project-list">
                        <ul class="nav nav-stacked">
                            <select multiple class="form-control input-small" name="source">
                                @foreach($source as $s)
                                <option value="{{$s->id}}">{{$s->source}}</option>
                                @endforeach
                            </select>

                        </ul>

                    </div>

                </div>

            
        </div>
        <div class="col-md-2">
            

                <span class="caption-subject font-green-sharp bold uppercase">Type </span>

                <div class="portlet-body todo-project-list-content">
                    <div class="todo-project-list">
                        <select multiple class="form-control input-small" name="type">
                            @foreach($type as $typ)
                            <option value="{{$typ->id}}">{{$typ->type}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>

            
        </div>
        <div class="col-md-2">
            

                <span class="caption-subject font-green-sharp bold uppercase">Mutualise </span>

                <div class="portlet-body todo-project-list-content">
                    <div class="todo-project-list">
                      <select name="mutualise" id="mutualise" class="form-control input-small">
                        <option value="tous"></option>
                        <option value="OUI">Mutualise</option>
                        <option value="NON">Non mutualise</option>
                      </select>
                    </div>
                </div>

            
        </div>




    </div>
    <div class="col-md-12">

        <!-- BEGIN WORLD PORTLET-->
        <div class="portlet light portlet-fit bordered" style="height: 500px;">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-green"></i>
                    <span class="caption-subject font-green bold uppercase">Carte</span>
                </div>

                <div class="dropdown" style="margin-left:85%;margin-top:15px;width:40px">
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

    

    @php
    $imageSrc = asset('login/logo_artec.png');
    @endphp
</div>
<script src="{{asset('js/map.js')}}"></script>

@endsection