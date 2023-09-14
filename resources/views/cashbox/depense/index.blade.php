@extends('layouts.app2')

@section('title', 'Caisse de dépense')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3">
                @foreach (getRolesByUser(Auth::user()->id) as $role)
                {{-- //Lorsque l'utilisateur n'a pas le role nécessaire. --}}

                @if ($role->name == "rootuser")
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#standard-modal">Approvissier la caisse</button>
                @break
                @endif
                @endforeach

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
                            {{-- <th>Banque</th>
                            <th>Numéro de chèque</th> --}}
                            {{-- <th>Facture concernée</th> --}}
                            <th>Utilisateur</th>
                            <th>Action</th>
                            {{-- <th>Description</th> --}}
                            {{-- <th>Actions</th> --}}

                        </tr>
                    </thead>



                    <tbody>

                        @foreach ($cashadds as $key => $item)
                        <tr class="{{ $item->bank ? '': 'table-danger' }}">
                            <td>{{ ++$key }}</td>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->bank ? $item->amount:'-'.$item->amount }}</td>
                            {{-- <td>{{ $item->bank ? $item->bank->name :'Néant' }}</td>
                            <td>{{ $item->cheque_number ? $item->cheque_number : 'Néant' }}</td> --}}
                            {{-- <td>{{ $item->invoice ? ($item->invoice->order? $item->invoice->order->code:''):'' }}
                            </td> --}}
                            <td>{{ $item->user->firstname }} {{ $item->user->lastname }}</td>
                            <td>
                                <a class="btn btn-primary" href="#" data-bs-toggle="modal"
                                    data-bs-target="#bs-example-modal-lg-edit-{{ $item->id }}"><i
                                        class="mdi mdi-eye"></i>
                                </a>
                                @include('cashbox.depense.edit_depense',['item' => $item])
                            </td>
                            {{-- <td>{{ $item->description }}</td> --}}
                            {{-- <td>
                                <button type="button" onclick="editVente({{$item->id}})" class="btn btn-primary"><i
                                        class="mdi mdi-lead-pencil"></i> </button>
                                <button type="button" onclick="deleteModalVente({{$item->id}})"
                                    class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                            </td> --}}

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