@extends('layouts.app2')

@section('title', 'Bon de caisse')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Bon de caisse</h4>
            </div>

        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">

            <div class="card-body">

                <form method="POST" action={{ route('cashbox.ticket.store') }} autocomplete="off">

                    @csrf
                    <div class="row d-flex align-items-end">
                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Type de caisse<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" data-toggle="select2" id="" name="cashbox_id"
                                    required>
                                    <option value="">Sélectionne une caisse</option>
                                    <option value="1">Caisse de vente</option>
                                    <option value="2">Caisse de dépense</option>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Fournisseur<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" data-toggle="select2" required name="supplier_id"
                                    required>
                                    <option value="">Sélectionner le fournisseur</option>
                                    @forelse ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @empty
                                        Ajouter un fournisseur
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Description<span
                                        style="color:red;">*</span></label>
                                        <input type="text" class="form-control" name="description">
                                {{-- <textarea class="form-control" name="description" id="" cols="30" rows="5"></textarea> --}}
                            </div>
                        </div>

                        <div class="col-md-2 col-12">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" id="add_detail">Ajouter</button>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des bon de caisse</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>#</th>
                                {{-- <th>Patient</th> --}}
                                <th>Détails</th>
                                <th>Montant</th>
                                <th>Fournisseur</th>
                                <th>Description</th>
                                <th>Status</th>
                                @foreach (getRolesByUser(Auth::user()->id) as $role)
                                    {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}

                                    @if ($role->name == "rootuser")
                                        <th>Traité</th>
                                        @break
                                    @endif
                                @endforeach
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($tickets as $key=>$ticket)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>

                                        @if ($ticket->details)
                                            <span style="display: none;">{{ $i = 0 }}</span>
                                            @foreach ($ticket->details as $detail)
                                                <div class="col-lg-12 mb-2">
                                                    <span style="display: none;">{{ $i++ }}</span>
                                                    <span>
                                                        @if ($ticket->status =="en attente")
                                                                <button type="button"
                                                                    onclick="deleteTicketDetail({{ $detail->id }})"
                                                                    title="Supprimer" style="border:none;"
                                                                    class="btn-danger rounded-circle"><i
                                                                        class="mdi mdi-minus"></i> </button>
                                                        @endif
                                                        <span class="m-2">{{ $detail->item_name }}</span>
                                                    </span>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if ($ticket->status =="en attente")
                                               <div class="text-end">
                                                <button type="button" class="btn btn-primary rounded-circle" style="font-size: 10px;" data-bs-toggle="modal" data-bs-target="#standard-modal-{{$ticket->id}}"  style="border:none;"
                                                    title="Prestation"><i class="mdi mdi-plus" style="font-size: 10px;"></i></button>
                                                </div>
                                                    @include('cashbox.ticket.edit',[
                                                        'ticket' => $ticket,
                                                        ])
                                        @endif
                                    </td>
                                    <td>
                                        {{ $ticket->amount? $ticket->amount : 0 }}
                                    </td>
                                    <td>
                                        {{ $ticket->supplier != null ? $ticket->supplier->name : 'Aucun' }}
                                    </td>
                                    <td>
                                        {{ $ticket->description ? $ticket->description : '' }}
                                    </td>
                                    <td>
                                        @if ($ticket->status=="en attente")
                                        <span class="badge bg-warning">En attente</span>
                                        @elseif ($ticket->status=="approuve")
                                        <span class="badge bg-success">Acceptée</span>
                                        @else
                                        <span class="badge bg-danger">Refusée</span>
                                        @endif
                                    </td>
                                    {{-- <td>
                                        {{ $ticket->status }}
                                    </td> --}}

                                    @foreach (getRolesByUser(Auth::user()->id) as $role)
                                        @if ($role->name == "rootuser")
                                            <td >
                                                <select class="form-select " id="example-select" onchange="updateStatus({{$ticket->id}})">
                                                    <option {{ $ticket->status == "en attente" ? 'selected':'' }} value="en attente">En attente</option>
                                                    <option {{ $ticket->status == "approuve" ? 'selected':'' }}  value="approuve">Acceptée</option>
                                                    <option {{ $ticket->status == "rejete" ? 'selected':'' }} value="rejete">Refusée</option>
                                                </select>

                                            </td>
                                            @break
                                        @endif
                                    @endforeach

                                    <td>
                                        @if ($ticket->status == "en attente")
                                            <button type="submit" onclick="deleteTicket({{ $ticket->id }})"
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
        var baseUrl = "{{url('/')}}"
        var ROUTEUPDATESTATUSTICKET = "{{ route('cashbox.ticket.updateStatus') }}"
        var TOKENUPDATESTATUSTICKET = "{{ csrf_token() }}"
    </script>

    <script src="{{asset('viewjs/bank/ticket.js')}}"></script>

