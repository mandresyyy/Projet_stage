window.onload = function () {
var map = L.map('map').setView([-18.8792, 46.3504], 6); // initialisation de la carte
var tuile = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { // ajout tuile
    maxZoom: 25,
    attribution: '© OpenStreetMap'
}).addTo(map);
var group = L.layerGroup();
var group_d = L.layerGroup();


// var layerControl = L.control.layers().addTo(map);
var layerControl = L.control.layers(null, null, {
    collapsed: false 
}).addTo(map);
function onEachFeature(feature, layer) {
    if (feature.properties) {

        var contenu = '<center><img src="../login/logo_artec.png" alt="tsita" style="width:50px"></center>';
        var contenu = contenu + '<img src="../storage/photos/' + feature.properties.logo + '" style="width:20px;float:right">';
        var contenu = contenu + '<table style="font-size:12px">';
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
        var contenu = contenu + '<tr><td><strong>Commune :</td><td>' + feature.properties.commune + ' </td></tr>'; 
        var contenu = contenu + '<tr><td><strong>District :</td><td>' + feature.properties.district + ' </td></tr>';   
        var contenu = contenu + '<tr><td><strong>Region :</td><td>' + feature.properties.region + ' </td></tr>';   
        var contenu = contenu + '</table>';
        var config = {
            maxWidth: 500, // Largeur maximale du popup en pixels
            minWidth: 300, // Largeur minimale du popup en pixels
            offset: [0, -0]
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
                pointToLayer: function (feature, latlng) {
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

        })
        .catch(error => console.error('Erreur :', error));
    group.addTo(map);
    window['marks'] = group;
}

var operateur = [];
var region = [];
var technologie = [];
var type = [];
var source = [];
var t_mutualise = 'tous';
var selectMultiples = document.querySelectorAll('select[multiple]');
selectMultiples.forEach(function (select) {
    select.addEventListener('change', function () {

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
mutualise.addEventListener('change', function () {
    t_mutualise = this.value;
    //    console.log(t_mutualise);
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
        success: function (data) {
            // console.log(data);
            console.log(data[0]);
            showMarqueur(data[1]); //
            //    console.log("length" + data[0].length);
            var t_body = document.getElementById('table_body');
            t_body.innerHTML = '';
            data[0].forEach(function (d) {
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

                newRow.addEventListener('click', function () {
                    // Récupérez la latitude et la longitude à partir de la ligne cliquée
                    let latitude = d.infra.latitude;
                    let longitude = d.infra.longitude;
                    if (window['marquage'] != null) {


                        map.removeLayer(window['marquage']);
                    }
                    // Faites quelque chose avec les valeurs de latitude et de longitude
                    // alert('Latitude : ' + latitude + ', Longitude : ' + longitude);
                    window['marquage'] = L.marker([latitude, longitude]);
                    window['marquage'].addTo(map);
                    map.flyTo([latitude, longitude], 7, { duration: 1.5 });

                    let close = document.getElementById('close');
                    close.click();
                });

                t_body.appendChild(newRow);
            });

        },
        error: function (error) {
            console.log(error);

        }
    });
}

function show_couche(nom_couche) {
    var nom_fichier = '/geojson/' + nom_couche + '.json';
    var LayerGroup = L.featureGroup();
    var legende='';
    if (nom_couche == 'Rgion') {
        var option = {
            color: 'green', 
            fillOpacity:0,
            weight: 3
        };
        legende='<span style="background-color: transparent; width: 15px; height: 15px; display: inline-block; margin-right: 5px; border: 3px solid green;"></span>Region';
        var couche = L.geoJSON(json_Rgion_1,{
            style:option,
            onEachFeature:function(feature,layer){
                // console.log(feature.properties.NOMREGION);
                var contenu = '<table>';
                var contenu = contenu + '<tr><td><strong>Code region :</td> <td>' + feature.properties.CODEREG + '</td></tr>';
                var contenu = contenu + '<tr><td><strong>Region :</td><td> ' + feature.properties.NOMREGION + '</td></tr></table>';
                
                layer.bindPopup(contenu);
                var customLabelIcon = L.divIcon({
                    className: 'custom-label-icon', 
                    iconSize: [70, 40], 
                    html: '<div class="custom-label" style="color:green">' + feature.properties.NOMREGION + '</div>', 
                });
                LayerGroup.addLayer(L.marker(layer.getBounds().getCenter(), { icon: customLabelIcon }));
            }
        });
        
    }
    else {
        var option = {
            fillOpacity:0,
            color:'blue',
            weight: 1
        };
        legende='<span style="background-color: transparent; width: 15px; height: 15px; display: inline-block; margin-right: 5px; border: 3px solid blue;"></span>District';
        var couche = L.geoJSON(json_District_2,{
            style:option,
            onEachFeature:function(feature,layer){
                // console.log(feature.properties.NOMDIST);
                var contenu =  '<table>';
                var contenu = contenu + '<tr><td><strong>Code region :</td> <td>' + feature.properties.CODEDIST + '</td></tr>';
                var contenu = contenu + '<tr><td><strong>District :</td><td> ' + feature.properties.NOMDIST + '</td></tr></table>';
                layer.bindPopup(contenu);
                var customLabelIcon = L.divIcon({
                    className: 'custom-label-icon', 
                    iconSize: [70, 40], 
                    html: '<div class="custom-label" style="color:blue">' + feature.properties.NOMDIST + '</div>', 
                });
                LayerGroup.addLayer(L.marker(layer.getBounds().getCenter(), { icon: customLabelIcon }));
            }
        });
    }
    LayerGroup.addLayer(couche);
    layerControl.addOverlay(LayerGroup,legende);

   
}

    var c1 = 'Rgion';
    var c2 = 'District_2';
    show_couche(c2);
    show_couche(c1);   
}

