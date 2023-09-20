<!-- Large modal -->
<div class="modal fade" id="bs-example-show-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Détail</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <h4>Motif : {{ $item->note }}</h4>
                <h4>Montant : {{ $item->montant }}</h4>
                <h4>Dernière mis à jour :  {{ date_format($item->updated_at, 'd/m/y h:m:s') }}</h4>
                <h4>Facture : {{ $item->invoice ? $item->invoice->code : '' }}</h4>
                <h4>Pièce jointe :
                    @if ($item->attachment)
                    <a href="{{ asset('storage/' . $item->attachment) }}" download>
                        <u style="font-size: 15px;">Voir</u>
                    </a>
                    @endif
                </h4>

                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Demande de rembousement</th>
                                <th>Utilisateur</th>
                                <th>Operation</th>
                                <th>Dernière mis à jour</th>

                            </tr>
                        </thead>


                        <tbody>

                            @foreach ($item->logRefund as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->refund->code }}</td>
                                    <td>{{ $item->user->firstname }} {{ $item->user->lastname }}</td>
                                    <td>{{ $item->operation }}</td>
                                    <td>
                                        {{-- {{$item->updated_at}} --}}
                                        {{ date_format($item->updated_at, 'd/m/y h:m:s') }}
                                    </td>
                                </tr>
                            @endforeach




                        </tbody>
                    </table>

                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
