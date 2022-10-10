@extends('layouts.app2')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <a type="button" class="btn btn-primary" href="{{ route('template.report-create') }} ">Ajouter un
                        nouveau template</a>
                </div>
                <h4 class="page-title">Templates compte rendu</h4>
            </div>

            <!----MODAL---->

            @include('doctors.create')

            @include('doctors.edit')

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
                <h5 class="card-title mb-0">Liste des templates</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Actions</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($templates as $item)
                                <tr>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        <a type="button" href="{{ route('template.report-edit', $item->id) }}"
                                            class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </a>
                                        <a type="button" href="{{ route('template.report-delete', $item->id) }}"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end card-->


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
                    window.location.href = "{{ url('doctors/delete') }}" + "/" + id;
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
                    [0, "asc"]
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

            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "{{ url('getdoctor') }}" + '/' + e_id,
                success: function(data) {

                    $('#id2').val(data.id);
                    $('#name').val(data.name);
                    $('#telephone').val(data.telephone);
                    $('#email').val(data.email);
                    $('#role').val(data.role);
                    $('#commission').val(data.commission);



                    console.log(data);
                    $('#editModal').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>
@endpush
