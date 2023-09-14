<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-edit-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Détail {{ $item->bank ? 'approvisionnement' : 'dépense' }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <h4>Date : {{ $item->date }}</h4>
                <h4>Montant : {{ $item->amount }}</h4>
                <h4>Bank : {{ $item->bank ? $item->bank->name:'' }}</h4>
                <h4>Numéro de chèque : {{ $item->cheque_number }}</h4>
                <h4>Pièce jointe :
                    @if ($item->attachment)
                    <a href="{{ asset('storage/' . $filename) }}" download>
                        <u style="font-size: 15px;">Voir</u>
                    </a>
                    @endif
               <h4> {{ $item->bank ? ' Approvisioné par :' : 'Dépense approuvé par :'}}  {{ $item->user->firstname }} {{ $item->user->lastname }}</h4>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
