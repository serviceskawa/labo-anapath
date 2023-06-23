@extends('layouts.app2')

@section('title', 'Remboursement')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Ajouter une nouvelle demande</button> --}}
                <a href="{{route('refund.request.create')}}" type="button" class="btn btn-primary" >Ajouter une nouvelle demande</a>
            </div>
            <h4 class="page-title">Demandes de remboursements</h4>
        </div>

        <!----MODAL---->

        {{-- @include('errors_reports.refund.create_modal') --}}

        @include('errors_reports.refund.edit')

    </div>
</div>


<div class="">


    @include('layouts.alerts')


    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des demandes de remboursements</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code examen</th>
                            <th>Description</th>
                            <th>Montant</th>
                            <th>Status</th>
                            @foreach (getRolesByUser(Auth::user()->id) as $role)
                                {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}

                                @if ($role->name == "rootuser")
                                    <th>Traité</th>
                                    @break
                                @endif
                            @endforeach
                            <th>Action</th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($refundRequests as $key=>$item)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->order->code }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->montant }}</td>
                            <td>
                                @if ($item->status==0)
                                <span class="badge bg-warning">En attente</span>
                                @elseif ($item->status==1)
                                <span class="badge bg-success">Acceptée</span>
                                @else
                                <span class="badge bg-danger">Refusée</span>
                                @endif
                            </td>
                            @foreach (getRolesByUser(Auth::user()->id) as $role)
                                {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}

                                @if ($role->name == "rootuser")
                                    <td >

                                        <select class="form-select " id="example-select" onchange="updateStatus({{$item->id}})">
                                            <option {{ $item->status == 0 ? 'selected':'' }} value="0">En attente</option>
                                            <option {{ $item->status == 1 ? 'selected':'' }}  value="1">Acceptée</option>
                                            <option {{ $item->status == 2 ? 'selected':'' }} value="2">Refusée</option>
                                        </select>

                                    </td>
                                    @break
                                @endif
                            @endforeach
                            <td>
                                <a type="button" onclick="edit({{$item->id}})" class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </a>
                                        <a type="button" onclick="deleteModal({{$item->id}})"  class="btn btn-danger "><i class="mdi mdi-trash-can-outline"></i> </a>
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
            window.location.href="refund-request/delete/"+id;
            Swal.fire(
                "Suppression !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}

//EDITION
function edit(id){
    var e_id = id;
    console.log(e_id);


    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: "/getrefund-request/" + e_id,

        success: function (data) {
            console.log(data);
            $('#id2').val(data.data.id);
            $('#test_order_code2').val(data.code);
            $('#montant2').val(data.data.montant);
            $('#description2').val(data.data.description);
            $('#editModal').modal('show');
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

        function updateStatus(id)
        {
            var status = $('#example-select').val();
            var e_id = id;
            console.log(e_id,status);

            $.ajax({
            url: "{{ route('refund.request.updateStatus') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: e_id,
                status:status,
            },
            success: function (data) {
                toastr.success("Mis à jour avec succès", 'Ajout réussi');
                location.reload();

            },
            error: function (error) {
                console.log(error);
            }
        })

        }
    </script>


@endpush
