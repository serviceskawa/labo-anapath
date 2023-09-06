@extends('layouts.app2')

@section('title', 'Vue-article | Articles')
@section('css')

@endsection

@section('content')




<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bs-example-modal-lg-create">Ajouter un nouvel article</button>
            </div>
            <h4 class="page-title">Articles</h4>
        </div>
        @include('articles.create')
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
            <h5 class="card-title mb-0">Liste des articles</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Nom de l'article</th>
                            <th>Qté en stock</th>
                            <th>Unité de mesure</th>
                            <th>Date d'expiration</th>
                            <th>Numéro du lot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($articles as $item)
                        <tr>
                            <td>{{ $item->article_name }}</td>

                            @if ($item->quantity_in_stock <= $item->minimum)
                                <td style="background-color: red; color:red; font-weight :900;">{{
                                    $item->quantity_in_stock }}</td>
                                @else
                                <td>{{ $item->quantity_in_stock }}</td>
                                @endif

                                <td>{{ $item->unit_of_measurement }}</td>
                                <td>{{ $item->expiration_date }}</td>
                                <td>{{ $item->lot_number }}</td>
                                <td>
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#bs-example-modal-lg-show-{{ $item->id }}"
                                        class="btn btn-primary"><i class="mdi mdi-eye"></i>
                                    </button>
                                    @include('articles.show',['item' => $item])

                                    <a class="btn btn-primary" href="{{ route('article.edit',$item->id)}}"><i
                                            class="mdi mdi-lead-pencil"></i>
                                    </a>

                                    <a class="btn btn-danger" href="{{ route('article.delete',$item->id) }}"><i
                                            class="mdi mdi-trash-can-outline"></i>
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