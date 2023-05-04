@extends('layouts.app2')

@section('title', 'Reports')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Comptes rendu</h4>

            </div>

            <!----MODAL---->

        </div>
    </div>

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
                <h5 class="card-title mb-0">Liste des comptes rendu</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Code patient</th>
                                <th>Nom Patient</th>
                                <th>Telephone</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th hidden style="display: none"></th>
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
       var baseUrl = "{{url('/')}}"
       var ROUTEGETDATATABLE = "{{ route('report.getReportsforDatatable') }}"
    </script>
    <script src="{{asset('viewjs/report/index.js')}}"></script>
@endpush
