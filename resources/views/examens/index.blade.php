@extends('layouts.app2')

@section('title', 'Examens')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                {{-- <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Nouveau</button>
            </div>
            <h4 class="page-title">Gérer les demandes d'examen</h4>
        </div>

        <!----MODAL---->

        {{-- @include('examens.create') --}}

                {{-- @include('doctors.edit') --}}

            </div>
        </div>


        <div class="">

            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <a href="{{ route('test_order.create') }}"><button type="button" class="btn btn-primary">Ajouter une
                            nouvelle demande d'examen</button></a>
                </div>
                <h4 class="page-title">Demandes d'examen</h4>
            </div>

            <!----MODAL---->
            @include('layouts.alerts')

            <div class="card mb-md-0 mb-3">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                            aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                        <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h5 class="card-title mb-0">Liste des demandes d'examen </h5>

                    <div id="cardCollpase1" class="collapse pt-3 show">


                        <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Code</th>
                                    <th>Patient</th>
                                    <th>Médecin</th>
                                    <th>Hopital</th>
                                    <th>Montant</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>


                            <tbody>

                                @foreach ($examens as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                        <td>{{ $item->code }} </td>
                                        <td>{{ $item->patient->name }}</td>
                                        <td>{{ $item->getDoctor()->name }}</td>
                                        <td>{{ $item->getHospital()->name }}</td>
                                        <td>{{ $item->total }}</td>
                                        <td>
                                            <a type="button" href="{{ route('details_test_order.index', $item->id) }}"
                                                class="btn btn-primary"><i class="mdi mdi-eye"></i> </a>
                                            @if ($item->status != 1)
                                                <button type="button" onclick="deleteModal({{ $item->id }})"
                                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i>
                                                </button>
                                            @endif

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
                    title: "La supression d'un examen entraine la suppression du Rapport. Voulez-vous continuez?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Oui ",
                    cancelButtonText: "Non !",
                }).then(function(result) {
                    if (result.value) {
                        window.location.href = "{{ url('test_order/delete') }}" + "/" + id;
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
                $.ajax({
                    type: "GET",
                    url: "{{ url('gettest') }}" + '/' + test_id,
                    success: function(data) {


                        $('#price').val(data.price);

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        </script>
    @endpush
