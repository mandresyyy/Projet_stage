var label = [];
var data = [];
var colors = [];
for (let i = 0; i < byoperateur.length; i++) {
    label.push(byoperateur[i].operateur);
    data.push(byoperateur[i].nb);
    colors.push(byoperateur[i].couleur);
}

var ctx = document.getElementById('byOperateur').getContext('2d');
var chart = new Chart(ctx, {
    type: "line", // pie bar line doughnut 
    data: {
        labels: label,
        datasets: [{
            label: 'nombre',
            data: data,
            backgroundColor: colors,
            borderColor: colors,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        categorySpacing: 0.2,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false // Masquer les légendes de couleur dans le cas de la version Chart.js 3.x
            }
        }
    },
});



// Initialisez un tableau vide pour les étiquettes
var labels = [];

// Remplissez le tableau des étiquettes avec les opérateurs
for (let i = 0; i < techno.length; i++) {
    labels.push(techno[i].generation);
}
// console.log(labels);
// Obtenez le contexte du graphique
var ctx = document.getElementById('techbyoperateur').getContext('2d');

// Créez un objet de configuration pour le graphique
var config = {
    type: 'bar',
    data: {
        labels: labels,
        datasets: []
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        categorySpacing: 0.2,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
};

// Créez le graphique avec la configuration
var chart = new Chart(ctx, config);

for (let x = 0; x < operateur.length; x++) {
    var datasetData = []; // Initialisez un tableau pour les données du dataset actuel
    for (let y = 0; y < techno.length; y++) {
        datasetData.push(techbyoperateur[x][y].nb);
    }

    let nouveauDataset = {
        label: operateur[x].operateur,
        data: datasetData,
        backgroundColor: operateur[x].couleur,
        borderColor: operateur[x].couleur,
        borderWidth: 1
    };

    // Ajoutez le dataset au graphique
    config.data.datasets.push(nouveauDataset);
}


// Mettez à jour le graphique une fois que tous les datasets sont ajoutés
chart.update();





var label_proprio = [];
var data_proprio = [];
for (let i = 0; i < proprio.length; i++) {
    label_proprio.push(proprio[i].proprietaire);
    data_proprio.push(proprio[i].nb);
}


var ctx_proprio=document.getElementById('proprio').getContext('2d');
var chart_proprio = new Chart(ctx_proprio, {
    type: "doughnut", // pie bar line doughnut 
    data: {
        labels: label_proprio,
        datasets: [{
            label: 'nombre',
            data: data_proprio,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        categorySpacing: 0.2,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: true // Masquer les légendes de couleur dans le cas de la version Chart.js 3.x
            }
        }
    },
});

var label_type = [];
var data_type = [];
for (let i = 0; i < type.length; i++) {
    label_type.push(type[i].type);
    data_type.push(type[i].nb);
}


var ctx_type=document.getElementById('type').getContext('2d');
var chart_type = new Chart(ctx_type, {
    type: "pie", // pie bar line doughnut 
    data: {
        labels: label_type,
        datasets: [{
            label: 'nombre',
            data: data_type,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        categorySpacing: 0.2,
        scales: {
            y: {
                display:true
            }
        },
        plugins: {
            legend: {
                display: true // Masquer les légendes de couleur dans le cas de la version Chart.js 3.x
            }
        }
    },
});