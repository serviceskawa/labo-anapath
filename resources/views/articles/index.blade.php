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
        @include('articles.create',['units'=>$units])
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
                            <th>Prix (FCFA)</th>
                            <th>Date d'expiration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($articles as $item)

                        <tr>
                            <td>{{ $item->article_name }}</td>

                            @if ($item->quantity_in_stock <= $item->minimum && $item->quantity_in_stock >= 1)
                                <td
                                    style="background-color: rgb(255, 223, 12); color:rgb(255, 223, 12); font-weight :900;">
                                    {{
                                    $item->quantity_in_stock }}/{{ $item->unit ? $item->unit->name:'' }}</td>
                                @elseif($item->quantity_in_stock == 0)
                                <td style="background-color: red; color:red; font-weight :900;">{{
                                    $item->quantity_in_stock }}/{{ $item->unit->name }}</td>
                                @elseif ($item->quantity_in_stock > $item->minimum)
                                <td>{{ $item->quantity_in_stock }}</td>
                                @endif

                                <td>{{ $item->prix }}</td>

                                @if ($item->expiration_date==null)
                                <td>Pas de date</td>
                                @else
                                <td>{{$item->expiration_date}}</td>
                                @endif

                                <td>
                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#bs-example-modal-lg-show-{{ $item->id }}"
                                        class="btn btn-primary"><i class="mdi mdi-eye"></i>
                                    </button>
                                    @include('articles.show',['item' => $item,'movs'=>$movs])
                                    <a class="btn btn-info" href="#" data-bs-toggle="modal"
                                        data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}"><i
                                            class="mdi mdi-lead-pencil"></i>
                                    </a>
                                    @include('articles.edit',['item' => $item,'units'=>$units])

                                    @if ($item->quantity_in_stock==0)
                                    <a class="btn btn-danger" href="#" onclick="deleteModalArticle({{ $item->id }})"><i
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
    var baseUrl = "{{url('/')}}"
</script>
<script src="{{asset('viewjs/patient/index.js')}}"></script>
@endpush
