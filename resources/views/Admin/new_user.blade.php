@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="{{route('utilisateur.liste')}}">Utilisateur</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Ajout</span>
</li>
@endsection
@section('contenu')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <!-- <i class="icon-equalizer font-red-sunglo"></i> -->
            <span class="caption-subject font-red-sunglo bold uppercase">Nouveau utilisateur</span>
            <!-- <span class="caption-helper">form actions on top...</span> -->
        </div>

    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form action="{{route('utilisateur.save')}}" method="POST" class="form-horizontal">
            @csrf
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Matricule</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="matricule" placeholder="matricule" value="{{ old('matricule') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Nom</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="nom" placeholder="nom" value="{{ old('nom') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Prenom</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="prenom" placeholder="prenom" value="{{ old('prenom') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Email</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Adresse mail" name="mail" value="{{ old('mail') }}" required>
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Mot de passe</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="password" class="form-control" placeholder="Mot de passe" name="mdp" required>
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Confirmation</label>
                    <div class="col-md-4">
                        <input type="password" class="form-control spinner" placeholder="Confirmation de mot de passe" name="mdpc" required>
                    </div>
                </div>
                <div class="form-group last">
                    <label class="col-md-3 control-label">Role</label>
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="icheck-inline">
                                @foreach($type_user as $type)
                                <label>
                                    <input type="radio" name="type" class="icheck" value="{{$type->id}}" checked>{{$type->type_util}} </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @if($errors->has('erreur_inscri'))
                <div class="alert alert-danger" style="color: red;">
                    {{ $errors->first('erreur_inscri') }}
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
                        <button type="submit" class="btn green">valider</button>
                    </div>
                </div>
            </div>
        </form>
        <!-- END FORM-->
    </div>
</div>

@endsection