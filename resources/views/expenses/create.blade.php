{{-- <div class="modal fade" id="bs-example-modal-lg-create" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Ajouter une nouvelle dépense</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('all_expense.store') }}" method="POST" autocomplete="on">
                    @csrf
                    <div class="modal-body">

                        <div style="text-align:right;"><span style="color:red;">*</span>Champs obligatoires</div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Montant<span
                                        style="color:red;">*</span></label>
                                <input type="number" name="amount" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Preuve</label>
                                <input type="file" name="receipt" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Catégorie de dépense<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="" name="expense_categorie_id" required>
                                    <option value="">Selectionner la catégorie de dépense</option>
                                    @foreach ($expenses_categorie as $ex_cat)
                                    <option value="{{ $ex_cat->id }}">{{ $ex_cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Ticket de caisse<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="" name="cashbox_ticket_id" required>
                                    <option value="">Selectionner le ticket de caisse</option>
                                    @foreach ($cash_ticket as $ch_ticket)
                                    <option value="{{ $ch_ticket->id }}">{{ $ch_ticket->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="example-select" class="form-label">Status<span
                                        style="color:red;">*</span></label>
                                <select class="form-select" id="" name="paid" required>
                                    <option value="">Selectionner le statut de la caisse</option>
                                    <option value="0">Non payé</option>
                                    <option value="1">Payé</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-12">
                                <label for="simpleinput" class="form-label">Description</label>
                                <textarea type="text" name="description" class="form-control" cols="12"
                                    rows="4"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter une nouvelle dépense</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div> --}}



@extends('layouts.app2')

@section('title', 'Ajouter une nouvelle dépense')

@section('css')

<style>
    input[data-switch]+label {
        width: 90px !important;
    }

    input[data-switch]:checked+label:after {
        left: 62px !important;
    }
</style>
@endsection

@section('content')

@include('layouts.alerts')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <a href="{{ route('all_expense.index') }}" class="btn btn-primary">Toutes les dépenses</a>
            </div>
            <h4 class="page-title">Dépenses</h4>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <div class="col-12">
                <div class="page-title-box">
                    {{-- <div class="page-title-right mt-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                    </div> --}}
                    Ajouter une nouvelle dépense
                </div>
            </div>
        </div>

        <form action="{{ route('all_expense.store') }}" method="post" autocomplete="on" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div style="text-align:right;"><span style="color:red;">*</span>Champs obligatoires</div>

                <div class="mb-3 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">Nom de l'article<span
                            style="color:red;">*</span></label>
                    <select class="form-select select2" data-toggle="select2" name="item_id" required>
                        <option>Sélectionner l'article</option>
                        @foreach ($articles as $article)
                        <option value="{{ $article->id }}">{{ $article->article_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 col-lg-12">
                    <label for="simpleinput" class="form-label">Prix unitaire<span style="color:red;">*</span></label>
                    <input type="number" name="unit_price" id="unit_price" class="form-control" required>
                </div>

                <div class="mb-3 col-lg-12">
                    <label for="simpleinput" class="form-label">Quantité<span style="color:red;">*</span></label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required>
                </div>

                <div class="mb-3 col-lg-12">
                    <label for="simpleinput" class="form-label">Preuve</label>
                    <input type="file" name="receipt" class="form-control">
                </div>

                <div class="mb-3 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">Catégorie de dépense<span
                            style="color:red;">*</span></label>
                    <select class="form-select select2" data-toggle="select2" name="expense_categorie_id" required>
                        <option>Sélectionner la categorie de dépense</option>
                        @foreach ($expenses_categories as $ex_cat)
                        <option value="{{ $ex_cat->id }}">{{ $ex_cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">Ticket de caisse<span
                            style="color:red;">*</span></label>
                    <select class="form-select select2" data-toggle="select2" name="cashbox_ticket_id" required>
                        <option>Sélectionner l'article</option>
                        @foreach ($cash_tickets as $ch_ticket)
                        <option value="{{ $ch_ticket->id }}">{{ $ch_ticket->code }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 col-md-12">
                    <label for="exampleFormControlInput1" class="form-label">Fournisseur<span
                            style="color:red;">*</span></label>
                    <select class="form-select select2" data-toggle="select2" name="supplier_id" required>
                        <option>Sélectionner le fournisseur</option>
                        @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 col-lg-12">
                    <label for="example-select" class="form-label">Status<span style="color:red;">*</span></label>
                    <select class="form-select" id="" name="paid" required>
                        <option value="">Selectionner le statut de la caisse</option>
                        <option value="0">Non payé</option>
                        <option value="1">Payé</option>
                    </select>
                </div>

                <div class="mb-3 col-lg-12">
                    <label for="simpleinput" class="form-label">Montant total</label>
                    <input type="number" readonly name="total_amount" id="total_amount" class="form-control">
                </div>

            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter une nouvelle dépense</button>
            </div>
        </form>
    </div>
</div>

@endsection



@push('extra-js')
<script>
    // Fonction pour calculer et mettre à jour le total
    function calculateTotal() {
        var unitPrice = parseFloat(document.getElementById('unit_price').value);
        var quantity = parseInt(document.getElementById('quantity').value);

        if (!isNaN(unitPrice) && !isNaN(quantity)) {
            var total = unitPrice * quantity;
            document.getElementById('total_amount').value = total;
        } else {
            document.getElementById('total_amount').value = '';
        }
    }

    // Écouteurs d'événements pour les champs unit_price et quantity
    document.getElementById('unit_price').addEventListener('input', calculateTotal);
    document.getElementById('quantity').addEventListener('input', calculateTotal);
</script>
<script>
    var baseUrl = "{{ url('/') }}"
        var ROUTESTOREPATIENT = "{{ route('patients.storePatient') }}"
        var TOKENSTOREPATIENT = "{{ csrf_token() }}"
        var ROUTESTOREHOSPITAL = "{{ route('hopitals.storeHospital') }}"
        var TOKENSTOREHOSPITAL = "{{ csrf_token() }}"
        var ROUTESTOREDOCTOR = "{{ route('doctors.storeDoctor') }}"
        var TOKENSTOREDOCTOR = "{{ csrf_token() }}"
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
</script>
<script src="{{ asset('viewjs/test/order/create.js') }}"></script>
@endpush