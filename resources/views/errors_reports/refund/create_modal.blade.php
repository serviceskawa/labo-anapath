<!-- Large modal -->
<div class="modal fade" id="bs-example-show-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Demande {{ $item->code }}
                    @if ($item->status == 'En attente')
                    <span style="color: rgba(231, 217, 30, 0.992);" class="text-">[En attente]</span>
                    @elseif($item->status == 'Aprouvé')
                    <span style="color: green;">[Aprouvée]</span>
                    @elseif($item->status == 'Clôturé')
                    <span style="color: black;">[Clôturée]</span>
                    @elseif($item->status == 'Rejeté')
                    <span style="color: red;">[Rejetée]</span>
                    @endif
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex" ><h4 style="margin-right: 10px">Description :</h4> <span style="margin-top: 10px" >{{" ".$item->note }}</span> </div>
                <div class="d-flex" ><h4 style="margin-right: 10px">Raison :</h4> <span style="margin-top: 10px" >{{ $item->reason ? $item->reason->description : '' }}</span> </div>
                <div class="d-flex" ><h4 style="margin-right: 10px">Montant :</h4> <span style="margin-top: 10px" >{{" ".$item->montant }}</span> </div>
                <div class="d-flex" ><h4 style="margin-right: 10px">Facture référence :</h4> <span style="margin-top: 10px" >{{" ".$item->invoice ? $item->invoice->code : '' }}</span> </div>
                <div class="d-flex" >
                    <h4 style="margin-right: 10px">Pièce jointe :</h4>
                    <span style="margin-top: 10px" >
                        @if ($item->attachment)
                            <a href="{{ asset('storage/' . $item->attachment) }}" download>
                                <u style="font-size: 15px;">Voir</u>
                            </a>
                        @endif
                    </span>
                </div>
                <h5 class="card-title mb-0 mt-3">Historique des mises à jour</h5>
                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Demande de rembousement</th>
                                <th>Utilisateur</th>
                                <th>Operation</th>
                                <th>Date</th>

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
