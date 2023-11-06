@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="#">Logs</a>
    <i class="fa fa-angle-right"></i>
</li>
<!-- <li>
    <span>Liste</span>
</li> -->
@endsection
@section('contenu')
<div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> Journal de logs</span>
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
                    <div class="col-md-12">
                        <div class="btn-group">
                        <form action="{{route('logs.search')}}" method="post" class="form-inline">
                            @csrf
                            <input type="date" class="form-control" name="date" >
                            <select name="action"  class="form-control">
                                <option value="">Action ...</option>
                                @foreach($action as $act)
                                <option value="{{$act->id}}">{{$act->action}}</option>
                                @endforeach
                            </select>
                            <input type="search"  name="utilisateur" class="form-control" id="searchInput" placeholder="Utilisateur">
                            <button type="submit" class="btn btn-circle"><i class="icon-magnifier"></i></button>
                        </form>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="btn-group ">
                            <input type="search" class="form-control" id="searchInput" placeholder="Rechercher...">
                        </div>
                    </div> -->
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur </th>
                        <th>Detail</th>
                      
                      
                    </tr>
                </thead>
                <tbody>
                    @foreach($liste as $l) 
                        <tr>
                        <td>{{$l->date}}</td>
                        <td>{{$l->Utilisateur->email}}</td>
                        <td>{{$l->Type_action->action}}: {{$l->detail}}</td>
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

                for (var j = 0; j < cells.length ; j++) {
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