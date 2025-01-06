@extends('layouts.app2')

@section('title', 'Hopital')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Ajouter un nouvel hôpital</button>
            </div>
            <h4 class="page-title">Hôpitaux</h4>
        </div>

        <!----MODAL---->

        @include('hopitals.create')
        @include('hopitals.edit')

    </div>
</div>


<div class="">
    @include('layouts.alerts')

    <div class="card mb-md-0 mb-3">
        <div class="card-body">
            <div class="card-widgets">
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Liste des hôpitaux</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Nom de l'hôpital</th>
                            <th>Téléphone</th>
                            <!--<th>Email</th>-->
                            <th>Adresse</th>
                            <th>Commission</th>
                            <th>Actions</th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($hopitals as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->telephone }}</td>
                            <!-- <td>{{ $item->email }}</td> -->
                            <td>{{ $item->adresse }}</td>
                            <td>{{ $item->commission.' %' }}</td>
                            <td>
                                <button type="button" onclick="edit({{$item->id}})" class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                <button type="button" onclick="deleteModal({{$item->id}})" class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
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
    var baseUrl = "{{ url('/') }}";
</script>

<script src="{{asset('viewjs/hoptital.js')}}"></script>

@endpush
