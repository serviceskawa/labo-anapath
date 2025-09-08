@extends('layouts.app2')

@section('title', 'Macroscopie')

@section('content')
    <div class="row">

        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <a href="{{ route('macro.create') }}"><button type="button" class="btn btn-primary">Ajouter une
                        macroscopie</button></a>
            </div>
            <h4 class="page-title">Macroscopie</h4>
        </div>

        <div class="">
            @include('layouts.alerts')
            @include('examens.signals.create')

            <div class="card mb-md-0 mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="card-title mb-0">Historique de traitement des demandes</h5>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-primary" id="changeState" style="display: none;">Changer
                                d'étape</button>
                        </div>
                    </div>

                    <div id="cardCollpase1" class="show collapse pt-3">
                        <div class="row mb-3">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Demande d'examen</label>
                                    <select name="id_test_pathology_order" id="id_test_pathology_order"
                                        class="form-select select2" data-toggle="select2">
                                        <option value="">Toutes les demandes</option>
                                        {{-- @forelse ($orders as $order)
                                    <option value="{{ $order->id }}">{{ $order->code }}</option>
                                    @empty
                                    @endforelse --}}
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Date</label>
                                    <input type="date" name="date" id="date" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Réalisé par</label>
                                    <select name="id_employee" id="id_employee" class="form-select select2"
                                        data-toggle="select2">
                                        <option value="">Tous les laborantin</option>
                                        @forelse ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->fullname() }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="example-fileinput" class="form-label">Rechercher</label>
                                    <input type="text" name="contenu" id="contenu" class="form-control">
                                </div>
                            </div>
                        </div>

                        <table id="datatable1" class="dt-responsive nowrap w-100 table">
                            <thead>
                                <tr>
                                    <th class="all" style="width: 20px;">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                        </div>
                                    </th>
                                    <th>Date Macro</th>
                                    <th>Code</th>
                                    <th>Réalisé par</th>
                                    {{-- <th>Date Montage</th>
                                    <th>Etapes</th> --}}
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Premier DataTable test --}}
            <div class="card mb-md-0 mt-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="card-title d-flex mb-3">
                                Demandes d'examens urgent à traiter
                            </h5>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-primary" id="deleteSelectedRows"
                                style="display: none;">Terminer la Macroscopie</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-2 p-1 ml-3 alert alert-danger rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            Cas urgent
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Type d'examen</label>
                            <select name="typeExamenId" id="typeExamenId" class="form-select select2">
                                <option value="">Tous</option>
                                @foreach ($type_orders as $item)
                                    @if ($item->slug != 'immuno-interne' && $item->slug != 'immuno-exterme')
                                        <option value="{{ $item->id }}">
                                            {{ $item->title }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-centered w-100 dt-responsive nowrap" id="products-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="all" style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                            </div>
                                        </th>
                                        <th>Date limite</th>
                                        <th>Code</th>
                                        {{-- <th>Macro réalisé par</th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deuxieme DataTables Histologie et Biopsie --}}
            <div class="card mb-md-0 mt-5">
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="card-title d-flex mb-3">
                                Demandes d'examens <span style="color: green;"> Histologie-Biopsie </span> à traiter
                            </h5>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-primary" id="deleteSelectedRows"
                                style="display: none;">Terminer la Macroscopie</button>
                        </div>
                    </div>

                    <div class="row mb-3 d-flex">
                        <div class="col-lg-2 p-1 ml-3 alert alert-success rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#histologie" style="text-decoration : none; color:inherit;">Histologie-Biopsie</a>
                        </div>

                        <div class="col-lg-2 p-1 ml-3 alert alert-light rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#piece-operatoire" style="text-decoration : none; color:inherit;">Pièce
                                opératoire</a>
                        </div>

                        <div class="col-lg-2 p-1 ml-3 alert alert-light rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#cytologie" style="text-decoration : none; color:inherit;">Cytologie</a>
                        </div>
                    </div>

                    <div class="row" id="histologie">
                        <div class="table-responsive">
                            <table class="table table-centered w-100 dt-responsive nowrap"
                                id="products-datatable-histologie-biopsie">
                                <thead class="table-light">
                                    <tr>
                                        <th class="all" style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                            </div>
                                        </th>
                                        <th>Date limite</th>
                                        <th>Code</th>
                                        {{-- <th>Macro réalisé par</th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Troisieme DataTables Piece Operatoire --}}
            <div class="card mb-md-0 mt-5" id="piece-operatoire">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="card-title d-flex mb-3">
                                Demandes d'examens <span style="color: green;"> Pièce Opératoire </span> à traiter
                            </h5>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-primary" id="deleteSelectedRows"
                                style="display: none;">Terminer la Macroscopie</button>
                        </div>
                    </div>

                    <div class="row mb-3 d-flex">
                        <div class="col-lg-2 p-1 ml-3 alert alert-light rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#histologie" style="text-decoration : none; color:inherit;">Histologie-Biopsie</a>
                        </div>

                        <div class="col-lg-2 p-1 ml-3 alert alert-success rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#piece-operatoire" style="text-decoration : none; color:inherit;">Pièce
                                opératoire</a>
                        </div>

                        <div class="col-lg-2 p-1 ml-3 alert alert-light rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#cytologie" style="text-decoration : none; color:inherit;">Cytologie</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-centered w-100 dt-responsive nowrap"
                                id="products-datatable-piece-operatoire">
                                <thead class="table-light">
                                    <tr>
                                        <th class="all" style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                            </div>
                                        </th>
                                        <th>Date limite</th>
                                        <th>Code</th>
                                        {{-- <th>Macro réalisé par</th> --}}
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Troisieme DataTables Cytologie --}}
            <div class="card mb-md-0 mt-5" id="cytologie">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="card-title d-flex mb-3">
                                Demandes d'examens <span style="color: green;"> Cytologie </span> à traiter
                            </h5>
                        </div>
                        <div class="">
                            <button type="button" class="btn btn-primary" id="deleteSelectedRows"
                                style="display: none;">Terminer la Macroscopie</button>
                        </div>
                    </div>

                    <div class="row mb-3 d-flex">
                        <div class="col-lg-2 p-1 ml-3 alert alert-light rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#histologie" style="text-decoration : none; color:inherit;">Histologie-Biopsie</a>
                        </div>

                        <div class="col-lg-2 p-1 ml-3 alert alert-light rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#piece-operatoire" style="text-decoration : none; color:inherit;">Pièce
                                opératoire</a>
                        </div>

                        <div class="col-lg-2 p-1 ml-3 alert alert-success rounded-pill"
                            style="margin-right: 5px; text-align:center;">
                            <a href="#cytologie" style="text-decoration : none; color:inherit;">Cytologie</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-centered w-100 dt-responsive nowrap"
                                id="products-datatable-cytologie">
                                <thead class="table-light">
                                    <tr>
                                        <th class="all" style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="customCheck1">
                                            </div>
                                        </th>
                                        <th>Date limite</th>
                                        <th>Code</th>
                                        <th>Macro réalisé par</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('extra-js')
    <script src="{{ asset('adminassets/js/vendor/dataTables.checkboxes.min.js') }}"></script>

    <!-- demo app -->
    {{-- <script src="{{asset('adminassets/js/pages/demo.products.js')}}"></script> --}}
    <!-- end demo js-->

    <script>
        var baseUrl = "{{ url('/') }}"
        var ROUTETESTORDERDATATABLE = "{{ route('macro.getTestOrdersforDatatable') }}"
        var ROUTETESTORDERDATATABLE2 = "{{ route('macro.getTestOrdersforDatatable2') }}"
        var ROUTETESTORDERDATATABLE3 = "{{ route('macro.getTestOrdersforDatatable3') }}"
        var ROUTETESTORDERDATATABLEHISTOLOGIE = "{{ route('macro.getTestOrdersforDatatableHistologie') }}"
        var ROUTETESTORDERDATATABLEPIECEOPERATOIRE = "{{ route('macro.getTestOrdersforDatatablePieceOperatoire') }}"
        var ROUTETESTORDERDATATABLECYTOLOGIE = "{{ route('macro.getTestOrdersforDatatableCytologie') }}"
        var TOKENSTOREDOCTOR = "{{ csrf_token() }}"
        var ROUTEPAATHOLOGIETESTORDERS = "{{ route('macro.search.orders') }}"
    </script>

    <script>
