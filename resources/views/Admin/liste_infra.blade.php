@extends("Admin.Layouts.master")
@section('contenu')
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
                            <form action="{{route('infra.search')}}" method="get" id="search">
                            <input type="search" class="form-control" id="searchInput" name="search" placeholder="Rechercher...">
                            
                            </form>
                        </div>
                    </div>
                </div>
                <div style="float: right;">
                {{$liste->links()}}
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
                        <th>Status</th>
                        

                    </tr>
                </thead>
                <tbody>
                    <script>
                        function info(id) {

                            var url = "{{ route('infra.info', ['idUpdate' => 'blanc']) }}";
                            url = url.replace('blanc', id);
                            window.location.href = url;
                        }
                    </script>

                    @foreach($liste as $inf)

                    <tr class="odd gradeX" onclick="info(@json($inf->id))">
                        <?php $listetech = $inf->get_technologie();
                        // $listesource=$inf->get_source();
                        ?>

                        <td>{{($loop->index+1) + (($liste->currentPage()-1) * $liste->perPage())}}</td>
                        <td> {{$inf->nom_site}}</td>
                        <td> {{$inf->operateur->operateur}} </td>
                        <?php
                        $tec = "";
                        foreach ($listetech as $tech) {
                            $tec = $tec . " " . $tech->technologie->generation;
                        }
                        ?>
                        <td>
                            {{$tec}}
                        </td>
                        <td> {{$inf->type_site->type}}</td>

                        <?php
                        // $src="";
                        // foreach($listesource as $sr){
                        //     $src=$src." ".$sr->source->source;
                        // }
                        ?>


                        <td>{{$inf->proprietaire->proprietaire}}</td>
                        <td>{{$inf->commune->commune}}({{$inf->commune->region}})</td>
                        @if($inf->en_service==1)
                        <td> <span class="label label-sm label-success">En service</span> </td>
                        @else
                        <td> <span class="label label-sm label-warning">Hors service</span> </td>
                        @endif


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

                for (var j = 0; j < cells.length; j++) {
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