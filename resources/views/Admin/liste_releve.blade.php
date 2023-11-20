@extends("Admin.Layouts.master")
@section('nav')
<li>
    <a href="#">Releve</a>
    <i class="fa fa-angle-right"></i>
</li>
<li>
    <span>Liste</span>
</li>
@endsection
@section('contenu')
<!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->
<script src="{{asset('Utilitaire/jQuery.js')}}"></script>
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->
<script src="{{asset('Utilitaire/Data_table.min.js')}}"></script>
<link href="{{asset('Utilitaire/datatable.min.css')}}" rel="stylesheet" type="text/css" />

<!-- <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" /> -->
<link href="{{asset('login/assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('login/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('Utilitaire/sweetalert.min.js')}}"></script>
<div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption font-dark">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject bold uppercase"> Releve</span>
            </div>
            <!-- <div class="actions">
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <label class="btn btn-transparent dark btn-outline btn-circle btn-sm active">
                        <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                    <label class="btn btn-transparent dark btn-outline btn-circle btn-sm">
                        <input type="radio" name="options" class="toggle" id="option2">Settings</label>
                </div>
            </div> -->
        </div>
        @if(session('success'))
            <script>
                Swal.fire({
                    title: 'Succès!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>
            @endif
        <div class="portlet-body">
            <div class="table-toolbar">
                <div class="row">
                    <div class="col-md-6">
                        <div class="btn-group">
                            <a href="{{route('new.releve_signal')}}">
                                <button id="sample_editable_1_new" class="btn sbold green"> Importer relevé
                                    <i class="fa fa-plus"></i>
                                </button></a>
                        </div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1">
                <thead>
                    <tr>
                        <th></th>
                        <th> Date importation </th>
                        <th> Operateur </th>
                        <th> Description </th>
                        <th>Debut du relevé</th>
                        <th></th>
                    </tr>
                </thead>
                <script>
                    function delet(id) {

                        var url = "{{ route('admin.releve.delete', ['id_rel' => 'blanc']) }}";
                        url = url.replace('blanc', id);
                        window.location.href = url;
                    }
                </script>
                <tbody>
                    @foreach($liste as $releve)
                    <tr class="odd gradeX">
                        <td scope="row"> {{$loop->index+1}} </td>
                        <td> {{$releve->date_upload}} </td>
                        <td> {{$releve->operateur->operateur}} </td>
                        <td>{{$releve->description}}</td>
                        <td>{{$releve->get_date_releve()}}</td>
                        <td><a class="btn btn-icon-only " onclick="confirmDelete('{{$releve->id_upload}}')"><i class="icon-trash"></i></a></td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
<script>
    function confirmDelete(id) {
        // console.log('ato');
        Swal.fire({
            title: 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                delet(id);
                return true;
            } else {
                return false;
            }
        });
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new DataTable('#sample_1', {
            language: {
                url: '../../pivottable/fr-FR.json',
            },
        });


    });
</script>