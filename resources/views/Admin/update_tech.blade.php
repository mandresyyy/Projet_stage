@extends("Admin.Layouts.master")
@section('contenu')
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption">
            <!-- <i class="icon-equalizer font-red-sunglo"></i> -->
            <span class="caption-subject font-red-sunglo bold uppercase">Modification technologie</span>
            <!-- <span class="caption-helper">form actions on top...</span> -->
        </div>

    </div>
    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <form action="{{route('techno.update')}}" method="POST" class="form-horizontal">
            @csrf
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Technologie</label>
                    <div class="col-md-4">
                        <input type="hidden" value="{{$tech->id}}" name="id">
                        <input type="text" class="form-control" name="techno" placeholder="Technologie" value="{{ $tech->generation }}" required>
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