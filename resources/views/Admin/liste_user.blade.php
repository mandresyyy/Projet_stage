@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="#">Utilisateur</a>
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
                <span class="caption-subject bold uppercase"> Utilisateurs</span>
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
                            <a href="{{route('utilisateur.form')}}">
                            <button id="sample_editable_1_new" class="btn sbold green" > Créer utilisateur
                                <i class="fa fa-plus"></i>
                            </button></a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    <tr>
                        <th></th>
                        <th> Matricule </th>
                        <th> Nom </th>
                        <th> Prenom </th>
                        <th> Mail </th>
                        <th> Role </th>
                        <th>Etat</th>
                        <th> Actions </th>
                    </tr>
                </thead>
                <tbody>
                <script>
                        function info(id) {

                            var url = "{{ route('utilisateur.info', ['idUpdate' => 'blanc']) }}";
                            url = url.replace('blanc', id);
                            window.location.href = url;
                        }
                    </script>
                    @foreach($liste as $Utilisateur)
                    <tr class="odd gradeX" onclick="info(@json($Utilisateur->id))">
                        <td scope="row"> {{$loop->index+1}} </td>
                        <td> {{$Utilisateur->matricule}} </td>
                        <td> {{$Utilisateur->nom}} </td>
                        <td> {{$Utilisateur->prenom}} </td>
                        <td>{{$Utilisateur->email}}</td>
                        <td> {{$Utilisateur->type->type_util}} </td>
                        @if($Utilisateur->etat->id==1)
                        <td> <span class="label label-sm label-success">{{$Utilisateur->etat->etat}}</span> </td>
                        @else
                        <td> <span class="label label-sm label-warning">{{$Utilisateur->etat->etat}}</span> </td>
                        @endif
                        <td>
                            <a href="{{route('utilisateur.info',['idUpdate'=>$Utilisateur->id])}}"><button type="button" class="btn btn-sm grey-salsa btn-outline"> Modifier </button></a>
                        </td>
                    </tr>
                    @endforeach
                   
                </tbody>
            </table>
            {{$liste->links()}}
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
});
});
</script>
