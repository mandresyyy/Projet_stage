@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="{{route('admin.dashboard')}}">Statistiques</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Tableau croisé</span>
</li>
@endsection
@section('contenu')
<script src="{{asset('pivottable/plotly-basic-latest.min.js.download')}}"></script>
<style id="plotly.js-style-global"></style>
<script type="text/javascript" src="{{asset('pivottable/jquery.min.js.download')}}"></script>
<script type="text/javascript" src="{{asset('pivottable/jquery-ui.min.js.download')}}"></script>
<script type="text/javascript" src="{{asset('pivottable/pivot.js.download')}}"></script>
<script type="text/javascript" src="{{asset('pivottable/plotly_renderers.js.download')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<link rel="stylesheet" href="{{asset('pivottable/pivot.min.css')}}">

<div class="row">
    <div class="col-md-12 col-sm-12" style="height:100% ">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Tableau croisé dynamique</span>
                    <span class="caption-helper"> ...</span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default" href="#" id="download">
                        <i class="fa fa-download"></i>
                    </a>
                    <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>
                </div>
            </div>
            <div class="pivotTable" id="pivotTable" style="height: 100%; overflow: auto;max-height:800px"></div>
        </div>
    </div>
</div>

<script>
    var derivers = $.pivotUtilities.derivers;
    var renderers = $.extend($.pivotUtilities.renderers,
        $.pivotUtilities.plotly_renderers);
    var rend_autorise = {
        "Tableau": renderers["Table"],
        "Tableau de chaleur": renderers['Heatmap'],
        "Bar": renderers["Bar Chart"],
        "Bar Horizontal": renderers["Horizontal Bar Chart"],
        "Camembert": renderers["Multiple Pie Chart"]
    }


    var rend_final = $.extend({}, rend_autorise);
    var data = @JSON($stats);
    const type = @JSON($type);
    var pvt;
    if (type == 0) {
        pvt = $("#pivotTable").pivotUI(data, {
            rows: ["region"], // "region","district","type","operateur","generation","source"
            cols: ["operateur"],
            showUI: true,
            aggregatorName: "Integer Sum",
            vals: ["nb"],
            renderers: rend_autorise,
            rendererName: "Tableau de chaleur"

        });
    } else if (type == 1) {
        pvt = $("#pivotTable").pivotUI(data, {
            rows: ["operateur"], // "region","district","type","operateur","generation","source"
            cols: ["generation"],
            //cols:["operateur"],
            showUI: true,
            aggregatorName: "Integer Sum",
            vals: ["nb"],
            renderers: rend_autorise,
            rendererName: "Tableau de chaleur"

        });
    } else if (type == 4) {
        pvt = $("#pivotTable").pivotUI(data, {
            rows: ["proprietaire"], // "region","district","type","operateur","generation","source"
            showUI: true,
            aggregatorName: "Integer Sum",
            vals: ["nb"],
            renderers: rend_autorise,
            rendererName: "Tableau"

        });
    } else {
        pvt = $("#pivotTable").pivotUI(data, {
            cols: ["type"], // "region","district","type","operateur","generation","source"
            rows: ["region"],
            showUI: true,
            aggregatorName: "Integer Sum",
            vals: ["nb"],
            renderers: rend_autorise,
            rendererName: "Tableau"

        });
    }



    // console.log(pvt);



    document.getElementById("download").addEventListener("click", function() {
        var selectElement = document.querySelector(".pvtRenderer");
        var selectedValue = selectElement.value;
        var html = '';
        if (selectedValue == 'Tableau' || selectedValue == 'Tableau de chaleur') {
            const divContent = document.getElementsByClassName("pvtTable");
            html = '<table style="width:100%" border="1">' + divContent[0].innerHTML + '</table>';
            pdf_tableau(html);
        } else {
            pdf_chart();

        }




    });

    function pdf_tableau(html) {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/pdf1', {
                method: 'POST', // ou 'GET' si vous utilisez GET
                headers: {
                    'Content-Type': 'application/json', // ou 'application/x-www-form-urlencoded' si nécessaire
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Si vous utilisez CSRF protection
                },
                body: JSON.stringify({
                    "table": html
                })
            })
            .then(function(response) {
                if (response.ok) {
                    // Traitement de la réponse en cas de succès
                    return response.json(); // Ou .json() si la réponse est en JSON
                } else {
                    // Gérer les erreurs de la requête
                    throw new Error('Erreur lors de la requête AJAX');
                }
            })
            .then(function(data) {
                window.location.href = data.pdfUrl;
                // console.log(data.pdfUrl);
            })
            .catch(function(error) {
                // Gérer les erreurs
                console.error(error);
            });
    }

    function pdf_chart() {
        var plotlyDiv = document.querySelector('.js-plotly-plot');

        // Utilisation de l'API toImage de Plotly pour convertir le graphique en une image base64
        Plotly.toImage(plotlyDiv, {
                format: 'png',
                width: 800,
                height: 600
            })
            .then(function(url) {
                var docDefinition = {
                    content: [{
                        image: url,
                        width: 500
                    }]
                };

                // Génération du PDF à l'aide de pdfmake
                pdfMake.createPdf(docDefinition).download('Graphe.pdf');
            });
    }
</script>
@endsection