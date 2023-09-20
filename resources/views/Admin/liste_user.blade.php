@extends("Admin.Layouts.master")
@section('contenu')
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
                        <div class="btn-group pull-right">
                            <input type="search" class="form-control" id="searchInput" placeholder="Rechercher..."> 
                        </div>
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
                    @foreach($liste as $Utilisateur)
                    <tr class="odd gradeX">
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
    var input = document.getElementById("searchInput");
    var table = document.getElementById("sample_1");
    var rows = table.getElementsByTagName("tr");

    input.addEventListener("input", function() {
        var searchValue = input.value.toLowerCase();

        for (var i = 1; i < rows.length; i++) { // Commence à 1 pour exclure la ligne d'en-tête
            var cells = rows[i].getElementsByTagName("td");
            var rowMatch = false;

            for (var j = 0; j < cells.length-1; j++) {
                var cellText = cells[j].textContent.toLowerCase();
                if (cellText.includes(searchValue)) {
                    rowMatch = true;
                    break;
                }
            }

            if (rowMatch) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
});
</script>
