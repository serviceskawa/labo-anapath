@extends('layouts.app2')

@section('title', 'Type consultations')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#standard-modal">Ajouter un nouveau type</button>
                </div>
                <h4 class="page-title">Type de consultation</h4>
            </div>

            <!----MODAL---->

            @include('type_consultation.create_modal')

        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Types de consultation</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Titre</th>
                                <th>Actions</th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($types as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#standard-modal{{ $item->id }}"><i
                                                class="mdi mdi-lead-pencil"></i></button>
                                        <button type="button" onclick="deleteModal({{ $item->id }})"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end card-->

    </div>
    @include('type_consultation.edit_modal', $types)
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
                    window.location.href = "{{ url('type_consultations/delete') }}" + "/" + id;
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
                "order": [
                    [0, "desc"]
                ],
                "columnDefs": [{
                    "targets": [0],
                    "searchable": false
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
            $('#standard-modalLabel').text("Mettre à jour");
            $('#btnSubmit').text("Mettre à jour");

            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "{{ url('type_consultations/show') }}" + '/' + e_id,
                success: function(data) {

                    $('#id').val(data.id);
                    $('#name').val(data.name);

                    $('#standard-modal').modal('show');
                },
                error: function(data) {
                    // console.log('Error:', data);
                }
            });
        }
    </script>
@endpush
