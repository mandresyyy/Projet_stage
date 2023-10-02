
    document.addEventListener("DOMContentLoaded", function() {
        var tabs = document.querySelectorAll(".tab-pane");
        var currentTab = 0; // Index de l'onglet actuel
        var progressBar = document.getElementById("progress-bar");
        var progressPercentage = ((currentTab + 1) / tabs.length) * 100; // +1 car les indices commencent à zéro
        progressBar.style.width = progressPercentage + "%";
        progressBar.style.backgroundColor = "#00cad1";

        function showTab(index) {
            if (index >= 0 && index < tabs.length) {
                tabs[currentTab].classList.remove("active");
                tabs[index].classList.add("active");
                currentTab = index;
                progressPercentage = ((currentTab + 1) / tabs.length) * 100; // +1 car les indices commencent à zéro
                progressBar.style.width = progressPercentage + "%";
            }
        }

        var nextButton = document.querySelector(".button-next");
        var prevButton = document.querySelector(".button-previous");
        var submit = document.querySelector(".button-submit");
        var element = document.querySelector('.form-control-static[data-display="code_site"]');
        var nom_s = document.querySelector('.form-control-static[data-display="nom_site"]');
        var oper = document.querySelector('.form-control-static[data-display="operateur"]');
        var prop = document.querySelector('.form-control-static[data-display="proprio"]');
        var annee = document.querySelector('.form-control-static[data-display="annee"]');
        var com = document.querySelector('.form-control-static[data-display="commune"]');
        var lon = document.querySelector('.form-control-static[data-display="long"]');
        var lat = document.querySelector('.form-control-static[data-display="lat"]');
        var type = document.querySelector('.form-control-static[data-display="type"]');
        var mutualise = document.querySelector('.form-control-static[data-display="mutualise"]');
        var coloc = document.querySelector('.form-control-static[data-display="coloc"]');
        var hauteur = document.querySelector('.form-control-static[data-display="hauteur"]');
        var largeur = document.querySelector('.form-control-static[data-display="largeur"]');
        var info = document.querySelector('.form-control-static[data-display="info"]');
        var src = document.querySelector('.form-control-static[data-display="source"]');
        var tech = document.querySelector('.form-control-static[data-display="techno"]');
        var form = document.getElementById('submit_form');
       
        submit.addEventListener('click', function(){
            form.submit();
        })
        nextButton.addEventListener("click", function() {
            showTab(currentTab + 1);
            if (currentTab + 1 == tabs.length) {
              
                var formData = new FormData(form);
                fetch("/infra/display", {
                        method: "post",
                         body: formData
                    })
                    .then(response => response.json()) // Traitez la réponse si nécessaire
                    .then(data => {
                        
                    

                    element.textContent = data['code_site'];
                    nom_s.textContent=data['nom_site'];
                    oper.textContent=data['operateur'];
                    prop.textContent=data['proprietaire'];
                    annee.textContent=data['annee'];
                    com.textContent=data['commune'];
                    lon.textContent=data['longitude'];
                    lat.textContent=data['latitude'];
                    type.textContent=data['type_site'];
                    mutualise.textContent=data['mutualise'];
                    coloc.textContent=data['coloc'];
                    hauteur.textContent=data['hauteur'];
                    largeur.textContent=data['largeur_canaux'];
                    info.textContent=data['technologie_generation'];
                    src.textContent=data['source'];
                    tech.textContent=data['techno'];
                    info.textContent=data['info_techno'];
                        console.log(data); // Affichez la réponse du serveur
                       
                    })
                    .catch(error => {
                        console.error("Erreur : " + error);
                    });

                    submit.removeAttribute("disabled");
                    nextButton.setAttribute("disabled", "true");
            }
        });
        

        prevButton.addEventListener("click", function() {
            showTab(currentTab - 1);
        });
        var mutualiseRadio = document.querySelectorAll("input[name='mutualise']");
        var colocSelect = document.querySelector("select[name='coloc']");

        // Fonction pour mettre à jour le champ "Colocataire"
        function updateColocField() {
            var mutualiseValue = Array.from(mutualiseRadio).find(radio => radio.checked).value;

            if (mutualiseValue === "0") {
                // console.log("ato");
                colocSelect.value = "1";
                colocSelect.disabled = true;
                
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

    
    var proprioInput = document.getElementById('proprio');
    var suggestionsprop = document.getElementById("sugg_prop");
    proprioInput.addEventListener("input", function() {
        var proprio = proprioInput.value;
        fetch("/admin/auto/proprio?prop=" + proprio) // suggestion
            .then(function(response) {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error("Erreur lors de la requête AJAX");
                }
            })
            .then(function(data) {
                suggestionsprop.innerHTML = "";
                // Remplissez la liste de suggestions avec les nouvelles suggestions
                data.forEach(function(suggestion) {
                    var option = document.createElement("option");
                    option.value = suggestion;
                    // console.log(data);
                    suggestionsprop.appendChild(option);
                });
            })
            .catch(function(error) {
                console.error(error);
            });
    });
    var codeCommuneInput = document.getElementById("code_commune");
    var CommuneInput = document.getElementById("commune");
    var suggestionsList = document.getElementById("suggestions");
    CommuneInput.addEventListener("input", function() {
        var Commune = CommuneInput.value;
        fetch("/admin/auto?term=" + Commune) // suggestion
            .then(function(response) {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error("Erreur lors de la requête AJAX");
                }
            })
            .then(function(data) {
                suggestionsList.innerHTML = "";
                
                data.forEach(function(suggestions) {
                    var options = document.createElement("option");
                    options.value = suggestions;
                    suggestionsList.appendChild(options);
                });
            })
            .catch(function(error) {
                console.error(error);
            });

        
        fetch("/admin/commune/region?commune=" + Commune)
            .then(response => response.json())
            .then(data => {
                codeCommuneInput.value = data;
                
            })
            .catch(error => {
                codeCommuneInput.value = "Non trouvé";
            });
    });

    });
