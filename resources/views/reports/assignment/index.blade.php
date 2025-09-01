@extends('layouts.app2')

@section('title', 'Reports')


@section('css')

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                {{-- <div class="page-title-right mr-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#standard-modal">Affecter des comptes rendu</button>
                </div> --}}
                <h4 class="page-title">Affectation des comptes rendu</h4>
            </div>
        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">
            <div class="card-body">

                <form method="POST" action="{{ route('report.assignment.store') }}" autocomplete="off">
                    @csrf
                    <div class="row d-flex align-items-end">
                        <div class="col-md-5 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Docteur<span
                                        style="color:red;">*</span></label>
                                <select class="form-select select2" data-toggle="select2" required id="user_id"
                                    name="user_id" required>
                                    <option value="">Sélectionner un docteur</option>
                                    {{-- @forelse (getUsersByRole('docteur') as $user)
                                        <option value="{{ $user->id }}">{{ $user->fullname() }}</option>
                                    @empty
                                        Ajouter un utilisateur avec le rôle docteur
                                    @endforelse --}}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" id="add_expense">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label for="example-select" class="form-label">Demande d'examen<span
                                    style="color:red;">*</span></label>
                            <select name="id_test_pathology_order" id="id_test_pathology_order" class="form-select select2"
                                data-toggle="select2">
                                <option value="">Toutes les demandes</option>
                                {{-- @forelse ($orders as $order)
                                    <option value="{{ $order->id }}">{{ $order->code }}
                                        {{ $order->test_affiliate ? '(' . $order->test_affiliate . ')' : '' }}</option>
                                @empty
                                @endforelse --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label for="example-select" class="form-label">Docteur<span style="color:red;">*</span></label>
                            <select class="form-select select2" data-toggle="select2" required id="id_doctor">
                                <option value="">Sélectionner un docteur</option>
                                {{-- @forelse (getUsersByRole('docteur') as $user)
                                    <option value="{{ $user->id }}">{{ $user->fullname() }}</option>
                                @empty
                                    Ajouter un utilisateur avec le rôle docteur
                                @endforelse --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Rechercher</label>
                            <input type="text" name="contenu" id="contenu" placeholder="Notes des affectations"
                                class="form-control">
                        </div>
                    </div>
                </div>

                <h5 class="card-title mb-0">Liste des affectations</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable2" class="table-striped dt-responsive nowrap w-100 table">

                        <thead>
                            <tr>
                                {{-- <th>#</th> --}}
                                <th>Code</th>
                                <th>Docteur</th>
                                <th>Nombre d'affectation</th>
                                <th>Date d'affectation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        {{-- <tbody>
                            @foreach ($assignments as $key => $assignment)

                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ $assignment->code }}</td>
                                    <td>{{ $assignment->user->fullname() }}</td>
                                    <td> {{ $assignment->details()->count() }} </td>
                                    <td> {{date_format($assignment->created_at,'d-m-Y')}} </td>
                                    <td>
                                        <a class="btn btn-primary" href="{{route('report.assignment.detail.index',$assignment->id)}}">
                                            <i class="uil-eye"></i>
                                        </a>

                                        @if ($assignment->details()->count() >= 1)
                                            <a class="btn btn-warning" href="{{route('report.assignment.print',$assignment->id)}}">
                                                <i class="mdi mdi-printer"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> --}}

                    </table>

                </div>
            </div>
        </div> 
    </div>
@endsection

@push('extra-js')
    <script>
        var ROUTETESTORDERDATATABLE = "{{ route('assignment.getTestOrdersforDatatable') }}"
        var TOKENSTOREDOCTOR = "{{ csrf_token() }}"
        var ROUTESEARCHORDERS = "{{ route('search.orders.assignment') }}"
        var ROUTESEARCHDORCTORS = "{{ route('search.doctors.assignment') }}"
    </script>

    <script>
        $('#id_test_pathology_order').select2({
            ajax: {
                url: ROUTESEARCHORDERS,
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
                                text: order.code + (order.test_affiliate ? ' (' + order.test_affiliate +
                                    ')' : '')
                            };
                        }),
                        pagination: {
                            more: data.has_more
                        }
                    };
                }
            },
            minimumInputLength: 2,
            placeholder: 'Tapez pour rechercher une demande...'
        });

        $('#id_doctor').select2({
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

    <script>
        /* DATATABLE */
        $(document).ready(function() {

            var table = $('#datatable2').DataTable({

                "columnDefs": [{
                    "targets": [0],
                    "searchable": false
                }],
                "bFilter": false,
                "language": {
                    "lengthMenu": "Afficher _MENU_ enregistrements par page",
                    "zeroRecords": "Aucun enregistrement disponible",
                    "info": "Afficher page _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun enregistrement disponible",
                    "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
                    "sSearch": "Rechercher:",
                    "paginate": {
                        "previous": "Précédent",
                        "next": "Suivant"
                    }
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: ROUTETESTORDERDATATABLE,
                    data: function(d) {
                        d.id_test_pathology_order = $('#id_test_pathology_order').val()
                        d.id_doctor = $('#id_doctor').val()
                        d.date = $('#date').val()
                        d.contenu = $('#contenu').val()
                    }
                },
                columns: [

                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'doctor',
                        name: 'doctor',
                    },
                    {
                        data: 'nbr_assignment',
                        name: 'nbr_assignment',
                    },
                    {
                        data: 'date_assignment',
                        name: 'date_assignment',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ],
                select: {
                    style: "multi",
                    selector: "td:first-child",
                },
                order: [
                    [0, 'asc']
                ],

            });
            // Recherche selon les types d'examen
            $("#id_test_pathology_order").on("change", function() {
                // alert(this.value)
                table.draw();
            });
            // Recherche selon les cas
            $("#id_doctor").on("change", function() {
                // alert(this.value)
                table.draw();
            });

            $('#contenu').on("input", function() {
                // alert(this.value)
                table.draw();
            });
        });
    </script>
@endpush
