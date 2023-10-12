<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-edit-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Détail bon de caisse</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <h4>Code : {{$ticket->code}}</h4>
               <h4> Catégorie de la dépense :  {{ $ticket->categorie ? $ticket->categorie->name :'' }}</h4>
               <h4> Fournisseur :  {{ $ticket->supplier ? $ticket->supplier->name:'' }}</h4>
                <h4>Montant total : {{ $ticket->amount }}</h4>
                 {{-- Debut --}}
                 <h5 class="mt-3">Listes des articles</h5>
                 <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Article</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($ticket->details()->get() as $key => $article)
                                <tr>
                                    <td>{{ ++$key}}</td>
                                    <td>{{ $article->item_name }}</td>
                                    <td>{{ $article->unit_price }}</td>
                                    <td>{{ $article->quantity }}</td>
                                    <td>{{ $article->line_amount }} </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- Fin --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
