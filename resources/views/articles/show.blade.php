<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-show-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Détail</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">


                {{-- Debut --}}
                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Quantité</th>
                                <th>Date</th>
                                <th>Quantité</th>
                                <th>Fait par</th>
                                <th>Description</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($movs as $mov)
                            @if($mov->article_id == $item->id)

                            <tr>
                                <td>{{ $mov->movement_type }}</td>
                                <td>{{ $mov->quantite_changed }}</td>
                                <td>{{ $mov->date_mouvement }}</td>
                                <td>{{ $mov->quantite_changed }}</td>
                                <td>{{ $mov->user->firstname }} {{ $mov->user->firstname }}</td>
                                <td>{{ $mov->description }}</td>
                            </tr>

                            @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- Fin --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->