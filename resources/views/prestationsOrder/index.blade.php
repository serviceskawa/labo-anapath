@extends('layouts.app2')

@section('title', 'Demande de prestations')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Demande de prestation</h4>
            </div>

        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">

            <div class="card-body">
                @include('prestationsOrder.edit')
                <!-- Ajouter un examen | si le statut de la demande est 1 alors on peut plus ajouter de nouveau examen dans la demande-->
                {{-- @if ($test_order->status != 1) --}}

                <form method="POST" action={{ route('prestations_order.store') }} id="addDetailForm" autocomplete="off">
                    @csrf
                    <div class="row d-flex align-items-end">
                        <div class="col-md-4 col-12">

                            <div class="mb-3">
                                <label for="example-select" class="form-label">Patients</label>
                                <select class="form-select select2" data-toggle="select2" id="" name="patient_id"
                                    required>
                                    <option>Tous les patients</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}"> {{ $patient->firstname }}
                                            {{ $patient->lastname }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">

                            <div class="mb-3">
                                <label for="example-select" class="form-label">Prestations</label>
                                <select class="form-select select2" data-toggle="select2" id="prestation_id"
                                    name="prestation_id" required onchange="getTest()">
                                    <option>Toutes les prestations</option>
                                    @foreach ($prestations as $prestation)
                                        <option data-category_test_id="{{ $prestation->id }}" value="{{ $prestation->id }}">
                                            {{ $prestation->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">

                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Prix</label>
                                <input type="text" name="total" id="total" class="form-control" required readonly>
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
                <h5 class="card-title mb-0">Liste des demandes</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Prestations</th>
                                <th>Prix</th>
                                <th>Status</th>
                                <th>Actions</th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($prestationOrders as $prestationOrder)
                                <tr>
                                    <td>{{ $prestationOrder->id }}</td>
                                    <td>{{ $prestationOrder->patient->firstname }} {{ $prestationOrder->patient->lastname }}
                                    </td>
                                    <td>{{ $prestationOrder->prestation->name }}</td>
                                    <td>{{ $prestationOrder->total }}</td>
                                    <td>
                                        <span
                                            class="bg-{{ $prestationOrder->status != 1 ? 'danger' : 'success' }} badge float-end">{{ $prestationOrder->status != 1 ? 'en cours' : 'payé' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($prestationOrder->status != 1)
                                            <button type="button" onclick="edit({{ $prestationOrder->id }})"
                                                class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                            <button type="button" onclick="deleteModal({{ $prestationOrder->id }})"
                                                class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
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

    <script type="text/javascript">
        var baseUrl = "{{url('/')}}"
        var ROUTEGETPRESTATIONORDER = "{{ route('prestations_order.getPrestationOrder') }}"
        var TOKENGETPRESTATIONORDER = "{{ csrf_token() }}"
    </script>
    <script src="{{asset('viewjs/prestation/prestationorder.js')}}"></script>
@endpush
