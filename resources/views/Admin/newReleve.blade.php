@extends("Admin.Layouts.master")
@section('contenu')
<link href="{{asset('login/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet light form-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                <i class="fa fa-upload font-dark"></i>
                    <span class="caption-subject font-green sbold uppercase">Import fichier de rélevé</span>
                </div>

            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form action=" {{route('releve_signal.save')}}" class="form-horizontal form-bordered" id="form" method="POST" enctype="multipart/form-data">
                    @csrf                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3"></label>
                            <div class="col-md-3">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="input-group input-large">
                                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                            <span class="fileinput-filename"> </span>
                                        </div>
                                        <span class="input-group-addon btn default btn-file">
                                            <span class="fileinput-new"> Selectionner un fichier </span>
                                            <input type="file" name="fichier" id="file" require> </span>
                                    </div>
                                    </div>
                                    <select name="operateur" class="form-control uneditable-input input-fixed input-medium">
                                        @foreach($operateur as $op)
                                        <option value="{{$op->operateur}}">{{$op->operateur}}</option>
                                        @endforeach
                                    </select>
                                    

                                
                            </div>
                        </div>



                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn green" type="submit">
                                    <i class="fa fa-upload"></i> Importer fichier</button>
                                
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            @if($errors->any())
       
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        @endif
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        </div>
        <!-- END PORTLET-->
    </div>
</div>
</div>

<script>
    var input = document.getElementById('file');
    var filenameSpan = document.querySelector('.fileinput-filename');
    input.addEventListener('change', function() {
        if (this.files.length > 0) {
            filenameSpan.textContent = this.files[0].name;
        } else {
            filenameSpan.textContent = '';
        }
    });
    // var btn_import=document.getElementById('import');
    // var form=document.getElementById('form');

    // btn_import.addEventListener('click', function(){
    //     form.submit();
    // });
</script>

@endsection