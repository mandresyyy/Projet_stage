@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="{{route('utilisateur.liste')}}">Utilisateur</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Details</span>
</li>
@endsection
@section('contenu')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <!-- <i class="icon-equalizer font-red-sunglo"></i> -->
            <span class="caption-subject font-red-sunglo bold uppercase">Modifier utilisateur</span>
            <!-- <span class="caption-helper">form actions on top...</span> -->
        </div>

    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form action="{{route('utilisateur.update')}}" method="POST" class="form-horizontal">
            @csrf
            <div class="form-body">
                <div class="form-group">
                    <input type="hidden" value="{{$util->id}}" name="id">
                    <label class="col-md-3 control-label">Matricule</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="matricule" placeholder="matricule" value="{{$util->matricule}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Nom</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="nom" placeholder="nom" value="{{$util->nom}}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Prenom</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="prenom" placeholder="prenom" value="{{$util->prenom}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Email</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Adresse mail" name="mail" value="{{$util->email}}" required>
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group last">
                    <label class="col-md-3 control-label">Role</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="icheck-inline">
                                @foreach($type_user as $type)
                                <label>
                                    @if($util->id_type_util==$type->id)
                                    <input type="radio" name="type" class="icheck" value="{{$type->id}}" checked>{{$type->type_util}} </label>
                                    @else
                                    <input type="radio" name="type" class="icheck" value="{{$type->id}}">{{$type->type_util}} </label>
                                    @endif    
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group last">
                    <label class="col-md-3 control-label">Etat</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="icheck-inline">
                                @foreach($etat as $type)
                                <label>
                                    @if($util->id_etat_compte==$type->id)
                                    <input type="radio" name="etat" class="icheck" value="{{$type->id}}" checked>{{$type->etat}} </label>
                                    @else
                                    <input type="radio" name="etat" class="icheck" value="{{$type->id}}">{{$type->etat}} </label>
                                    @endif    
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @if($errors->has('erreur_modif'))
                <div class="alert alert-danger" style="color: red;">
                    {{ $errors->first('erreur_modif') }}
                </div>
                @endif
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            </div>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-offset-3 col-md-4">
                        <a href="{{route('utilisateur.liste')}}"><button type="button" class="btn default">Annuler</button></a>
                    
                        <button type="submit" class="btn green">Valider</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END FORM-->
    </div>
</div>

@endsection