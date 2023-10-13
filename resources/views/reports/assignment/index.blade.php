@extends('layouts.app2')

@section('title', 'Reports')

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

            <!----MODAL---->

        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">
            <div class="card-body">

                <h5 class="card-title mb-0">Liste des affectations</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Docteur</th>
                                <th>Nombre d'affectation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $key=>$doctor)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $doctor->fullname() }}</td>
                                    <td>
                                        @forelse ($doctor->assignment as $assignment)
                                            <ul>
                                                <li> {{$assignment->report->order->code}} </li>
                                            </ul>
                                        @empty
                                            <span class="text-muet">Aucune affectation</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <a type="button" href="{{route('report.assignment.create',$doctor->id)}}"
                                            class="btn btn-primary" title="Affecter un compte rendu"><i
                                                class="mdi mdi-lead-pencil"></i>
                                        </a>
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

@endpush
