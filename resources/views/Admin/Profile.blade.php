@extends("Admin.Layouts.master")
@section('contenu')




<div class="profile-content">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light ">
                <div class="portlet-title tabbable-line">
                    <div class="caption caption-md">
                        <i class="icon-globe theme-font hide"></i>
                        <span class="caption-subject font-blue-madison bold uppercase">Mon compte</span>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_1_1" data-toggle="tab">Info personnel</a>
                        </li>
                        <li >
                            <a href="#tab_1_3" data-toggle="tab">Changer mot de passe</a>
                        </li>
                    </ul>
                </div>
                <div class="portlet-body">
                    <div class="tab-content">
                        <!-- PERSONAL INFO TAB -->
                        <div class="tab-pane active" id="tab_1_1">
                            <form role="form" action="{{route('user.profil.update')}}" method="POST">
                                @csrf
                            <div class="form-group">
                                    <label class="control-label">Matricule</label>
                                    <input type="text" placeholder="" value="{{$utilisateur->matricule}}" name="" class="form-control" disabled/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nom</label>
                                    <input type="text" placeholder="" value="{{$utilisateur->nom}}" name="nom" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Prenom</label>
                                    <input type="text" placeholder="" value="{{$utilisateur->prenom}}" name="prenom" class="form-control" />
                                </div>

                                <div class="form-group">

                                    <label class=" control-label">Email</label>

                                    <div class="input-group">
                                        <input type="email" class="form-control" placeholder="" value="{{$utilisateur->email}}" name="mail" value="" required>
                                        <span class="input-group-addon">
                                            <i class="fa fa-envelope"></i>
                                        </span>

                                    </div>
                                </div>
                                <div class="margiv-top-10">
                                    <button class="btn green"> Mettre à jour </button>
                                    <!-- <a href="javascript:;" class="btn default"> Cancel </a> -->
                                </div>
                            </form>
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
                        <!-- END PERSONAL INFO TAB -->


                        <!-- CHANGE PASSWORD TAB -->
                        <div class="tab-pane" id="tab_1_3">
                            <form action="{{route('user.mdp.update')}}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="control-label">Ancien mot de passe</label>
                                    <input type="password" class="form-control" name="current_mdp" require/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" name="motdepasse" require/>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Retapez le nouveau mot de passe</label>
                                    <input type="password" class="form-control" name="confirmation" require/>
                                </div>
                                <div class="margiv-top-10">
                                    <button class="btn green"> Mettre à jour </button>
                                    <!-- <a href="javascript:;" class="btn default"> Cancel </a> -->
                                </div>
                            </form>
                            @if($errors->any())
                            @if(!$errors->has('erreur'))
                            
                            <script>
                                
                            </script>
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
                        <!-- END CHANGE PASSWORD TAB -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection