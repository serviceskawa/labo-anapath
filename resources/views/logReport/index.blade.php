@extends('layouts.app2')

@section('title', 'Historique')

@section('css')

    <link href="{{ asset('adminassets/css/fullcalendar/main.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('adminassets/js/fullcalendar/main.js') }}"></script>


@endsection
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right mr-3">
                    <a href="{{ route('report.index') }}" type="button" class="btn btn-primary">Retour à la liste des comptes rendu</a>
                </div>
                <h4 class="page-title">Historiqques</h4>
            </div>

        </div>
    </div>
    <!-- end page title -->
    <div class="">
        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">

            <div class="card-body">

                <div class="card-widgets">
                    <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                    <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false"
                        aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                    <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
                </div>
                <h5 class="card-title mb-0">Liste des historiques</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Compte rendu</th>
                                <th>Opération</th>
                                <th>Utilisateur</th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->created_at }}</td>
                                    <td>{{ $log->report !=null ? $log->report->code :'Aucun'}}</td>
                                    <td>{{ $log->operation }}</td>
                                    <td>{{ $log->user->fullname() }}</td>
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
        var baseUrl = "{url('/')}"
    </script>
    <script src="{{asset('viewjs/log.js')}}"></script>
@endpush
