@extends('layouts.app2')

@section('title', 'Macroscopie')

@section('content')
<div class="row">

    <div class="page-title-box">
        <div class="page-title-right mr-3">
            <a href="{{ route('macro.create') }}"><button type="button" class="btn btn-primary">Ajouter une macroscopie</button></a>
        </div>
        <h4 class="page-title">Macroscopie</h4>
    </div>

    <div class="">

        <!----MODAL---->
        @include('layouts.alerts')
        @include('examens.signals.create')

        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-0">Historique de traitement des demandes</h5>
                    </div>
                    <div class="">
                        <button type="button" class="btn btn-primary" id="changeState" style="display: none;" >Changer d'étape</button>
                    </div>
                </div>


                <div id="cardCollpase1" class="show collapse pt-3">

                    <div class="row mb-3">

                        <div class="col-lg-3">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Demande d'examen</label>
                                <select name="id_test_pathology_order" id="id_test_pathology_order" class="form-select select2" data-toggle="select2">
                                    <option value="">Toutes les demnades</option>
                                    @forelse ($orders as $order)
                                        <option value="{{ $order->id }}">{{ $order->code }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                        </div> 

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Date</label>
                                <input type="date" name="date" id="date" class="form-control">
                            </div>
                        </div> 

                        <div class="col-lg-4">

                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Réalisé par</label>
                                <select name="id_employee" id="id_employee" class="form-select select2" data-toggle="select2">
                                    <option value="">Tous les laborantin</option>
                                    @forelse ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->fullname() }}</option>
                                    @empty
                                    @endforelse
                                </select>
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
                                <th>Code</th>
                                <th>Macro Réalisée par</th>
                                <th>Date Macro</th>
                                <th>Date Montage</th>
                                <th>Etapes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> 





































        <div class="card mb-md-0 mt-5">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h5 class="card-title d-flex mb-3">
                            Liste des Examens à traiter en priorité
                            <p id="count_data">

                            </p>
                        </h5>
                    </div>
                    <div class="">
                        <button type="button" class="btn btn-primary" id="deleteSelectedRows" style="display: none;" >Terminer la Macroscopie</button>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-lg-2 p-1 ml-3 alert alert-danger rounded-pill"
                        style="margin-right: 5px; text-align:center;">
                        Cas urgent
                    </div>
                </div>


                {{-- Formulaire --}}
                <div class="col-lg-3">
                    <div class="mb-3">
                        <label for="example-fileinput" class="form-label">Type d'examen</label>
                        <select name="typeExamenId" id="typeExamenId"  class="form-select select2">
                            <option value="">Tous</option>
                            @foreach ($type_orders as $item)
                                @if($item->slug != "immuno-interne" && $item->slug != "immuno-exterme" && $item->slug != "cytologie")
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
        <script src="{{asset('adminassets/js/vendor/dataTables.checkboxes.min.js')}}"></script>
         <!-- demo app -->
         {{-- <script src="{{asset('adminassets/js/pages/demo.products.js')}}"></script> --}}
         <!-- end demo js-->
        <script>
            var baseUrl = "{{ url('/') }}"
            // var ROUTETESTORDERDATATABLE = "{{ route('test_order.getTestOrdersforDatatable') }}"
            var ROUTETESTORDERDATATABLE = "{{ route('macro.getTestOrdersforDatatable') }}"
            var ROUTETESTORDERDATATABLE2 = "{{ route('macro.getTestOrdersforDatatable2') }}"
            var ROUTETESTORDERDATATABLE3 = "{{ route('macro.getTestOrdersforDatatable3') }}"
            var TOKENSTOREDOCTOR = "{{ csrf_token() }}"
        </script>
        <script src="{{ asset('viewjs/macro.js') }}"></script>
        <script src="{{ asset('viewjs/demo-macro.js') }}"></script>
    @endpush
