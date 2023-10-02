
        window.onload = function() {
            var map = L.map('map').setView([-18.8792, 46.3504], 8); // initialisation de la carte
            var tuile = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { // ajout tuile
                maxZoom: 25,
                attribution: '© OpenStreetMap'
            }).addTo(map);
           
            // var layerControl = L.control.layers().addTo(map);
            var layerControl = L.control.layers(null, null, {
                collapsed: false
            }).addTo(map);
            var group = L.layerGroup();

            // console.log(operateur[0]['operateur']);

            // // console.log(signal[0][0]['Longitude']);
            for (let z = 0; z < operateur.length; z++) {
                let op = operateur[z]['operateur'];
                var group = L.layerGroup();
                console.log(signal[op]);
                for (let i = 0; i < signal[op].length; i++) {
                    for (let x = 0; x < signal[op][i].length; x++) {
                        // console.log(signal[i][x]);
                        let mark = L.circle([signal[op][i][x]['Latitude'], signal[op][i][x]['Longitude']], {
                            color: signal[op][i][x]['couleur'],
                            fillColor: signal[op][i][x]['couleur'],
                            fillOpacity: 1,
                            radius: 3,
                            weight: 3,
                            // dashArray: '5.5'
                        });
                    var popupContent ='<table style="font-size:12px">';
                    var popupContent = popupContent + '<tr><td><strong>Date :</td> <td>' + signal[op][i][x]['Timestamp'].split("_")[0] + '</td></tr>';
                    var popupContent = popupContent + '<tr><td><strong>Operateur :</td><td> ' + signal[op][i][x]['Operatorname']  + '</td></tr>';
                    var popupContent = popupContent + '<tr><td><strong>Technologie :</td><td> ' + signal[op][i][x]['NetworkTech']  + '</td></tr>';
                    var popupContent = popupContent + '<tr><td><strong>Niveau de signal :</td><td> ' + signal[op][i][x]['Level']  + '</td></tr>';
                    var popupContent = popupContent + '<tr><td><strong>Vitesse :</td><td> ' + signal[op][i][x]['Speed']  + '</td></tr>';
                    var popupContent = popupContent + '<tr><td><strong>Altitude :</td><td> ' + signal[op][i][x]['Altitude']  + '</td></tr></table>';
                        // Associez le popup au cercle
                        mark.bindPopup(popupContent);

                        // Ajoutez un gestionnaire d'événements 'click' au cercle
                        mark.on('click', function(event) {
                            // Ouvrez le popup lorsque le cercle est cliqué
                            mark.openPopup();
                        });
                        group.addLayer(mark);
                    }
                    //    console.log(i);
                }
                layerControl.addOverlay(group, op);
                // group.addTo(map);

            }

            function show_couche(nom_couche) {
                var nom_fichier = '/geojson/' + nom_couche + '.json';
                var LayerGroup = L.featureGroup();
                var legende = '';
                if (nom_couche == 'Rgion') {
                    var option = {
                        color: 'green',
                        fillOpacity: 0,
                        weight: 3
                    };
                    legende = '<span style="background-color: transparent; width: 15px; height: 15px; display: inline-block; margin-right: 5px; border: 3px solid green;"></span>Region';
                    var couche = L.geoJSON(json_Rgion_1, {
                        style: option,
                        onEachFeature: function(feature, layer) {
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
                            LayerGroup.addLayer(L.marker(layer.getBounds().getCenter(), {
                                icon: customLabelIcon
                            }));
                        }
                    });

                } else {
                    var option = {
                        fillOpacity: 0,
                        color: 'blue',
                        weight: 1
                    };
                    legende = '<span style="background-color: transparent; width: 15px; height: 15px; display: inline-block; margin-right: 5px; border: 3px solid blue;"></span>District';
                    var couche = L.geoJSON(json_District_2, {
                        style: option,
                        onEachFeature: function(feature, layer) {
                            // console.log(feature.properties.NOMDIST);
                            var contenu = '<table>';
                            var contenu = contenu + '<tr><td><strong>Code region :</td> <td>' + feature.properties.CODEDIST + '</td></tr>';
                            var contenu = contenu + '<tr><td><strong>District :</td><td> ' + feature.properties.NOMDIST + '</td></tr></table>';
                            layer.bindPopup(contenu);
                            var customLabelIcon = L.divIcon({
                                className: 'custom-label-icon',
                                iconSize: [70, 40],
                                html: '<div class="custom-label" style="color:blue">' + feature.properties.NOMDIST + '</div>',
                            });
                            LayerGroup.addLayer(L.marker(layer.getBounds().getCenter(), {
                                icon: customLabelIcon
                            }));
                        }
                    });
                }
                LayerGroup.addLayer(couche);
                layerControl.addOverlay(LayerGroup, legende);

            }

            function onEachFeature(feature, layer) {
                if (feature.properties) {

                    var contenu = '<center><img src="../login/logo_artec.png" alt="tsita" style="width:50px"></center>';
                    var contenu = contenu + '<img src="../storage/photos/' + feature.properties.logo + '" style="width:20px;float:right">';
                    var contenu = contenu + '<table style="font-size:12px">';
                    var contenu = contenu + '<tr><td><strong>Operateur :</td> <td>' + feature.properties.operateur + '</td></tr>';
                    var contenu = contenu + '<tr><td><strong>Nom du site :</td><td> ' + feature.properties.nom_site + '</td></tr>';
                    var contenu = contenu + '<tr><td><strong>Technologie :</td> <td>' + feature.properties.technologie_generation + '</td></tr>';
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
                layerControl.addOverlay(group, "Infrastructure");

            }


            var c1 = 'Rgion';
            var c2 = 'District_2';
            show_infra();
            show_couche(c2);
            show_couche(c1);
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

            layerControl.getContainer().appendChild(customContainer);


        }
