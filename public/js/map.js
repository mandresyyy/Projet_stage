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
  

    // var marks = "/geojson/testa.geojson";
    
    if (window['marks'] != null) {
        // console.log("misy");
        window['marks'].clearLayers();
        map.removeLayer(window['marks']);
    }

   
    // fetch(marks)
    //     .then(response => response.json())
    //     .then(data => {

    group.addLayer(L.geoJSON(json_name, {
                pointToLayer: function (feature, latlng) {
                    let lon = parseFloat(latlng.lng);
                    let lat = parseFloat(latlng.lat);
                    let demi = 0.005;
                    if (isNaN(lon) || isNaN(lat)) {
                        console.log(feature);
                    }
                    let supgauche = [lat + demi, lon - demi];
                    let supdroite = [lat - demi, lon + demi];
                    var carre = L.rectangle([supgauche, supdroite], {
                        color: feature.properties.couleur,
                        fillColor: feature.properties.couleur,
                        fillOpacity: 0.5
                    });
                    if (feature.properties.mutualise == 'OUI') {
                        var circle = L.circle([lat, lon], {
                            color: 'purple',
                            fillColor: '##BA55D3',
                            fillOpacity: 0.5,
                            radius: 25
                        });
                        group.addLayer(circle);
                    }
                   return carre
                    
                },

                onEachFeature: onEachFeature,

            }));
        // })
        // .catch(error => console.error('Erreur :', error));
    group.addTo(map);
    window['marks'] = group;
}

function showCouche(json_name) {
    var marks = "/geojson/"+json_name+".topojson"; 
    var legende='';
    var LayerGroup = L.featureGroup();

    if(json_name=='region'){
        legende='<span style="background-color: transparent; width: 15px; height: 15px; display: inline-block; margin-right: 5px; border: 3px solid green;"></span>Region';

        var option = {
            color: 'green', 
            fillOpacity:0,
            weight: 2
        };
    
        fetch(marks)
            .then(response => response.json())
            .then(topojsonData => {
                const geojson = topojson.feature(topojsonData, topojsonData.objects.collection);
    
                LayerGroup.addLayer(L.geoJSON(geojson,{
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
                }))
            })
            .catch(error => console.error('Erreur :', error));
    
            layerControl.addOverlay(LayerGroup,legende);
    }

    else{
        legende='<span style="background-color: transparent; width: 15px; height: 15px; display: inline-block; margin-right: 5px; border: 3px solid blue;"></span>District';

        var option = {
            color: 'blue', 
            fillOpacity:0,
            weight:1
        };
    
        fetch(marks)
            .then(response => response.json())
            .then(topojsonData => {
                const geojson = topojson.feature(topojsonData, topojsonData.objects.collection);
    
                LayerGroup.addLayer(L.geoJSON(geojson,{
                    style:option,
                    onEachFeature:function(feature,layer){
                        var contenu = '<table>';
                        var contenu = contenu + '<tr><td><strong>Code district :</td> <td>' + feature.properties.CODEDIST + '</td></tr>';
                        var contenu = contenu + '<tr><td><strong>District :</td><td> ' + feature.properties.NOMDIST + '</td></tr></table>';
                        layer.bindPopup(contenu);
                        var customLabelIcon = L.divIcon({
                            className: 'custom-label-icon', 
                            iconSize: [70, 40], 
                            html: '<div class="custom-label" style="color:blue">' + feature.properties.NOMDIST + '</div>', 
                        });
                        LayerGroup.addLayer(L.marker(layer.getBounds().getCenter(), { icon: customLabelIcon }));
                    }
                }))
            })
            .catch(error => console.error('Erreur :', error));
    
            layerControl.addOverlay(LayerGroup,legende);
    }
    
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
            // console.log(JSON.parse(data[1]));
            showMarqueur(JSON.parse(data[1])); //
            data[0].forEach(function (d) {
                var rowData = [
                    d.infra.nom_site,
                    d.infra.operateur.operateur,
                    d.infra.commune.commune,
                    d.infra.commune.district,
                    d.infra.commune.region,
                    d.infra.latitude,
                    d.infra.longitude,
                ];
            
                dataTable.row.add(rowData).draw();
            });
            

        },
        error: function (error) {
            console.log(error);

        }
    });

    dataTable.on('click', 'tr', function () {
        var data = dataTable.row(this).data();
    
        // Récupérez la latitude et la longitude à partir des données de la ligne
        var latitude = data[5]; // Remplacez 5 par l'index de la colonne contenant la latitude
        var longitude = data[6]; // Remplacez 6 par l'index de la colonne contenant la longitude
    
        if (window['marquage'] != null) {
                    map.removeLayer(window['marquage']);
        }
        
                // Faites quelque chose avec les valeurs de latitude et de longitude
                window['marquage'] = L.marker([latitude, longitude]);
                window['marquage'].addTo(map);
                map.flyTo([latitude, longitude], 12, { duration: 1.5 });
        
                let close = document.getElementById('close');
                close.click();
        
        // Utilisez les valeurs de latitude et de longitude comme nécessaire
        // console.log('Latitude : ' + latitude + ', Longitude : ' + longitude);
    });
}



function loadGeoJSONData(url) {
  return new Promise((resolve, reject) => {
    fetch(url)
      .then(response => {
        if (!response.ok) {
          reject(new Error(`Erreur de chargement du fichier : ${response.statusText}`));
          return;
        }
        return response.json();
      })
      .then(data => {
        // console.log(data);
        resolve(data);
      })
      .catch(error => {
        reject(error);
      });
  });
}




    var c1 = 'region';
     var c2 = 'district';
    
    showCouche(c1);   
    showCouche(c2);
    