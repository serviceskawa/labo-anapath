@extends('layouts.app2')

@section('title', 'Remboursement')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#standard-modal">Ajouter une nouvelle raison</button>
                </div>
                <h4 class="page-title">Raison de remboursement</h4>
            </div>

            <!----MODAL---->

            @include('errors_reports.refund.categorie.create')

            @include('errors_reports.refund.categorie.edit')

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
                <h5 class="card-title mb-0">Liste des raisons</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Actions</th>

                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($categories as $key => $item)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>
                                        <a type="button" onclick="editRefund({{ $item->id }})"
                                            class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </a>
                                        <a type="button" onclick="deleteModalRefund({{ $item->id }})"
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
        var baseUrl = "{{ url('/') }}"
    </script>

    <script>
        function deleteModalRefund(id) {

            Swal.fire({
                title: "Voulez-vous supprimer l'élément ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui ",
                cancelButtonText: "Non !",
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "/refund-request-categorie/delete/" + id;
                    Swal.fire(
                        "Suppression !",
                        "En cours de traitement ...",
                        "success"
                    )
                }
            });
        }

        //EDITION
        function editRefund(id) {
            var e_id = id;
            console.log(e_id);


            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "/refund-request-categorie/" + e_id,

                success: function(data) {
                    console.log(data);
                    $('#id2').val(data.id);
                    $('#description').val(data.description);
                    $('#editModal').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>

@endpush
