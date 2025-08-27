@extends('layouts.app2')

@section('title', 'Reports')

@section('content')
    <div class="">
        <div class="page-title-box">
            <div class="page-title-right mr-3 mb-1">
                <a href="{{ route('report.assignment.index') }}" type="button" class="btn btn-primary">Retour à la liste des
                    affectations</a>
            </div>
            <h4 class="page-title">
                Affectation : {{ $assignment->code }}
            </h4>
        </div>

        <form action="{{ route('report.assignment.update') }}" method="post">
            <div class="card my-3">
                <div class="card-body">
                    <input type="hidden" name="id" value="{{ $assignment->id }}">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="example-select" class="form-label">Docteur</label>
                            <select class="form-select select2" data-toggle="select2" name="user_id" id="user_id"
                                required>
                                <option value="">Sélectionner le docteur</option>
                                {{-- @foreach (getUsersByRole('docteur') as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ $assignment->user_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->fullname() }}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="example-select" class="form-label">Date</label>
                            <input type="date" id="date_assignment" class="form-control" name="date"
                                value="{{ $assignment->date ? $assignment->date : now()->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row px-2">
                        <label for="example-select" class="form-label p-0">Note</label>
                        <textarea name="note_assignment" class="form-control" id="" cols="30" rows="5">{{ $assignment->note }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card my-3 ">
                <div class="card-header">
                    Liste des demandes d'examens affectées
                </div>
                <h5 class="card-title mb-0"></h5>

                <div class="card-body">
                    <form method="POST" id="addDetailForm" autocomplete="on">
                        @csrf
                        <div class="row d-flex align-items-end">
                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Code <small
                                            style="text-transform: uppercase; color: gray;">[Demande
                                            d'examen/Reférence]</small></label>
                                    <select class="form-select select2" data-toggle="select2" id="test_order_id"
                                        name="test_order_id">
                                        <option value="">Sélectionner une demande d'examen</option>
                                        {{-- @foreach ($testOrders as $order)
                                    <option value="{{ $order->id }}">
                                        {{ $order->code }} {{ isAffecte($order->id) ? '('.isAffecte($order->id)->fullname() .')' : '' }}
                                        @if (typeExamAffecte($order->id) == 3)
                                            / {{ $order->test_affiliate }}
                                            {{ isAffecteRefence($order->test_affiliate) ? '('.isAffecteRefence($order->test_affiliate)->fullname() .')' : '' }}
                                        @elseif(typeExamAffecte($order->id) == 2)
                                            / {{ $order->test_affiliate }}
                                        @endif
                                    </option>
                                    @endforeach --}}
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Note</label>
                                    <input type="text" name="note" id="note" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2 col-12">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-primary" id="add_detail">Ajouter</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="cardCollpase1" class="show collapse pt-3">
                        <table id="datatable1" class="detail-list-table table-striped dt-responsive nowrap w-100 table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Demande d'examen</th>
                                    <th>Note</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="modal-footer">
                            <button type="submit" class="btn w-100 btn-success">Soumettre</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('extra-js')
    <script>
        var assignment = {!! json_encode($assignment) !!}
        var baseUrl = "{{ url('/') }}"
        var ROUTESTOREDETAILTICKET = "{{ route('report.assignment.detail.store') }}"
        var TOKENSTOREDETAILTICKET = "{{ csrf_token() }}"
        var ROUTEGETDETAIL = "{{ route('report.assignment.getDetail', $assignment->id) }}"
        var ROUTETESTORDERSEARCH = "{{ route('test-orders.search.assignment') }}"
        var ROUTESEARCHDORCTORS = "{{ route('search.doctors.assignment') }}"
    </script>

    <script>
        $('#test_order_id').select2({
            ajax: {
                url: ROUTETESTORDERSEARCH,
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
                                text: order.display_text
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

        $('#user_id').select2({
            ajax: {
                url: ROUTESEARCHDORCTORS,
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
                        results: data.data.map(function(doctor) {
                            return {
                                id: doctor.id,
                                text: doctor.fullname
                            };
                        }),
                        pagination: {
                            more: data.has_more
                        }
                    };
                }
            },
            minimumInputLength: 2,
            placeholder: 'Tapez pour rechercher un docteur...',
            allowClear: true
        });
    </script>

    <script src="{{ asset('viewjs/report/assignment.js') }}"></script>
@endpush
