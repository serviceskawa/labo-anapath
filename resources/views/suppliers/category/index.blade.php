@extends('layouts.app2')

@section('title', 'Fournisseur')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Ajouter une nouvelle catégorie</button>
            </div>
            <h4 class="page-title">Catégories de fournisseur</h4>
        </div>

        <!----MODAL---->

        @include('suppliers.category.create')

        @include('suppliers.category.edit')

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
            <h5 class="card-title mb-0">Liste des catégories de fournisseurs</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom de la catégorie</th>
                            <th>Description</th>
                            <th>Actions</th>

                        </tr>
                    </thead>



                    <tbody>

                        @foreach ($supplierCategories as $key =>$item)
                        <tr>
                            <td>{{ $key++ }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description }}</td>
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
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/suppliers/category.js')}}"></script>

@endpush
