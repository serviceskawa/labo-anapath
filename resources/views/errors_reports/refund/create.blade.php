@extends('layouts.app2')

@section('title', 'Remboursements')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3 mb-1">
                <a href="{{ route('refund.request.index') }}" type="button" class="btn btn-primary"> <i class="dripicons-reply"></i> Retour</a>
            </div>
            <h4 class="page-title">Ajouter une demande de remboursements</h4>
        </div>
    </div>
</div>

<div class="">

    @include('layouts.alerts')

    <div class="card my-3">

        <div class="card-body">

            <form action="{{route('refund.request.store')}} " method="post" id="addRefundRequest" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="col-md-4 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Raison de la demande<span
                            style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="refund_reason_id"
                            id="refund_reason_id">
                            <option>Sélectionner une raison</option>
                            @foreach ($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Facture de référence<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="invoice_id"
                            id="invoice_id">
                            <option>Sélectionner la facture</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->id }}">{{$invoice->code}} ({{ $invoice->order ? $invoice->order->code : ($invoice->contrat ? 'Contrat: '.$invoice->contrat->name : $invoice->code) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="simpleinput" class="form-label">Montant<span style="color:red;">*</span></label>
                        <input type="number" name="montant" id="montant" class="form-control" required>
                    </div>


                </div>
                <div class="mb-3">
                    <div class="form-group">
                    <label for="simpleinput" class="form-label">Description</label>
                    <textarea name="note" id="description" rows="6" class="form-control"></textarea>
                    </div>
                </div>
        </div>

        <div class="modal-footer">
            <button type="reset" id="resetbutton" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" id="submitbutton" class="btn btn-primary">Demander un remboursement</button>
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

$(document).ready(function() {
    $('#submitbutton').on('click', function(e) {
        var reason = $('#refund_reason_id').val()
        var invoice = $('#invoice_id').val()
        if (reason =='Sélectionner une raison') {
            toastr.error("Sélectionner une raison", 'Raison');
            event.preventDefault(); // Empêche la soumission du formulaire
        }
        if (invoice =='Sélectionner la facture') {
            toastr.error("Sélectionner la facture de référence", 'Facture');
            event.preventDefault(); // Empêche la soumission du formulaire
        }
    });
})

    $('#invoice_id').on('change', function(){
        var note = "Une demande de remboursement pour la facture "
        $.ajax({
                type: "GET",
                url: "/invoices/getInvoice/" + this.value,

                success: function(data) {
                    $('#montant').val(data.total)
                    $('#description').val(note+data.code)
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
    })
    $('#montant').on('input', function() {
        var notePlus = "pour un motant de "
        var descript = $('#description').val()
        var id = $('#invoice_id').val()
            if (id !="Sélectionner une facture") {
                $.ajax({
                    type: "GET",
                    url: "/invoices/getInvoice/" + id,

                    success: function(data) {
                        if ($('#montant').val() > data.total) {
                            toastr.error("Le montant saisi est supérieur total de la facture", 'Montant saisi');
                        }else{
                            // $('#description').val(descript+' '+$('#montant').val())
                        }

                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }else{
                toastr.error("Veillez sellectionné une facture", 'Facture');
            }
    })
</script>
@endpush