@endpush

{{-- @push('extra-js')
    <script type="text/javascript">
        var baseUrl = "/";

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
                    window.location.href = "{{ url('detail-prestations/delete') }}" + "/" + id;
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
                    [7, "desc"]
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


        $('#addDetailForm').on('submit', function(e) {
            e.preventDefault();

            let prestation_order_id = $('#prestation_order_id').val();
            let patient_id = $('#patient_id').val();
            let prestation_id = $('#prestation_id').val();
            let price = $('#price').val();
            let remise = $('#remise').val();
            let mont = $('#mont').val();
            console.log(prestation_id);

            $.ajax({
                url: "{{ route('details_prestation_order.store') }}",
                // url: baseUrl+"/prestation_order/details",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    prestation_order_id: prestation_order_id,
                    patient_id: patient_id,
                    prestation_id: prestation_id,
                    price: price,
                    remise: remise,
                    total: mont,
                },
                success: function(response) {
                    $('#addDetailForm').trigger("reset")
                    if (response) {
                        console.log(response);
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                        location.reload();
                    };
                },
                // error: function(response) {
                //     console.log(response)
                // },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    console.log(status);
                    console.log(error);
                }
            });
        });

        //EDITION
        // function edit(id) {
        //     var e_id = id;
        //     // var baseUrl = "{{ url('/') }}";
        //     // Populate Data in Edit Modal Form
        //     $.ajax({
        //         type: "GET",
        //         // url: "{{ url('prestations_order/edit') }}" + '/' + e_id,
        //         url: baseUrl+"prestations_order/edit/"+e_id,
        //         success: function(data) {
        //             var code = document.getElementById('code');
        //             code.innerHTML = data.data.code;
        //             $('#prestation_order_id').val(data.data.id);
        //             var patient = data.patient.firstname + ' ' + data.patient.lastname;
        //             $('#patient_id').val(patient);
        //             var contrat = data.contrat.name;
        //             $('#contrat').val(contrat);
        //             console.log(data.data.contrat_id);
        //             $('#contrat_id').val(data.data.contrat_id);
        //             $('#prestation_id').val(data.prestation_id);
        //             console.log(data);
        //             $('#editModal').modal('show');
        //         },
        //         error: function(data) {
        //             console.log('Error:', data);
        //         }
        //     });
        // }

        //EDITION PRESTATION
        function editprestation(prestation_order_id, prestation_id, row) {
            var e_id = prestation_id;
            var order_id = prestation_order_id;
            var rowID = row;
            // var baseUrl = "{{ url('/') }}";
            $.ajax({
                type: "GET",
                // url: "{{ url('prestations/edit') }}" + '/' + e_id,
                url: baseUrl+"prestations_order/edit/"+e_id,
                success: function(data) {

                    $('#prestation_id2').val(data.id);
                    $('#total2').val(data.price);
                    $('#rowID').val(rowID);
                    $('#prestation_order_id2').val(order_id);
                    $('#editModal2').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        // SUPPRESSION
        function deleteprestation(prestation_order_id, prestation_id, row) {
            var prestation_id = prestation_id;
            var order_id = prestation_order_id;
            var rowID = row;

            $.ajax({
                url: "{{ route('prestations_order.destroyDetail') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    prestation_order_id: order_id,
                    prestation_id: prestation_id,
                    rowID: rowID,

                },
                success: function(response) {

                    if (response) {
                        console.log(response);
                        toastr.success("Supprimé avec succès", 'Suppression réussi');
                        location.reload();
                    }
                    //$('#datatable1').DataTable().ajax.reload();
                    // $('#addDetailForm').trigger("reset")
                    // updateSubTotal();
                },
                error: function(response) {
                    console.log(response)
                },
            });

        }

        function getTest() {
            var prestation_id = $('#prestation_id').val();


            $.ajax({
                type: "POST",
                url: "{{ route('prestations_order.getPrestationOrder') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    prestationId: prestation_id,
                },
                success: function(data) {
                    console.log(data.total);
                    $('#total').val(data.total);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });

        }
    </script>
    <script src="{{asset('viewjs/prestation/prestationorder.js')}}"></script>
@endpush --}}
