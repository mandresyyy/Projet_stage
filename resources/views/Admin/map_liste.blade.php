@extends("Admin.Layouts.master")
@section('contenu')
<link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<div class="row">
    <div class="col-md-8">
        <!-- BEGIN WORLD PORTLET-->
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers font-green"></i>
                    <span class="caption-subject font-green bold uppercase">World</span>
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
                <div id="map" class="vmaps"> </div>
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

    <div class="col-md-2">
        <div class="portlet light ">

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
    </div>
    <div class="col-md-2">
        <div class="portlet light ">

            <span class="caption-subject font-green-sharp bold uppercase">Technologie </span>

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
    </div>
    <div class="col-md-2">
        <div class="portlet light ">

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
            <br>

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
    </div>
    <div class="col-md-2">
        <div class="portlet light ">

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
    </div>
    <div class="col-md-2">
        <div class="portlet light ">

            <span class="caption-subject font-green-sharp bold uppercase">Mutualise </span>

            <div class="portlet-body todo-project-list-content">
                <div class="todo-project-list">
                    <input type="checkbox" class="" id="mutualise" name="mutualise">

                </div>
            </div>


        </div>
    </div>

    @php
    $imageSrc = asset('login/logo_artec.png');
    @endphp
</div>
<script>
    var map = L.map('map').setView([-18.8792, 46.3504], 8); // initialisation de la carte
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { // ajout tuile
        maxZoom: 25,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    var group = L.layerGroup();

    function onEachFeature(feature, layer) {
        if (feature.properties) {

            var contenu = '<center><img src="{{$imageSrc}}" alt="tsita" style="width:50px"></center>';
            var contenu = contenu + '<img src="{{ asset("storage/photos") }}/' + feature.properties.logo + '" style="width:20px;float:right">';
            var contenu = contenu + '<table>';
            var contenu = contenu + '<tr><td><strong>Operateur :</td> <td>' + feature.properties.operateur + '</td></tr>';
            var contenu = contenu + '<tr><td><strong>Nom du site :</td><td> ' + feature.properties.nom_site + '</td></tr>';
            var contenu = contenu + '<tr><td><strong>Type :</td><td>' + feature.properties.type_du_site + '</td></tr>';
            var contenu = contenu + '<tr><td><strong>Technologie :</td> <td>' + feature.properties.technologie_generation + '</td></tr>';
            var contenu = contenu + '<tr><td><strong>Source d\'energie : </td><td>' + feature.properties.source_energie + '</td></tr>';
            var contenu = contenu + '<tr><td><strong>Proprietaire :</td><td>' + feature.properties.proprietaire + '</td></tr>';
            var contenu = contenu + '<tr><td><strong>Site mutualisé :</td><td>' + feature.properties.mutualise + ' </td></tr>';
            var contenu = contenu + '<tr><td><strong>Operateur en colocation:</td><td>' + feature.properties.colloc + ' </td></tr>';
            var contenu = contenu + '<tr><td><strong>Site mutualisé :</td><td>' + feature.properties.mutualise + ' </td></tr>';
            var contenu = contenu + '<tr><td><strong>Latitude :</td><td>' + feature.properties.latitude + ' </td></tr>';
            var contenu = contenu + '<tr><td><strong>Longitude :</td><td>' + feature.properties.longitude + ' </td></tr>';
            var contenu = contenu + '<tr><td><strong>Hauteur antenne  (m):</td><td>' + feature.properties.hauteur_antenne + ' </td></tr>';
            var contenu = contenu + '<tr><td><strong>Largeur des canaux occupés (MHz) :</td><td>' + feature.properties.largeur_canaux + ' </td></tr>';
            var contenu = contenu + '<tr><td><strong>Annee mise en service :</td><td>' + feature.properties.annee_mise_en_service + ' </td></tr>';
            var contenu = contenu + '</table>';
            var config = {
                offset: [0, -15]
            };
            layer.bindPopup(contenu, config);
        }

    }

    function showMarqueur(json_name) {
        // console.log(json_name);
        var marks = "/geojson/" + json_name + ".geojson";
        if (window['marquage'] != null) {
            map.removeLayer(window['marquage']);
        }
        if (window['marks'] != null) {
            // console.log("misy");
            window['marks'].clearLayers();
            map.removeLayer(window['marks']);
        }


        fetch(marks)
            .then(response => response.json())
            .then(data => {

                group.addLayer(L.geoJSON(data, {
                    pointToLayer: function(feature, latlng) {
                        let lon = parseFloat(feature.properties.longitude);
                        let lat = parseFloat(feature.properties.latitude);
                        let demi = 0.005;

                        let supgauche = [lat + demi, lon - demi];
                        let supdroite = [lat - demi, lon + demi];
                        var carre = L.rectangle([supgauche, supdroite], {
                            color: feature.properties.couleur,
                            fillColor: feature.properties.couleur,
                            fillOpacity: 0.5
                        });
                        if (feature.properties.mutualise == 'OUI') {
                            var circle = L.circle([feature.properties.latitude, feature.properties.longitude], {
                                color: 'purple',
                                fillColor: '##BA55D3',
                                fillOpacity: 0.5,
                                radius: 50
                            });
                            group.addLayer(circle);
                        }

                        return carre
                    },

                    onEachFeature: onEachFeature,

                }));
                group.addTo(map);
                window['marks'] = group;
            })
            .catch(error => console.error('Erreur :', error));
    }

    var operateur = [];
    var region = [];
    var technologie = [];
    var type = [];
    var source = [];
    var t_mutualise = 'NON';
    var selectMultiples = document.querySelectorAll('select[multiple]');
    selectMultiples.forEach(function(select) {
        select.addEventListener('change', function() {

            if (select.name == 'operateur') {
                operateur = [];
            }
            if (select.name == 'region') {
                region = [];
            }
            if (select.name == 'technologie') {
                technologie = [];
            }
            if (select.name == 'type') {
                type = [];
            }
            if (select.name == 'source') {
                source = [];
            }

            for (var i = 0; i < select.selectedOptions.length; i++) {
                if (select.name == 'operateur') {
                    operateur.push(select.selectedOptions[i].value);
                }
                if (select.name == 'region') {
                    region.push(select.selectedOptions[i].value);
                }
                if (select.name == 'technologie') {
                    technologie.push(select.selectedOptions[i].value);
                }
                if (select.name == 'type') {
                    type.push(select.selectedOptions[i].value);
                }
                if (select.name == 'source') {
                    source.push(select.selectedOptions[i].value);
                }
            }
            // console.log(technologie);
            // console.log(region);
            search();

        });
    });

    var mutualise = document.getElementById('mutualise');
    mutualise.addEventListener('change', function() {
        if (this.checked) {
            t_mutualise = 'OUI';
        } else {
            t_mutualise = 'NON';
        }
        search();
    })

    function search() {
        $.ajax({
            type: 'GET',
            url: '/user/load',
            data: {
                'techno': technologie,
                'operateur': operateur,
                'region': region,
                'type': type,
                'source': source,
                'mutualise': t_mutualise
            },
            success: function(data) {
                // console.log(data);
                console.log(data[0]);
                showMarqueur(data[1]); //
                //    console.log("length" + data[0].length);
                var t_body = document.getElementById('table_body');
                t_body.innerHTML = '';
                data[0].forEach(function(d) {
                    var newRow = document.createElement('tr');

                    // Créez des cellules <td> pour la nouvelle ligne (ajoutez autant que nécessaire)
                    var cell1 = document.createElement('td');
                    cell1.textContent = d.infra.nom_site;
                    var cell2 = document.createElement('td');
                    cell2.textContent = d.infra.operateur.operateur;
                    // var cell3 = document.createElement('td');
                    // cell3.textContent = d.infra.latitude;
                    // var cell4 = document.createElement('td');
                    // cell4.textContent = d.infra.longitude;
                    var cell5 = document.createElement('td');
                    cell5.textContent = d.infra.commune.commune;
                    var cell6 = document.createElement('td');
                    cell6.textContent = d.infra.commune.district;
                    var cell7 = document.createElement('td');
                    cell7.textContent = d.infra.commune.region;
                    newRow.appendChild(cell2);
                    // newRow.appendChild(cell3);
                    // newRow.appendChild(cell4);
                    newRow.appendChild(cell1);


                    newRow.appendChild(cell5);
                    newRow.appendChild(cell6);
                    newRow.appendChild(cell7);

                    newRow.addEventListener('click', function() {
                        // Récupérez la latitude et la longitude à partir de la ligne cliquée
                        let latitude = d.infra.latitude;
                        let longitude = d.infra.longitude;
                        if (window['marquage'] != null) {


                            map.removeLayer(window['marquage']);
                        }
                        // Faites quelque chose avec les valeurs de latitude et de longitude
                        alert('Latitude : ' + latitude + ', Longitude : ' + longitude);
                        window['marquage'] = L.marker([latitude, longitude]);
                        window['marquage'].addTo(map);

                        let close = document.getElementById('close');
                        close.click();
                    });

                    t_body.appendChild(newRow);
                });

            },
            error: function(error) {
                console.log(error);

            }
        });
    }
</script>
@endsection