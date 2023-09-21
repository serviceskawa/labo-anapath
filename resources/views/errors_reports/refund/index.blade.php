@extends('layouts.app2')

@section('title', 'Remboursements')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Ajouter une nouvelle demande</button> --}}
                    <a href="{{ route('refund.request.create') }}" type="button" class="btn btn-primary">Ajouter une nouvelle
                        demande</a>
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

        <div class="col-12">
            <div class="card mb-md-0 mb-3">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                            aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                        <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h5 class="card-title mb-0">Liste des demandes de remboursements</h5>

                    <div id="cardCollpase1" class="collapse pt-3 show">

                        <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <th>Code examen</th> --}}
                                    <th>Montant</th>
                                    <th>Raison</th>
                                    <th>Status</th>
                                    <th>Facture</th>
                                    <th>Dernière actualisation</th>
                                    <th>PDF</th>
                                    @foreach (getRolesByUser(Auth::user()->id) as $role)
                                        {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}
                                        @if ($role->name == 'rootuser')
                                            <th>Traité</th>
                                            @break
                                        @endif
                                    @endforeach
                                    <th>Action</th>

                                </tr>
                            </thead>


                            <tbody>

                                @foreach ($refundRequests as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        {{-- <td>{{ $item->order->code }}</td> --}}
                                        <td>{{ $item->montant }}</td>
                                        <td>{{ tronquerChaine($item->reason->description) }}</td>
                                        <td>
                                            @if ($item->status == 'En attente')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif ($item->status == 'Aprouvé')
                                                <span class="badge bg-success">Acceptée</span>
                                            @elseif($item->status == 'Rejeté')
                                                <span class="badge bg-danger">Refusée</span>
                                            @else
                                                <span class="badge bg-secondary">Clôturée</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->invoice ? $item->invoice->code : '' }}</td>
                                        <td>
                                            {{ date_format($item->updated_at, 'd/m/y h:m:s') }}
                                        </td>
                                        <td>
                                            @if ($item->attachment)
                                                <a href="{{ asset('storage/' . $item->attachment) }}" download>
                                                    <u style="font-size: 15px;">Voir</u>
                                                </a>
                                            @endif
                                        </td>
                                        @foreach (getRolesByUser(Auth::user()->id) as $role)
                                            {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}

                                            @if ($role->name == 'rootuser')
                                                <td>

                                                   @if ($item->status  == 'En attente' || $item->status =='Aprouvé' || $item->status == 'Rejeté')
                                                    <select class="form-select " id="refund_status"
                                                            onchange="updateStatusRefund({{ $item->id }})">
                                                            <option {{ $item->status == 'En attente' ? 'selected' : '' }}
                                                                value="En attente">En
                                                                attente</option>
                                                            <option {{ $item->status == 'Approuvé' ? 'selected' : '' }}
                                                                value="Aprouvé">Acceptée
                                                            </option>
                                                            <option {{ $item->status == 'Rejeté' ? 'selected' : '' }}
                                                                value="Rejeté">Refusée
                                                            </option>

                                                        </select>
                                                   @endif

                                                </td>
                                                @break
                                            @endif
                                        @endforeach
                                        <td>
                                            <a class="btn btn-primary" href="#" data-bs-toggle="modal"
                                                data-bs-target="#bs-example-show-{{ $item->id }}"><i
                                                    class="mdi mdi-eye"></i>
                                            </a>
                                            @include('errors_reports.refund.create_modal', ['refund' => $item])

                                            @if ($item->status == 'En attente' || $item->status == 'Aprouvé')
                                                <a type="button" onclick="edit({{ $item->id }})"
                                                    class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </a>
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3">
            <div class="card mb-md-0 mt-5">
                <div class="card-body">
                    <div class="card-widgets">
                        <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                        <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                            aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                        <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                    </div>
                    <h5 class="card-title mb-0">Historique des demandes de remboursements</h5>
                    <div id="cardCollpase1" class="collapse pt-3 show">


                        <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <th>Code examen</th> --}}
                                    <th>Demande de rembousement</th>
                                    <th>Utilisateur</th>
                                    <th>Operation</th>
                                    <th>Dernière mis à jour</th>

                                </tr>
                            </thead>


                            <tbody>

                                @foreach ($logs as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->refund->code }}</td>
                                        <td>{{ $item->user->firstname }} {{ $item->user->lastname }}</td>
                                        <td>{{ $item->operation }}</td>
                                        <td>
                                            {{ date_format($item->updated_at, 'd/m/y h:m:s') }}
                                        </td>
                                    </tr>
                                @endforeach




                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection


@push('extra-js')
    <script>
        //UPDATE STATUS
        function updateStatusRefund(id) {
            var status = $('#refund_status').val();
            var e_id = id;
            console.log(e_id, status);

            $.ajax({
                url: "{{ route('refund.request.updateStatus') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: e_id,
                    status: status,
                },
                success: function(data) {
                    console.log(data);
                    if (data.status == 500) {
                        toastr.error(data.data, 'Erreur');
                        location.reload();
                    } else {

                        toastr.success("Mis à jour avec succès", 'Ajout réussi');
                        if (data.data == 1) {
                            window.location.href = "/invoices/show/" + data.invoice;
                        } else {
                            location.reload();
                        }
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            })
        }

        $('#invoice_id2').on('change', function() {
            var note = "Une demande de remboursement pour la facture "
            $.ajax({
                type: "GET",
                url: "/invoices/getInvoice/" + this.value,

                success: function(data) {
                    console.log(data);
                    if ($('#montant2').val()) {
                        $('#montant2').val(data.total)
                        $('#description2').val(note+data.code)
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        })

        $('#montant2').on('input', function() {
            var id = $('#invoice_id2').val()
            var notePlus = "pour un motant de "
            var descript = $('#description2').val()
            if (id !="Sélectionner une facture") {
                $.ajax({
                    type: "GET",
                    url: "/invoices/getInvoice/" + id,

                    success: function(data) {
                        if ($('#montant2').val() > data.total) {
                            toastr.error("Le montant saisi est supérieur total de la facture", 'Montant saisi');
                        }else{
                            // $('#description2').val(descript+' '+$('#montant2').val())
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }else{
                toastr.error("Veillez sellectionné une facture", 'Facture');
            }
        })

        //EDITION
        function edit(id) {
            var e_id = id;
            console.log(e_id);
            var id = document.getElementById('id2');
            var montant = document.getElementById('montant2');


            // Populate Data in Edit Modal Form
            $.ajax({
                type: "GET",
                url: "/getrefund-request/" + e_id,

                success: function(data) {
                    console.log(data);
                    $('#id2').val(data.data.id);
                    $('#invoice_id2').val(data.data.invoice_id);
                    $('#refund_reason_id2').val(data.data.refund_reason_id).change();
                    $('#montant2').val(data.data.montant);
                    $('#description2').val(data.data.note);
                    $('#attachment').val(data.data.attachment);
                    console.log(data.data.status);
                    if (data.data.status == 'Aprouvé') {
                        console.log(id,montant);
                        id.setAttribute("readonly", "readonly");
                        document.getElementById('invoice_id2').setAttribute("disabled", "disabled");
                        document.getElementById('refund_reason_id2').setAttribute("disabled", "disabled");
                        montant.setAttribute("readonly", "readonly");
                        document.getElementById('description2').setAttribute("readonly", "readonly");
                    }
                    $('#editModal').modal('show');
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }


    </script>
    <script src="{{ asset('viewjs/invoice/refund.js') }}"></script>
@endpush
