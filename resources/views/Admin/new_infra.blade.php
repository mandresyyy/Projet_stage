@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="{{route('infra.liste')}}">Infrastructure</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Ajout </span>
</li>
@endsection
@section('contenu')
<script src="{{asset('js/infra_js.js')}}"></script>
<div class="row">
    <div class="col-md-12">

        <div class="portlet light bordered" id="form_wizard_1">
            <div class="portlet-title">
                <div class="caption">
    
                    <span class="caption-subject font-red bold uppercase"> Ajout infrastructure
                        
                    </span>
                </div>

            </div>
            <div class="portlet-body form">
                <form class="form-horizontal" action="{{route('infra.save')}}" id="submit_form" method="POST">
                    @csrf
                    <div class="form-wizard">
                        <div class="form-body">

                            <ul class="nav nav-pills nav-justified steps">
                                <li>
                                    <a href="#" data-toggle="tab" class="step">
                                        <span class="number"> 1 </span>
                                        <span class="desc">
                                            <i class="fa fa-check"></i> Information génerale </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-toggle="tab" class="step">
                                        <span class="number"> 2 </span>
                                        <span class="desc">
                                            <i class="fa fa-check"></i> Paramètre </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-toggle="tab" class="step active">
                                        <span class="number"> 3 </span>
                                        <span class="desc">
                                            <i class="fa fa-check"></i> Information technique </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-toggle="tab" class="step">
                                        <span class="number"> 4 </span>
                                        <span class="desc">
                                            <i class="fa fa-check"></i> Confirmation </span>
                                    </a>
                                </li>
                            </ul>
                            <div id="progress-bar" class="progress progress-striped" role="progressbar">
                                <div class="progress-bar progress-bar-success"> </div>
                            </div>

                            <div class="tab-content">

                                <div class="tab-pane active" id="tab1">
                                    <h3 class="block">Information génerale</h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Code site
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" id="code_site" class="form-control" name="code_site" value="{{ old('code_site') }}" required/>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Nom site
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" id="nom_site" class="form-control" name="nom_site" value="{{ old('nom_site') }}" required/>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Operateur
                                        <span class="required"> * </span></label>
                                        <div class="col-md-4">
                                            <select name="operateur" id="country_list" class="form-control" ">
                                                @foreach($listeop as $op)
                                                    @if($op->operateur!='Non defini')
                                                        <option value="{{$op->id}}" {{ old('operateur') == $op->id ? 'selected' : '' }}>{{$op->operateur}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Proprietaire
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="proprio" id="proprio" list="sugg_prop" value="{{ old('proprio') }}"  required/>
                                            <datalist id="sugg_prop"></datalist>
                                        </div>
                                    </div>
                                 
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Année mise en service
                                        </label>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" name="annee" value="{{ old('annee') }}" />

                                        </div>
                                    </div>




                                </div>
                                <div class="tab-pane" id="tab2">
                                    <h3 class="block">Parametre du site</h3>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Commune
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="commune"  id="commune" list="suggestions" value="{{ old('commune') }}" required/>
                                            <datalist id="suggestions"></datalist>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Code commune
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="code_c" id="code_commune" value="{{old('code_c')}}" disabled/>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Longitude
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="longitude" value="{{old('longitude')}}" required/>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Latitude
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="latitude" value="{{old('latitude')}}" required/>

                                        </div>
                                    </div>
                                  
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Type de site</label>
                                        <div class="col-md-4">
                                            <select name="type" id="country_list" class="form-control" value="{{old('type')}}">
                                                    <option value="1">Non defini</option>
                                                @foreach($listetype as $type)
                                                        @if($type->type!='Non defini')
                                                        <option value="{{$type->id}}" {{ old('type') == $type->id ? 'selected' : '' }}>{{$type->type}}</option>
                                                        @endif
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Mutualise
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <div class="radio-list">
                                                <label>
                                                    <input type="radio" name="mutualise" value="0" {{ old('mutualise') == '0' ? 'checked' : '' }} checked /> Non </label>
                                                <label>
                                                    <input type="radio" name="mutualise" value="1" {{ old('mutualise') == '1' ? 'checked' : '' }} /> Oui </label>
                                            </div>
                                            <div id="form_gender_error"> </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Operateur en colocation</label>
                                        <div class="col-md-4">
                                        <input class="form-control" name="coloc" value="{{old('coloc')}}" >

                                        </div>
                                    </div>





                                </div>
                                <div class="tab-pane" id="tab3">
                                    <h3 class="block">Information technique</h3>
                                    <div class="form-group form-md-line-input">
                                        <label class="control-label col-md-3">Technologies
                                            <span class="required"> * </span>
                                        </label>
                                        <div class="col-md-4">
                                            <select multiple name="tech[]"  class="form-control"   required>
                                                @foreach($listetech as $tech)
                                                    <option value="{{$tech->id}}" {{ old('tech') == $tech->id ? 'selected' : '' }}>{{$tech->generation}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Information sur les technologies
                                            <span class="required">  </span>
                                        </label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" name="info" rows="3" value="{{old('info')}}"></textarea>
                                            <span class="help-block"> </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Source d'énergie
                                            <span class="required">  </span>
                                        </label>
                                        <div class="col-md-4">
                                        <input type="text" name="source" class="form-control" value="{{old('source')}}" placeholder="source1-source2"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Hauteur de l'antenne
                                            <span class="required">  </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" name="hauteur" maxlength="4" class="form-control" value="{{old('hauteur')}}"/>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Largeur des canaux
                                            <span class="required"> </span>
                                        </label>
                                        <div class="col-md-4">
                                            <input type="text" name="largeur" maxlength="4" class="form-control" value="{{old('largeur')}}"/>

                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="tab-pane" id="tab4">
                                    <h3 class="block">Ajouter infrastructure</h3>
                                    <h4 class="form-section">Information génerale</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Code site:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="code_site"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Nom site:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="nom_site"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Operateur:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="operateur"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Proprietaire:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="proprio"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Année mise en service:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="annee"> </p>
                                        </div>
                                    </div>
                                    <h4 class="form-section">Paramètre du site</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Longitude:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="long"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Latitude:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="lat"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Commune:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="commune"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Type de site:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="type"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Mutualise:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="mutualise"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Operateur en colocation:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="coloc"> </p>
                                        </div>
                                    </div>
                                    <h4 class="form-section">Information technique</h4>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Les technologies</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="techno"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Information sur les technologies:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="info"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Source d'énergie:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="source"> </p>
                                        </div>
                                    </div>
                                
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Hauteur de l'antenne:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="hauteur"> </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Largeur des canaux:</label>
                                        <div class="col-md-4">
                                            <p class="form-control-static" data-display="largeur"> </p>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <a href="#"  class="btn default button-previous">
                                        <i class="fa fa-angle-left"></i> Back </a>
                                    <a href="#" class="btn btn-outline green button-next"> Continue
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                    <a href="#" class="btn green button-submit" disabled> Submit
                                        <i class="fa fa-check"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if($errors->any())
                    @if(!$errors->has('erreur'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>  
                @endif
                @endif
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
        </div>
    </div>
</div>

@endsection