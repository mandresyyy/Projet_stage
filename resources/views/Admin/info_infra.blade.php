@extends("Admin.Layouts.master")
@section('contenu')
<!-- Inclure jQuery -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<!-- Inclure jQuery UI -->
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->


<div class="row profile-account">
    <div class="col-md-3">
        <ul class="ver-inline-menu tabbable margin-bottom-10">
            <li class="active" id="t1">
                <a data-toggle="tab" href="#tab_1-1">
                    <i class="fa fa-cog"></i> Information géneral</a>
                <span class="after"> </span>
            </li>

            <li id="t2">
                <a data-toggle="tab" href="#tab_3-3">
                    <i class="fa fa-wrench"></i> Information technique </a>
            </li>
            
            <li>
            <i class="fa fa-wrench"></i>
                @if($infra->en_service==1)

                <input type="checkbox" class="make-switch" checked data-on-text="En service" onchange="etat(@json($infra->id))" data-on-color="success" data-off-color="warning" data-off-text="Hors service" data-size="small">
                @else
                <input type="checkbox" class="make-switch" data-on-text="En service" onchange="etat(@json($infra->id))" data-on-color="success" data-off-color="warning" data-off-text="Hors service" data-size="small">
                @endif
                <script>
                    function etat(id) {
                        var url = "{{ route('infra.etat', ['id' => 'blanc']) }}";
                        url = url.replace('blanc', id);
                        window.location.href = url;
                    }
                </script>
                
            </li>


        </ul>
        @if($errors->any())
        @if(!$errors->has('erreur'))
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        @endif
        @endif
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div id="tab_1-1" class="tab-pane active">
                <form role="form" action="{{route('infra.update')}}" id="form1" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="id_infra" value="{{$infra->id}}">
                        <div class="col-md-6">
                            <label class="control-label">Code site</label>
                            <input type="text" class="form-control" name="code_site" value="{{$infra->code_site}}" />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Nom site</label>
                            <input type="text" class="form-control" name="nom_site" value="{{$infra->nom_site}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Operateur</label>
                            <select name="operateur" class="form-control">
                                <option value="{{$infra->id_operateur}}">{{$infra->operateur->operateur}}</option>
                                @foreach($listeop as $op)
                                @if($op->id!=$infra->id_operateur)
                                <option value="{{$op->id}}">{{$op->operateur}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Proprietaire</label>
                            <input type="text" class="form-control" name="proprio" value="{{$infra->proprietaire->proprietaire}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Type site</label>
                            <select name="type" class="form-control">
                                <option value="{{$infra->id_type_site}}">{{$infra->type_site->type}}</option>
                                @foreach($listetype as $op)
                                @if($op->id!=$infra->id_type_site)
                                <option value="{{$op->id}}">{{$op->type}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Annee mise en service</label>
                            <input class="form-control" name="annee" value="{{$infra->annee_mise_service}}">
                        </div>

                    </div>




                    <div class="form-group">
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Commune</label>
                            <input class="form-control" id="commune" value="{{$infra->commune->commune}}" list="suggestions" name="commune">
                            <datalist id="suggestions"></datalist>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Code commune</label>
                            <input class="form-control code-commune" name="code_c" id="code_commune" value="{{$infra->commune->code_c}}" disabled>

                        </div>

                    </div>
                    <div class="col-md-6">
                        <br>
                        <label class="control-label">Source energie</label>
                        <select class="form-control" name="source[]" multiple>
                            @foreach($listesrc as $src)
                            @if($src->not_in($listeinfrasrc)==false)
                            <option selected value="{{$src->id}}">{{$src->source}}</option>
                            @else
                            <option value="{{$src->id}}">{{$src->source}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Mutualise</label>
                            <br>
                            <label>
                                @if($infra->mutualise=="OUI")
                                <input type="radio" name="mutualise" class="icheck" value="0"> Non</label>
                            <input type="radio" name="mutualise" class="icheck" value="1" checked> OUI</label>
                            @else
                            <input type="radio" name="mutualise" class="icheck" value="0" checked> Non</label>
                            <input type="radio" name="mutualise" class="icheck" value="1"> OUI</label>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Colocataire</label>
                            <select name="coloc" class="form-control">
                                <option value="{{$infra->id_colloc}}">{{$infra->coloc->operateur}}</option>
                                @foreach($listeop1 as $op)
                                @if($op->id!=$infra->id_colloc)
                                <option value="{{$op->id}}">{{$op->operateur}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>


                    </div>

                    <div class="col-md-12" style="margin-top: 20px;">

                        <a id="valider1" class="btn green"> Valider</a>
                        <a id="back" class="btn default"> Annuler </a>
                    </div>


                </form>


            </div>


            <div id="tab_3-3" class="tab-pane">
                <form action="{{route('infra.update2')}}" method="POST" id="form2">
                    @csrf
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="id_infra" value="{{$infra->id}}">
                            <label class="control-label">Latitude</label>
                            <input type="text" class="form-control" name="latitude" value="{{$infra->latitude}}" />
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">Longitude</label>
                            <input type="text" class="form-control" name="longitude" value="{{$infra->longitude}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Hauteur</label>
                            <input type="text" class="form-control" name="hauteur" value="{{$infra->hauteur}}" />
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Largeur canaux</label>
                            <input type="text" class="form-control" name="largeur" value="{{$infra->largeur_canaux}}" />
                        </div>
                    </div>
                    <div class="form-group">

                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Technologie</label>
                            <select class="form-control" name="tech[]" multiple>
                                @foreach($listetech as $tech)
                                @if($tech->not_in($listeinfratech)==false)
                                <option selected value="{{$tech->id}}">{{$tech->generation}}</option>
                                @else
                                <option value="{{$tech->id}}">{{$tech->generation}}</option>
                                @endif
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-6">
                            <br>
                            <label class="control-label">Details technologie</label>
                            <textarea type="text" class="form-control" name="info" value="{{$infra->technologie_generation}}">{{$infra->technologie_generation}}</textarea>
                        </div>


                    </div>
                    <div class="col-md-12" style="margin-top: 20px;">

                        <a href="javascript:;" id="valider2" class="btn green"> Valider</a>
                        <a id="back" class="btn default"> Annuler </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!--end col-md-9-->
</div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var codeCommuneInput = document.getElementById("code_commune");
        var CommuneInput = document.getElementById("commune");
        var suggestionsList = document.getElementById("suggestions");

        CommuneInput.addEventListener("input", function() {
            var Commune = CommuneInput.value;
            // console.log(Commune);

            fetch("/admin/auto?term=" + Commune) // suggestion
                .then(function(response) {
                    if (response.ok) {
                        return response.json();
                        console.log(response);
                    } else {
                        throw new Error("Erreur lors de la requête AJAX");
                    }
                })
                .then(function(data) {

                    suggestionsList.innerHTML = "";
                    // console.log(data);
                    // Remplissez la liste de suggestions avec les nouvelles suggestions
                    data.forEach(function(suggestion) {
                        var option = document.createElement("option");
                        option.value = suggestion;
                        suggestionsList.appendChild(option);
                    });
                })
                .catch(function(error) {
                    console.error(error);
                });





            // Effectuez une requête AJAX vers votre endpoint pour obtenir la commune correspondante
            fetch("/admin/commune/region?commune=" + Commune)
                .then(response => response.json())
                .then(data => {
                    codeCommuneInput.value = data;
                })
                .catch(error => {
                    codeCommuneInput.value = "Non trouvé";
                });
        });

        var mutualiseRadio = document.querySelectorAll("input[name='mutualise']");
        var colocSelect = document.querySelector("select[name='coloc']");

        // Fonction pour mettre à jour le champ "Colocataire"
        function updateColocField() {
            var mutualiseValue = Array.from(mutualiseRadio).find(radio => radio.checked).value;

            if (mutualiseValue === "0") {
                colocSelect.disabled = true;
                colocSelect.value = "1";
            } else {
                colocSelect.disabled = false;
            }
        }

        // Écoutez les événements de changement sur le champ "Mutualise"
        mutualiseRadio.forEach(radio => {
            radio.addEventListener("change", updateColocField);
        });

        // Appelez la fonction pour configurer l'état initial du champ "Colocataire"
        updateColocField();

        var form1 = document.getElementById('form1');
        var valider1 = document.getElementById('valider1');
        valider1.addEventListener("click", function() {
            form1.submit();
        });
        var form2 = document.getElementById('form2');
        var valider2 = document.getElementById('valider2');
        valider2.addEventListener("click", function() {
            form2.submit();
        });
        var btnback = document.getElementById('back');
        btnback.addEventListener("click", function() {
            window.history.back();
        });


    });
</script>
@endsection