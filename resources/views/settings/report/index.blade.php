@extends('layouts.app2')

@section('title', 'REPORTS SETTINGS')

@section('css')
    <link href="{{ asset('/adminassets/css/vendor/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminassets/css/vendor/quill.snow.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        .ck-editor__editable[role="textbox"] {
            /* editing area */
            min-height: 200px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right mr-3 mb-1">
                        <a href="{{ route('report.index') }}" type="button" class="btn btn-primary">Retour à la liste des comptes rendu</a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un nouveau titre</button>
                    </div>
                    <h4 class="page-title"></h4>
                    
                    @include('settings.report.create')
                    
                    @include('settings.report.edit')
                </div>


        </div>
    </div>


    <div class="">


        @include('layouts.alerts')

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Paramètres du compte rendu</h4>
                    <p class="text-muted font-14">

                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#input-types-preview" data-bs-toggle="tab" aria-expanded="false"
                                class="nav-link active">
                                Titres
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="#input-types-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Placeholder
                            </a>
                        </li> --}}
                    </ul> <!-- end nav-->
                    <div class="card-body">
                        <div class="card-widgets">
                            <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                            <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                                aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                            <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                        </div>
                        <h5 class="card-title mb-0">Liste des Titres</h5>
        
                        <div id="cardCollpase1" class="collapse pt-3 show">
        
        
                            <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                                <thead class="col-lg-12" style="text-align: center;">
                                    <tr>
                                        <th class="col-lg-2">#</th>
                                        <th class="col-lg-6">Titres</th>
                                        <th class="col-lg-4">Actions</th>
        
                                    </tr>
                                </thead>
        
        
                                <tbody>
        
                                    @foreach ($titles as $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $item->id }}</td>
                                            <td style="text-align: center;">{{ $item->title }}</td>
                                            <td style="text-align: center;">
                                                <button type="button" onclick="edit({{ $item->id }})"
                                                    class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                                <button type="button" onclick="deleteModal({{ $item->id }})"
                                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                            </td>
        
                                        </tr>
                                    @endforeach
        
                                </tbody>
                            </table>
        
                        </div>
                    </div>
                    <div class="tab-content">
                        
                    </div> <!-- end tab-content-->
                </div>

            </div> <!-- end card -->
        </div>
    </div>
@endsection


@push('extra-js')
    <script>
        // SUPPRESSION
        function deleteModal(id) {

            Swal.fire({
                title: "Voulez-vous supprimer l'élément ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui ",
                cancelButtonText: "Non !",
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ url('settings/report-delete') }}" + "/" + id;
                    Swal.fire(
                        "Suppression !",
                        "En cours de traitement ...",
                        "success"
                    )
                }
            });
        }


        /* DATATABLE */
        $(document).ready(function() {

            $('#datatable1').DataTable({
                "order": [ ],
                "columnDefs": [{
                    "targets": [0],
                    "searchable": true
                }],
                "language": {
                    "lengthMenu": "Afficher _MENU_ enregistrements par page",
                    "zeroRecords": "Aucun enregistrement disponible",
                    "info": "Afficher page _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun enregistrement disponible",
                    "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
                    "sSearch": "Rechercher:",
                    "paginate": {
                        "previous": "Précédent",
                        "next": "Suivant"
                    }
                },
            });
        });


        //EDITION
        function edit(id) {
            var e_id = id;

            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "{{ url('settings/reports-edit') }}" + '/' + e_id,
                success: function(data) {
                    $('#id2').val(data.id);
                    $('#title2').val(data.title);
                    $('#editModal').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>
@endpush
