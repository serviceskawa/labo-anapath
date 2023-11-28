@extends('layouts.app2')

@section('title', 'Examens2')

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
                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Historique</h5>

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

                        </div> <!-- end col -->

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="example-fileinput" class="form-label">Date</label>
                                <input type="date" name="date" id="date" class="form-control">
                            </div>
                        </div> <!-- end col -->

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

                        </div> <!-- end col -->


                    </div>


                    <table id="datatable1" class="dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Réalisée à</th>
                                <th>Affecté à</th>
                                <th>Statuts</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                    </table>

                </div>
            </div>
        </div> <!-- end card-->

    </div>
    @endsection

    @push('extra-js')
        <script>
            var baseUrl = "{{ url('/') }}"
            // var ROUTETESTORDERDATATABLE = "{{ route('test_order.getTestOrdersforDatatable') }}"
            var ROUTETESTORDERDATATABLE = "{{ route('macro.getTestOrdersforDatatable') }}"
            var URLupdateAttribuate = "{{ url('attribuateDoctor') }}" + '/' + doctor_id + '/' + order_id
        </script>
        <script src="{{ asset('viewjs/macro.js') }}"></script>
    @endpush
