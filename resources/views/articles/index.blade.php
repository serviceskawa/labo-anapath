@extends('layouts.app2')

@section('title', 'Stocks')

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
            @include('articles.create', ['units' => $units])
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


                    <table id="datatable1" class="table  dt-responsive nowrap w-100">
                        <select name="" class="form-control form-control-sm" id="qt"></select>
                        <thead>
                            <tr>
                                <th>Nom de l'article</th>
                                <th>Qté en stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($articles as $item)
                                <tr class="{{ $item->quantity_in_stock == 0 ? 'table-danger': ($item->quantity_in_stock <= $item->minimum ? 'table-warning' :'') }}" >
                                    <td>{{ $item->article_name }}</td>
                                    <td>{{ $item->quantity_in_stock }} {{ $item->unit ? $item->unit->name:'' }} <span style="display: none">  {{$item->quantity_in_stock == 0 ? 2: ($item->quantity_in_stock <= $item->minimum ? 1 :'')}} </span></td>

                                    <td>
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#bs-example-modal-lg-show-{{ $item->id }}"
                                            class="btn btn-primary"><i class="mdi mdi-eye"></i>
                                        </button>
                                        @include('articles.show', ['item' => $item, 'movs' => $movs])
                                        <a class="btn btn-info" href="#" data-bs-toggle="modal"
                                            data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}"><i
                                                class="mdi mdi-lead-pencil"></i>
                                        </a>
                                        @include('articles.edit', ['item' => $item, 'units' => $units])

                                        @if ($item->quantity_in_stock == 0)
                                            <a class="btn btn-danger" href="#"
                                                onclick="deleteModalArticle({{ $item->id }})"><i
                                                    class="mdi mdi-trash-can-outline"></i>
                                            </a>
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
    <script>
        // var baseUrl = "{{ url('/') }}"
    </script>
    {{-- <script src="{{ asset('viewjs/patient/index.js') }}"></script> --}}
    <script src="{{ asset('viewjs/article.js') }}"></script>
@endpush
