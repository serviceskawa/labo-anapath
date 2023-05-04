@extends('layouts.app2')

@section('title', 'Créer un facture')

@section('content')
<div class="">

    @include('layouts.alerts')

    <div class="card my-3">

        <div class="card-body">

            <form action="{{route('invoice.store')}} " method="post" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Demande d'examen<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="test_orders_id"
                            id="test_orders_id" required>
                            <option>Sélectionner une demande d'examen</option>
                            @foreach ($testOrders as $testOrder)
                            <option value="{{ $testOrder->id }}">{{ $testOrder->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date <span style="color:red;">*</span></label>
                        <input type="date" class="form-control" name="invoice_date" id="invoice_date"
                            data-date-format="dd/mm/yyyy" required>
                    </div>

                </div>

        </div>


        <div class="modal-footer">
            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Créer une nouvelle facture</button>
        </div>


        </form>
    </div>
</div>

</div>
@endsection


@push('extra-js')
<script>
    var baseUrl = "{{ url('/') }}";
    var ROUTESTOREDOCTOR = "{{route('doctors.storeDoctor')}}"
    var TOKENSTOREDOCTOR = "{{ csrf_token() }}"
    var ROUTESTOREHOSPITAL = "{{route('hopitals.storeHospital')}}"
    var TOKENSTOREHOSPITAL = "{{ csrf_token() }}"
    var ROUTESTOREPATIENT = "{{ route('patients.storePatient') }}"
    var TOKENSTOREPATIENT = "{{ csrf_token() }}"
    var ROUTEGETTESTORDER = "{{route('test_order.get_all_test_order')}}"
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
</script>
<script src="{{asset('viewjs/invoice/create.js')}}"></script>
@endpush