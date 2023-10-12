@extends('layouts.app2')

@section('title', 'Ccaisse')

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
                                <input type="text" value="Caisse de dépense" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Catégorie de dépense<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" data-toggle="select2" required id="expense_categorie_id"
                                    name="expense_categorie_id" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    @forelse ($expenses_categorie as $expense_categorie)
                                        <option value="{{ $expense_categorie->id }}">{{ $expense_categorie->name }}</option>
                                    @empty
                                        Ajouter une catégorie
                                    @endforelse
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Fournisseur<span
                                        style="color:red;">*</span></label>
                                <input type="text" id="supplier" class="form-control" name="supplier">
                                {{-- <select class="form-select select2" data-toggle="select2" required id="supplier" name="supplier_id"
                                    required>
                                    <option value="">Sélectionner le fournisseur</option>
                                    @forelse ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @empty
                                        Ajouter un fournisseur
                                    @endforelse
                                </select> --}}
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" id="add">Ajouter</button>
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
                                <th>Code</th>
                                <th>Montant</th>
                                <th>Fournisseur</th>
                                <th>Description</th>
                                <th>Status</th>
                                {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}
                                @if (getOnlineUser()->can('view-process-cashbox-tickets'))
                                    <th>Traitement</th>
                                @endif

                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($tickets as $key => $ticket)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $ticket->code ? $ticket->code : '' }}</td>

                                    <td>
                                        {{ $ticket->amount ? $ticket->amount : 0 }}
                                    </td>
                                    <td>
                                        {{ $ticket->supplier != null ? $ticket->supplier->name : 'Aucun' }}
                                    </td>
                                    <td>
                                        {{ $ticket->description ? $ticket->description : '' }}
                                    </td>
                                    <td>
                                        @if ($ticket->status == 'en attente')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif ($ticket->status == 'approuve')
                                            <span class="badge bg-success">Approuvé</span>
                                        @else
                                            <span class="badge bg-danger">Décliné</span>
                                        @endif
                                    </td>
                                    {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}
                                    @if (getOnlineUser()->can('view-process-cashbox-tickets'))
                                        <td>
                                            @if ($ticket->status == 'en attente')
                                                <select class="form-select " id="example-select"
                                                    onchange="updateStatusTicket({{ $ticket->id }})">
                                                    <option {{ $ticket->status == 'en attente' ? 'selected' : '' }}
                                                        value='en attente'>En attente</option>
                                                    <option {{ $ticket->status == 'approuve' ? 'selected' : '' }}
                                                        value='approuve'>Approuvée</option>
                                                    <option {{ $ticket->status == 'rejete' ? 'selected' : '' }}
                                                        value='rejete'>Décliné</option>
                                                </select>
                                            @endif



                                        </td>
                                    @endif


                                    <td>
                                        <a class="btn btn-primary" href="#" data-bs-toggle="modal"
                                            data-bs-target="#bs-example-modal-lg-edit-{{ $ticket->id }}"><i
                                                class="mdi mdi-eye"></i>
                                        </a>
                                        @include('cashbox.ticket.edit', ['item' => $ticket])
                                        @if ($ticket->status == 'en attente')
                                            <a type="button"
                                                href="{{ route('cashbox.ticket.details.index', $ticket->id) }}"
                                                class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i>
                                            </a>
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
        $(function() {
            $("#supplier").autocomplete({
                source: function(request, response) {
                    // Faites une requête Ajax pour récupérer les noms des articles depuis la base de données
                    $.ajax({
                        url: "/fournisseur/getSupplier",
                        dataType: "json",
                        data: {
                            term: request.term // Terme saisi par l'utilisateur
                        },
                        success: function(data) {
                            response(
                            data); // Affichez les suggestions d'articles à l'utilisateur
                        }
                    });
                },
                minLength: 2 // Nombre de caractères avant de déclencher l'autocomplétion
            });
        });

        function updateStatusTicket(id) {
            var status = $('#example-select').val();
            var e_id = id;
            console.log(e_id, status);

            if (status != 'en attente') {
                Swal.fire({
                    title: "Voulez-vous "+ status +" ce bon de caisse ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Oui ",
                    cancelButtonText: "Non !",
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('cashbox.ticket.status.update') }}",
                            type: "POST",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id: e_id,
                                status: status,
                            },
                            success: function(data) {
                                console.log(data);
                                toastr.success("Mis à jour du ticket", 'Ajout réussi');
                                // location.reload();
                                window.location.href = "{{ url('cashbox/tickets') }}"
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        })
                    }
                });

            }

        }
    </script>
    <!-- Inclure jQuery -->


    <script>
        var baseUrl = "{{ url('/') }}"
        var ROUTEUPDATESTATUSTICKET = "{{ route('cashbox.ticket.updateStatus') }}"
        var TOKENUPDATESTATUSTICKET = "{{ csrf_token() }}"
    </script>

    <script src="{{ asset('viewjs/bank/ticket.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Inclure jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endpush