$(document).ready(function() {
    // Event delegation pour les boutons de suppression
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const button = $(this);

        Swal.fire({
            title: "Voulez-vous continuer?",
            text: "Cette action est irréversible",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, supprimer",
            cancelButtonText: "Annuler",
            confirmButtonColor: '#d33',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`{{ url('/macro/delete') }}/${id}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur de suppression');
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Erreur: ${error.message}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Supprimé!',
                    text: 'L\'élément a été supprimé avec succès',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Recharger le DataTable au lieu de la page complète
                    if (typeof table !== 'undefined') {
                        table.ajax.reload(null, false); // false = garder la pagination
                    } else {
                        location.reload();
                    }
                });
            }
        });
    });
});
</script>

    <script>
        $('#id_test_pathology_order').select2({
            ajax: {
                url: ROUTEPAATHOLOGIETESTORDERS,
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        limit: 20
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.data.map(function(order) {
                            return {
                                id: order.id,
                                text: order.code
                            };
                        }),
                        pagination: {
                            more: data.has_more
                        }
                    };
                }
            },
            minimumInputLength: 2,
            placeholder: 'Tapez pour rechercher une demande...',
            allowClear: true
        });
    </script>

    <script src="{{ asset('viewjs/macro.js') }}"></script>
    <script src="{{ asset('viewjs/demo-macro.js') }}"></script>
    <script src="{{ asset('viewjs/demo-macro-histologie.js') }}"></script>
    <script src="{{ asset('viewjs/demo-macro-pieceoperatoire.js') }}"></script>
    <script src="{{ asset('viewjs/demo-macro-cytologie.js') }}"></script>
@endpush
