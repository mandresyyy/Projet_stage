@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="{{route('infra.liste')}}">Infrastructure</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Liste</span>
</li>
@endsection
@section('contenu')
<!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->
<script src="{{asset('Utilitaire/jQuery.js')}}"></script>
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->
<script src="{{asset('Utilitaire/Data_table.min.js')}}"></script>
<link href="{{asset('Utilitaire/datatable.min.css')}}" rel="stylesheet" type="text/css" />

<!-- <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" /> -->
<link href="{{asset('login/assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('login/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> Infrastructure</span>
            </div>
            <!-- <div class="actions">
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <label class="btn btn-transparent dark btn-outline btn-circle btn-sm active">
                        <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                    <label class="btn btn-transparent dark btn-outline btn-circle btn-sm">
                        <input type="radio" name="options" class="toggle" id="option2">Settings</label>
                </div>
            </div> -->
        </div>
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <a href="{{route('infra.form')}}">
                                <button id="sample_editable_1_new" class="btn sbold green"> Nouvelle infrastructure
                                    <i class="fa fa-plus"></i>
                                </button></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="btn-group pull-right">

                        </div>
                    </div>
                </div>
                <div style="float: right;">

                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    <tr>

                        <th></th>
                        <th> Nom site </th>
                        <th>Operateur </th>
                        <th> Technologie </th>
                        <th> Type de site </th>
                        <th> Proprietaire </th>
                        <th>Commune</th>
                        <th>Enregistrement</th>
                        <th>Status</th>
                        <th>Supprimer</th>

                    </tr>
                </thead>
                <tbody>
                    <script>
                        function info(id) {

                            var url = "{{ route('infra.info', ['idUpdate' => 'blanc']) }}";
                            url = url.replace('blanc', id);
                            window.location.href = url;
                        }
                        function delet(id){
                            var url = "{{ route('infra.delete', ['id' => 'blanc']) }}";
                            url = url.replace('blanc', id);
                            window.location.href = url;
                        }
                    </script>

                    @foreach($liste as $inf)

                    <tr class="odd gradeX">


                        <td onclick="info(@json($inf->id))">{{($loop->index+1)}}</td>
                        <td onclick="info(@json($inf->id))"> {{$inf->nom_site}}</td>
                        <td onclick="info(@json($inf->id))"> {{$inf->operateur->operateur}} </td>
                        <td onclick="info(@json($inf->id))">
                            {{$inf->technologies->pluck('generation')}}
                        </td>
                        <td onclick="info(@json($inf->id))"> {{$inf->type_site->type}}</td>


                        <td onclick="info(@json($inf->id))">{{$inf->proprietaire->proprietaire}}</td>
                        <td onclick="info(@json($inf->id))">{{$inf->commune->commune}}({{$inf->commune->region}})</td>
                        <td onclick="info(@json($inf->id))">{{$inf->date_upload}}</td>
                        @if($inf->en_service==1)
                        <td onclick="info(@json($inf->id))"> <span class="label label-sm label-success">En service</span> </td>
                        @else
                        <td onclick="info(@json($inf->id))"> <span class="label label-sm label-warning">Hors service</span> </td>
                        @endif
                        <td>
                            <button class="btn btn-circle btn-icon-only btn-default" onclick="delet(@json($inf->id))">
                                <i class="icon-trash"></i>
                            </button>
                        </td>
                       
                    </tr>
                    @endforeach

                </tbody>
            </table>
                @if(session('delete'))
                        <script>
                            alert('Infra supprimer');
                        </script>
                @endif
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        new DataTable('#sample_1', {
            language: {
                url: '../../pivottable/fr-FR.json',
            },
            pageLength: 25,
        });

    });
</script>


