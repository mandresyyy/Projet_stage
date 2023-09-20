@extends("Admin.Layouts.master")
@section('contenu')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <!-- <i class="icon-equalizer font-red-sunglo"></i> -->
            <span class="caption-subject font-red-sunglo bold uppercase">Ajout opérateur</span>
            <!-- <span class="caption-helper">form actions on top...</span> -->
        </div>

    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form action="{{route('operateur.save')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Operateur</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="operateur" placeholder="operateur" value="{{ old('operateur') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Logo</label>
                    <div class="col-md-4">
                        <input type="file" class="form-control" name="photo" placeholder="logo" value="{{ old('logo') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Couleur</label>
                    <div class="col-md-4">
                        <input type="color" class="form-control" name="couleur" style="width: 50px;" value="#FF0000">
                    </div>
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