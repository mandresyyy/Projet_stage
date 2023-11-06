const fs = require('fs');
const topojson = require('topojson');
// console.log(topojson)

const geojsonPath = process.argv[2];
const outputpath=process.argv[3];
// Charger le fichier GeoJSON
const geojsonData = JSON.parse(fs.readFileSync(geojsonPath));

// Convertir en TopoJSON
const topojsonData = topojson.topology({ collection: geojsonData });

// Enregistrer le fichier TopoJSON
fs.writeFileSync(outputpath, JSON.stringify(topojsonData));
// console.log('mety');