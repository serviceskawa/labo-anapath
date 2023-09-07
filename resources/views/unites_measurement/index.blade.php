@extends('layouts.app2')

@section('title', 'Unité de mesure')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create">Ajouter une unité de mesure</button>
            </div>
            <h4 class="page-title">Unité de mesure</h4>
        </div>
        @include('unites_measurement.create')
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
            <h5 class="card-title mb-0">Liste des unités de mesure</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($unitMeasurements as $item)
                        <tr>
                            <td><input type="checkbox" name="" id=""></td>
                            <td>{{ $item->name }}</td>
                            <td>
                                {{-- <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-show-{{ $item->id }}"
                                    class="btn btn-primary"><i class="mdi mdi-eye"></i>
                                </button>
                                @include('unites_measurement.show',['item' => $item]) --}}

                                <a class="btn btn-primary" href="#" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}"><i
                                        class="mdi mdi-lead-pencil"></i>
                                </a>
                                @include('unites_measurement.edit',['item' => $item])

                                {{-- <a class="btn btn-danger" href="{{ route('unit.delete',$item->id) }}"><i
                                        class="mdi mdi-trash-can-outline"></i>
                                </a> --}}
                                <button type="button" onclick="deleteModalUnit({{ $item->id }})"
                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
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
<script>
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
@endpush