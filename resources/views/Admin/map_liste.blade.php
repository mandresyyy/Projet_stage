@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="{{route('admin.acceuil')}}">Map</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Infrastructure</span>
</li>
@endsection
@section('contenu')
<link href="{{asset('css/fontMap.css')}}" rel="stylesheet">

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/topojson-client@3"></script>

<!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->
<script src="{{asset('Utilitaire/jQuery.js')}}"></script>
<script src="{{asset('Utilitaire/Data_table.min.js')}}"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->

<!-- <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" /> -->
<link href="{{asset('Utilitaire/datatable.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('login/assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('login/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />

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
    <div class="col-md-12 " style="height: 100px; ">
        <div class="col-md-2">

            <span class="caption-subject font-green-sharp bold uppercase">Operateur </span>

            <div class="portlet-body todo-project-list-content">
                <div class="todo-project-list">
                    <ul class="nav nav-stacked">
                        <select multiple class="form-control input-small" name="operateur" style="height:70px">
                            @foreach($operateur as $op)
                            <option value="{{$op->id}}">{{$op->operateur}}</option>
                            @endforeach
                        </select>
                    </ul>

                </div>

            </div>


        </div>
        <div class="col-md-2">


            <span class="caption-subject font-green-sharp bold uppercase">Technologie </span>

            <div class="portlet-body todo-project-list-content">
                <div class="todo-project-list">
                    <ul class="nav nav-stacked">
                        <select multiple class="form-control input-small" name="technologie" style="height:70px">
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
                        <select multiple class="form-control input-small" name="region" style="height:70px">
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
                        <select multiple class="form-control input-small" name="source" style="height:70px">
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
                    <select multiple class="form-control input-small" name="type" style="height:70px">
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
</div>
<div class="col-md-12">

    <!-- BEGIN WORLD PORTLET-->
    <div class="portlet light portlet-fit bordered" style="height: 100%;">
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
                <div id="map" class="vmaps" style="height: 800px"> </div>
            </div>
        </div>
        <!-- END WORLD PORTLET-->
    </div>

    <!-- responsive -->
    <div id="responsive" class="modal fade" tabindex="-1" data-width="500" style="width:75%;height:70%;background-color:#e2ebeb;margin-left:15%">
    <style>
       #sample_1_filter{
        float: left;
       }
    </style>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Infrastructures</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">

                  
                    <table class="table  table-hover table-checkable " id="sample_1">
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
            <button type="button" data-dismiss="modal" class="btn btn-outline dark" id="close">Fermer</button>
        </div>
    </div>



    @php
    $imageSrc = asset('login/logo_artec.png');
    @endphp
</div>

<script src="{{asset('js/map.js')}}"></script>
<script>
    var dataTable;
    //         document.addEventListener("DOMContentLoaded", function() {
    //             dataTable= new DataTable('#sample_1', {
    //     language: {
    //         // url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
    //         url: '../../pivottable/fr-FR.json',
    //     },
    //     
    // });

    //     });
    //     $(document).ready(function() {
    dataTable = $('#sample_1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf'
        ],
        language: {
            // url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
            url: '../../pivottable/fr-FR.json',
        },
    });
    // } );
</script>

@endsection