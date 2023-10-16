@extends('layouts.app2')

@section('title', 'Dépenses')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css"
        integrity="sha512-In/+MILhf6UMDJU4ZhDL0R0fEpsp4D3Le23m6+ujDWXwl3whwpucJG1PEmI3B07nyJx+875ccs+yX2CqQJUxUw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Inclure les fichiers CSS de Dropzone.js via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/dropzone.min.css" />
    <style>
        .simple-button {
            background-color: transparent;
            border: none;
            color: #dc3545; /* Couleur du texte pour les boutons de suppression */
            cursor: pointer;
        }
        .simple-link-button {
        background-color: transparent;
        border: none;
        color: #0d6efd; /* Couleur du texte pour les boutons de lien */
        cursor: pointer;
    }
    </style>
@endsection

@section('content')
    <div class="">

        @include('layouts.alerts')


        {{-- @include('examens.details.create') --}}
        <div class="page-title-right">
            <h4 class="mt-3">Détails de la dépense</h4>
        </div>

        {{-- Bloc pour modifier les demandes d'examan --}}
        <form action="{{route('all_expense.update')}}" method="post" autocomplete="off"
            enctype="multipart/form-data">
            <div class="card my-3">


            {{-- Fusion de read et updaye --}}
                <div class="card-body">

                    @csrf
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <input type="hidden" class="form-control" readonly name="expense_id" value="{{$expense->id}}">
                    <div class="row d-flex align-items-end">
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Catégorie de dépense<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" data-toggle="select2" required id="expense_categorie_id" name="expense_categorie_id"
                                        required>
                                        <option value="">Sélectionner une catégorie</option>
                                        @forelse ($expenses_categorie as $expense_categorie)
                                            <option value="{{ $expense_categorie->id }}" {{$expense->expense_categorie_id == $expense_categorie->id ? 'selected' :''}} >{{ $expense_categorie->name }}</option>
                                        @empty
                                            Ajouter une catégorie
                                        @endforelse
                                    </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Fournisseur<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" data-toggle="select2" required name="supplier_id"
                                    required>
                                    <option value="">Sélectionner le fournisseur</option>
                                    @forelse ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{$expense->supplier_id == $supplier->id ? 'selected':''}}>{{ $supplier->name }}</option>
                                    @empty
                                        Ajouter un fournisseur
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <label for="example-select" class="form-label">Description de la dépense</label>
                            <input name="description" type="text" class="form-control mb-3" id=""  {{$expense->paid != 0 ? 'readonly':''}} value="{{$expense->description}}" >
                        </div>
                        <div class="col-md-6 col-12">
                            <label for="example-select" class="form-label">Date de la dépense</label>
                            <input class="form-control" {{$expense->paid != 0 ? 'readonly':''}} id="example-date" type="date"
                                    value="{{  old('date') ? old('date') : $expense->date}}"
                                    name="date">
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Montant<span
                                        style="color:red;">*</span></label>
                                <input type="number" {{$expense->paid != 0 ? 'readonly':''}} name="amount" id="amount" value="{{  old('amount') ? old('amount') : $expense->amount}}" {{$expense->paid != 0 ? 'readonly':''}} class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-12 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Type de
                                paiement</label>
                            <select class="form-select select2" data-toggle="select2"
                                name="payment" {{$expense->paid != 0 ? 'disabled':''}} value="{{ $expense->payment }}" id="payment"
                                required>
                                <option {{ $expense->payment == 'ESPECES' ? 'selected' : '' }}
                                    value="ESPECES">ESPECES</option>
                                <option
                                    {{ $expense->payment == 'MOBILEMONEY' ? 'selected' : '' }}
                                    value="MOBILEMONEY">MOBILE MONEY</option>

                                <option {{ $expense->payment == 'CHEQUES' ? 'selected' : '' }}
                                    value="CHEQUES">CHEQUES</option>

                                <option {{ $expense->payment == 'VIREMENT' ? 'selected' : '' }}
                                    value="VIREMENT">VIREMENT</option>


                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Numéro de la facture</label>
                                <input type="number" {{$expense->paid ==  2   ? 'readonly':''}} name="number_invoice" id="number_invoice" value="{{  old('number_invoice') ? old('number_invoice') : $expense->number_invoice}}" {{$expense->paid != 0 ? 'readonly':''}} class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="mb-3 row">
                               <div class="col-10">
                                    <label for="example-select" class="form-label">Pièce jointe<span
                                        style="color:red;">*</span></label>
                                <input type="file" name="receipt" {{$expense->paid == 2 ? 'disabled':''}} value="{{  old('receipt') ? old('receipt') : $expense->receipt}}" class="form-control">
                               </div>
                               <div class="col-2 mt-3">
                                    @if($expense->receipt)
                                    <a href="{{ asset('storage/' . $expense->receipt) }}" style="font-size:25px" class="d-flex" download>  <i class="mdi mdi-file-outline"></i>  </a>
                                @endif
                               </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Status de la dépense</label>
                                <select class="form-select" id="" name="paid" required>
                                    <option value="">Selectionner le statut de la caisse</option>
                                    <option value="0" {{$expense->paid == 0 ? 'selected' : ''}}>Non payé</option>
                                    <option value="1" {{$expense->paid == 1 ? 'selected' : ''}}>Payé</option>
                                    <option value="2" {{$expense->paid == 2 ? 'selected' : ''}}>Payé et Livré</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

        </div>


        {{-- Debut du bloc pour faire les l'ajout des articles  --}}
        <div class="card mb-md-0 mb-3">
            <div class="card-header">
                Liste des articles
            </div>
            <h5 class="card-title mb-0"></h5>

            <div class="card-body">
                @if ($expense->paid == 0)

                    <form method="POST" id="addDetailForm" autocomplete="on">
                        @csrf
                        <div class="row d-flex align-items-end">
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Article</label>
                                    <input type="text" id="article-name" class="form-control" name="article_name">
                                </div>
                            </div>
                            <div class="col-md-2 col-12">

                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Prix</label>
                                    <input type="number" name="unit_price" id="unit_price" class="form-control"
                                        >
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Quantité</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control"
                                        >
                                </div>
                            </div>
                            <div class="col-md-2 col-12">
                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Total</label>

                                    <input type="number" name="line_amount" id="total" class="form-control"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary" id="add_detail">Ajouter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="detail-list-table table-striped dt-responsive nowrap w-100 table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Article</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <td colspan="1" class="text-right">
                                    <strong>Total:</strong>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td id="val">
                                    <input type="number" id="estimated_ammount" class="estimated_ammount"
                                        value="0" readonly>
                                </td>
                                <td></td>

                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" {{$expense->paid == 2 && !getOnlineUser()->can('view-super-depense') ? 'disabled':''}} class="btn w-100 btn-success">Soumettre</button>
                </div>

            </div>
        </div> <!-- end card-->
    </form>


    </div>
@endsection

@push('extra-js')

    <script>
        $(function() {
            $("#article-name").autocomplete({
            source: function(request, response) {
            // Faites une requête Ajax pour récupérer les noms des articles depuis la base de données
            $.ajax({
                url: "/getArticle",
                dataType: "json",
                data: {
                term: request.term // Terme saisi par l'utilisateur
                },
                success: function(data) {
                    // console.log(data);
                response(data); // Affichez les suggestions d'articles à l'utilisateur
                }
            });
            },
            minLength: 2 // Nombre de caractères avant de déclencher l'autocomplétion
            });
        });
    </script>
    <!-- Inclure jQuery -->

    <script>
    var expense = {!! json_encode($expense) !!}


    var baseUrl = "{{ url('/') }}"
    var ROUTESTOREDETAILEXPENSE = "{{ route('expense.detail.store') }}"
    var TOKENSTOREDETAILEXPENSE = "{{ csrf_token() }}"

    var ROUTEUPDATETOTALEXPENSE = "{{ route('expense.updateTotal') }}"
    var TOKENUPDATETOTALEXPENSE = "{{ csrf_token() }}"

    var ROUTEGETDETAIL = "{{ route('expense.getDetail',$expense->id)}}"

    </script>
    <script src="{{asset('viewjs/bank/expense.js')}}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Inclure jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('/adminassets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/adminassets/js/pages/demo.datatable-init.js') }}"></script>


@endpush
