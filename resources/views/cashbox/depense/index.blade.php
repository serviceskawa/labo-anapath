@extends('layouts.app2')

@section('title', 'Caisse de dépense')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}
                @if (getOnlineUser()->can('view-add-cashbox-expense'))
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#standard-modal">Approvissier la caisse</button>
                @endif


            </div>
            <h4 class="page-title">Caisse de dépense</h4>
        </div>

        <!----MODAL---->

        @include('cashbox.depense.create')

        {{-- @include('cashbox.depense.edit') --}}

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
            <a href="javascript:void(0);" class="btn btn-success mb-4">Solde actuel : {{ $totalToday }}</a>
            <h5 class="card-title mb-0">Caisse de Dépense</h5>

            <div id="cardCollpase1" class="collapse pt-3 show">


                <table id="datatable1" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Montant</th>

                            <th>Utilisateur</th>
                            <th>Action</th>

                        </tr>
                    </thead>



                    <tbody>

                        @foreach ($cashadds as $key => $item)
                        <tr class="{{ $item->bank ? '': 'table-danger' }}">
                            <td>{{ ++$key }}</td>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->bank ? $item->amount:'-'.$item->amount }}</td>

                            <td>{{ $item->user->firstname }} {{ $item->user->lastname }}</td>
                            <td>
                                <a class="btn btn-primary" href="#" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}"><i
                                        class="mdi mdi-eye"></i>
                                </a>
                                @include('cashbox.depense.edit_depense',['item' => $item])
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
    var baseUrl = "{{ url('/') }}"
</script>
<script src="{{ asset('viewjs/bank/cashbox.js') }}"></script>
@endpush
