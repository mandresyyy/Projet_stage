        function popup(feature,layer){
            var popupContent ='<table style="font-size:12px">';
            var popupContent = popupContent + '<tr><td><strong>Operateur :</td><td> ' + feature.properties.operateur  + '</td></tr>';
            var popupContent = popupContent + '<tr><td><strong>Date :</td> <td>' + feature.properties.date_releve + '</td></tr>';
            var popupContent = popupContent + '<tr><td><strong>Description :</td> <td>' + feature.properties.description + '</td></tr>';
            var popupContent = popupContent + '<tr><td><strong>Signal capté :</td><td> ' + feature.properties.capter  + '</td></tr>';
            var popupContent = popupContent + '<tr><td><strong>Technologie :</td><td> ' + feature.properties.technologie  + '</td></tr>';
            var popupContent = popupContent + '<tr><td><strong>Niveau de signal :</td><td> ' + feature.properties.level + '</td></tr>';
            var popupContent = popupContent + '<tr><td><strong>Vitesse :</td><td> ' + feature.properties.speed  + '</td></tr>';
            var popupContent = popupContent + '<tr><td><strong>Altitude :</td><td> ' + feature.properties.altitude  + '</td></tr></table>';
            layer.bindPopup(popupContent);
        }

        
            var map = L.map('map').setView([-18.8792, 46.3504], 8); // initialisation de la carte
            var tuile = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { // ajout tuile
                maxZoom: 25,
                attribution: '© OpenStreetMap'
            }).addTo(map);
           
            // var layerControl = L.control.layers().addTo(map);
            var contr = L.control.layers(null, null, {
                collapsed: false
            }).addTo(map);
            contr.setPosition('topleft');
            var layerControl = L.control.layers(null, null, {
                collapsed: false
            }).addTo(map);
           
            var group = L.layerGroup();

            function releveByoperateur(){
                for (let z = 0; z < operateur.length; z++) {
                    let groupop = L.layerGroup();
                    let nom_fichier="/geojson/releve/"+operateur[z].operateur+".geojson";
                    fetch(nom_fichier)
                        .then(response => response.json())
                        .then(data=>{
                            groupop.addLayer(L.geoJSON(data,{
                                pointToLayer:function(feature,latlng){
                                    return L.circleMarker(latlng,{
                                        color:feature.properties.couleur,
                                        fillColor:feature.properties.couleur,
                                        fillOpacity:1,
                                        radius:1.5,
                                        weight:1.5,
                                    });
                                },
                                onEachFeature:popup,
                            }));
                        })
                        .catch(error => console.error('Erreur :', error));
                    layerControl.addBaseLayer(groupop, operateur[z].operateur);
    
                }
    
            }
            
            function releveBytech(){
                for (let z = 0; z < tech.length; z++) {
                    let grouptech = L.layerGroup();
                    let nom_fichier="/geojson/releve/"+tech[z].generation+".geojson";
                    fetch(nom_fichier)
                        .then(response => response.json())
                        .then(data=>{
                            grouptech.addLayer(L.geoJSON(data,{
                                pointToLayer:function(feature,latlng){
                                    return L.circleMarker(latlng,{
                                        color:feature.properties.couleur,
                                        fillColor:feature.properties.couleur,
                                        fillOpacity:0,
                                        radius:3,
                                        weight:0.5,
                                    });
                                },
                                onEachFeature:popup,
                            }));
                        })
                        .catch(error => console.error('Erreur :', error));
                    layerControl.addBaseLayer(grouptech, tech[z].generation);
    
                }
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
              
            // window.onload = () => {
                var c1 = 'region';
                var c2 = 'district';
             
               showCouche(c1);   
               showCouche(c2);
            // };
               
               

            function onEachFeature(feature, layer) {
                if (feature.properties) {
                    var contenu = '<center><img src="../../login/logo_artec.png" alt="tsita" style="width:50px"></center>';
                    var contenu = contenu + '<img src="../../storage/photos/' + feature.properties.logo + '" style="width:20px;float:right">';
                    var contenu = contenu + '<table style="font-size:12px">';
                    var contenu = contenu + '<tr><td><strong>Operateur :</td> <td>' + feature.properties.operateur + '</td></tr>';
                    var contenu = contenu + '<tr><td><strong>Nom du site :</td><td> ' + feature.properties.nom_site + '</td></tr>';
                    var contenu = contenu + '<tr><td><strong>Technologie :</td> <td>' + feature.properties.technologie_generation + '</td></tr>';
                    var contenu = contenu + '<tr><td><strong>Details :</td> <td>' + feature.properties.details + '</td></tr>';                    
                    var contenu = contenu + '<tr><td><strong>Hauteur antenne  (m):</td><td>' + feature.properties.hauteur_antenne + ' </td></tr>';
                    var contenu = contenu + '<tr><td><strong>Largeur des canaux occupés (MHz) :</td><td>' + feature.properties.largeur_canaux + ' </td></tr>';
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

            function show_infra() {
                var marks = "/storage/geojson/infrastructure.geojson";


                fetch(marks)
                    .then(response => response.json())
                    .then(data => {
                        // console.log(data);
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

                    })
                    .catch(error => console.error('Erreur :', error));
                layerControl.addOverlay(group, "Infrastructures");

            }


           
            show_infra();
            releveByoperateur();
            releveBytech();
            show_legend();
            function show_legend(){
                 var customContainer = L.DomUtil.create('div', 'custom-container');
            customContainer.innerHTML = '<div class="custom-info">' +
                '<span style="background-color: #0f1112; width: 5px; height: 5px; display: inline-block; margin-right: 5px; border-radius: 100%;"></span>' +
                '-200 - -120' +
                '</div>' + '<div class="custom-info">' +
                '<span style="background-color: #a3b7c2; width: 5px; height: 5px; display: inline-block; margin-right: 5px; border-radius: 100%;"></span>' +
                '-120 - -111' +
                '</div>' +
                '</div>' + '<div class="custom-info">' +
                '<span style="background-color: #0374b0; width: 5px; height: 5px; display: inline-block; margin-right: 5px; border-radius: 100%;"></span>' +
                '-111 - -101' +
                '</div>' +
                '</div>' + '<div class="custom-info">' +
                '<span style="background-color: #81dae6; width: 5px; height: 5px; display: inline-block; margin-right: 5px; border-radius: 100%;"></span>' +
                '-101 - -91' +
                '</div>' +
                '</div>' + '<div class="custom-info">' +
                '<span style="background-color: green; width: 5px; height: 5px; display: inline-block; margin-right: 5px; border-radius: 100%;"></span>' +
                '-91 - -81' +
                '</div>' +
                '</div>' + '<div class="custom-info">' +
                '<span style="background-color: yellow; width: 5px; height: 5px; display: inline-block; margin-right: 5px; border-radius: 100%;"></span>' +
                '-81 - -71' +
                '</div>' +
                '</div>' + '<div class="custom-info">' +
                '<span style="background-color: orange; width: 5px; height: 5px; display: inline-block; margin-right: 5px; border-radius: 100%;"></span>' +
                '-71 - -50' +
                '</div>';

                contr.getContainer().appendChild(customContainer);

            }
           

        
