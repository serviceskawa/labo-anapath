@extends('layouts.app2')

@section('title', 'Remboursement')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Ajouter une demande de remboursements</h4>
        </div>

        <!----MODAL---->
    </div>
</div>

<div class="">

    @include('layouts.alerts')

    <div class="card my-3">

        <div class="card-body">

            <form action="{{route('refund.request.store')}} " method="post" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="col-md-6 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Facture<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="invoice_id"
                            id="invoice_id" required>
                            <option>Sélectionner une facture</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->id }}">{{$invoice->code}} ({{ $invoice->order ? $invoice->order->code : ($invoice->contrat ? 'Contrat: '.$invoice->contrat->name : $invoice->code) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Raison de la demande<span
                            style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="refund_reason_id"
                            id="refund_reason_id" required>
                            <option>Sélectionner une raison</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="simpleinput" class="form-label">Montant<span style="color:red;">*</span></label>
                        <input type="number" name="montant" id="montant" readonly class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Pièce jointe<span
                                style="color:red;">*</span></label>
                        <input type="file" id="example-fileinput" required name="attachement" class="form-control">

                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-group">
                    <label for="simpleinput" class="form-label">Description</label>
                    <textarea name="note" id="" rows="6" class="form-control"></textarea>
                    </div>
                </div>
        </div>

        <div class="modal-footer">
            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Demander un remboursement</button>
        </div>


        </form>
    </div>
</div>

</div>
@endsection


@push('extra-js')
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
</script> --}}
<script>
    $('#invoice_id').on('change', function(){
        $.ajax({
                type: "GET",
                url: "/invoices/getInvoice/" + this.value,

                success: function(data) {
                    console.log(data);
                    $('#montant').val(data.total)
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
    })
</script>
@endpush
