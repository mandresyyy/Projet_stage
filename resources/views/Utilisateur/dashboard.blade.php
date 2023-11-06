@extends("Utilisateur.Layouts.master")
@section('nav')


<li>
    <a href="{{route('user.dashboard')}}">Statistiques</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Dashboard</span>
</li>
@endsection
@section('contenu')

<script src="{{asset('js/chart.js')}}"></script>


<style>
.page-bar{
    display: none;
}

</style>
<div class="row widget-row" >
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading">Infrastructures</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green fa fa-satellite-dish"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$nbInfra->nb}}">{{$nbInfra->nb}}</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading">Operateurs</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-red fa fa-phone"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$nbOperateur->nb}}">0</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading">Technologies</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-blue fa fa-signal"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$nbTechnologie->nb}}">0</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
    <div class="col-md-3">
        <!-- BEGIN WIDGET THUMB -->
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading">Mutualises</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-purple fa fa-share-alt"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="{{$mutualise}}">0</span>
                </div>
            </div>
        </div>
        <!-- END WIDGET THUMB -->
    </div>
</div>

<div class="row">
<div class="col-md-8 col-sm-8" style="height:100% ">
        <div class="portlet light bordered" style="height: 100%; overflow: 0;">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Operateur </span>
                    <span class="caption-helper">...</span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default" href="{{route('user.pivot',['type'=>1])}}">
                        <i class="fa fa-random"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <canvas id="techbyoperateur"></canvas>
            </div>
        </div>
</div>
    <div class="col-md-4 col-sm-4" style="height:100% ">
        <div class="portlet light bordered" style="height: 100%; overflow: 0;">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Infrastructure </span>
                    <span class="caption-helper">...</span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default" href="{{route('user.pivot',['type'=>0])}}">
                        <i class="fa fa-random"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <canvas id="byOperateur"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
<div class="col-md-6 col-sm-6" style="height:100% ">
        <div class="portlet light bordered" style="height: 100%; overflow: auto;">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Type </span>
                    <span class="caption-helper">...</span>
                </div>
                <div class="actions">
                <a class="btn btn-circle btn-icon-only btn-default" href="{{route('user.pivot',['type'=>5])}}">
                        <i class="fa fa-random"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
            <canvas id="type" class="portlet-body"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6" style="height:100% ">
        <div class="portlet light bordered" style="height: 100%; overflow: auto;">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-dark">Proprietaire </span>
                    <span class="caption-helper"> ...</span>
                </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default" href="{{route('user.pivot',['type'=>4])}}">
                        <i class="fa fa-random"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body" style="max-height: 315px;overflow:auto">
            <canvas id="proprio" class="portlet-body"></canvas>
            </div>
        </div>
    </div>
    
    
</div>
<div class="row">
    
   
</div>
<script>
    const byoperateur = @JSON($stats);
    const techbyoperateur = @JSON($byTech);
    const operateur = @JSON($operateur);
    const techno = @JSON($techno);
    const mutualise = @JSON($mutualise);
    const proprio=@JSON($proprio);
    const type=@JSON($type);
    //   console.log(byoperateur);

   // Obtenez tous les éléments avec la classe "widget-thumb-body-stat"
var statElements = document.querySelectorAll('.widget-thumb-body-stat');

// Définissez la vitesse de l'animation (en millisecondes par incrément)
var animationSpeed = 0.5;

// Définissez l'incrément initial
var increment = 1;

// Définissez la fonction pour animer le compteur
function animateCounter(element, finalValue) {
  var currentValue = parseInt(element.textContent);
  if (currentValue < finalValue) {
    element.textContent = currentValue + increment;
    setTimeout(function() {
      animateCounter(element, finalValue);
    }, animationSpeed);
  } else {
    element.textContent = finalValue;
  }
}

// Parcourez tous les éléments et lancez l'animation pour chacun
statElements.forEach(function(element) {
  var finalValue = parseInt(element.getAttribute('data-value'));
  animateCounter(element, finalValue);
});


</script>
<script src="{{asset('js/Stats.js')}}"></script>



@endsection