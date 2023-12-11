@extends('layouts.app2')

@section('title', 'Dépenses')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                {{-- <div class="page-title-right mr-3">
                <a href="{{ route('all_expense.create') }}" class="btn btn-primary">Ajouter une nouvelle dépense</a>
            </div> --}}

                <h4 class="page-title">Dépenses</h4>
            </div>
            {{-- @include('expenses.create',['expenses' => $expenses,'expenses_categorie' =>
        $expenses_categorie,'cash_ticket' =>
        $cash_ticket]) --}}
        </div>
    </div>


    <div class="">


        @include('layouts.alerts')



        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <form method="POST" action={{ route('all_expense.store') }} autocomplete="off">

                    @csrf
                    <div class="row d-flex align-items-end">
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
                            </div>
                        </div>

                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Objet<span
                                        style="color:red;">*</span></label>
                                <input type="text" id="objet" class="form-control" name="description">
                            </div>
                        </div>



                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" id="add_expense">Ajouter</button>
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
                <h5 class="card-title mb-0">Dépenses</h5>

                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Fournisseur</th>
                                <th>Objet de la dépense</th>
                                <th>Montant</th>
                                <th>Piece jointe</th>

                                @if (getOnlineUser()->can('view-process-cashbox-tickets'))
                                    <th>Traitement</th>
                                @endif
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            @foreach ($expenses as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                    <td>{{ $item->supplier->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->amount }} <br>
                                        {{ $item->payment }}
                                    </td>
                                    <td>
                                        <span> {{ $item->invoice_number? 'Re: ':'' }}  {{ $item->invoice_number }}</span>
                                        @if ($item->receipt !=null)
                                            <a href="{{ asset('storage/' . $item->receipt) }}" style="font-size:25px"
                                                class="d-flex text-center" download> <i class="mdi mdi-file-outline"></i> </a>
                                        @endif
                                    </td>



                                    @if (getOnlineUser()->can('view-process-cashbox-tickets'))
                                        <td style="width: 300px">
                                            @if ($item->paid != 2)
                                                <select class="form-select" style="width: 250px" id="status_expense"
                                                    onchange="updateStatusExpense({{ $item->id }},this)">
                                                    <option {{ $item->paid == 0 ? 'selected' : '' }} value=0>Non payée
                                                    </option>
                                                    <option {{ $item->paid == 1 ? 'selected' : '' }} value=1>Payée non
                                                        livrée</option>
                                                    <option value={{ $item->paid == 2 ? 'selected' : '' }}>Payée et
                                                        livrée</option>
                                                </select>
                                            @else
                                                <span
                                                    class="badge badge-outline-{{ $item->paid == 1 ? 'warning' : 'success' }}">
                                                    {{ $item->paid == 1 ? 'Marquée comme payée non livrée' : 'Marquée comme livrée' }}</span>
                                            @endif

                                        </td>
                                    @endif

                                    <td>
                                        <a class="btn btn-primary"
                                            href="{{ route('expense.details.index', $item->id) }}"><i class="uil-eye"></i>
                                        </a>


                                        {{-- @include('expenses.show',['item' => $item]) --}}

                                        {{-- <a class="btn btn-primary" href="#" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}"><i
                                        class="mdi mdi-lead-pencil"></i>
                                </a>
                                @include('expenses.edit',['item' => $item]) --}}

                                        {{-- <button type="button" onclick="deleteModalEx({{ $item->id }})" class="btn btn-danger"><i
                                        class="mdi mdi-trash-can-outline"></i> </button> --}}
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
                                data
                            ); // Affichez les suggestions d'articles à l'utilisateur
                        }
                    });
                },
                minLength: 2 // Nombre de caractères avant de déclencher l'autocomplétion
            });
        });

        function updateStatusExpense(id, element) {
            // var status = $('#status_expense').val();
            var e_id = id;
            var title = '';
            console.log(element.value);
            if (element.value == 1) {
                title = "Voulez-vous marquer cette dépense comme payée non livrée ?"
            }else if (element.value == 2) {
                title = "Voulez-vous marquer cette dépense comme payée et livrée ?"
            }
            Swal.fire({
                title: title,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui ",
                cancelButtonText: "Non !",
            }).then(function(result) {
                if (result.value) {
                    if (element.value == 1) {
                        $.ajax({
                            url: "/expense-status/" + e_id,
                            type: "GET",
                            success: function(data) {
                                console.log(data);
                                toastr.success("Dépense marquée comme payée", 'Dépense');
                                // location.reload();
                                window.location.href = "{{ url('/expense') }}"
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        })
                    } else {

                        $.ajax({
                            url: "/expense-detail-mouv-stock/" + e_id,
                            type: "GET",
                            success: function(data) {

                                console.log(data);
                                toastr.success("Stock mis à jour", 'Stock');
                                // location.reload();
                                window.location.href = "{{ url('/expense') }}"
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        })

                    }
                }
            });

        }
    </script>
    <!-- Inclure jQuery -->


    <script>
        var baseUrl = "{{ url('/') }}"
    </script>
    {{-- <script src="{{ asset('viewjs/patient/index.js') }}"></script>

    <script src="{{ asset('viewjs/bank/ticket.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Inclure jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endpush
