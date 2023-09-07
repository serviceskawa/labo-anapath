
            <div id="standard-modal-{{$ticket->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="standard-modalLabel">Ajouter un bon de caisse</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <div class="modal-body">
                <form method="POST" action="{{ route('cashbox.ticket_detail.store',)}}"  autocomplete="off">

                    @csrf
                    <div class="modal-body">
                        {{-- <input type="hidden" name="prestation_order_id" id="prestation_order_id" class="form-control"> --}}
                        <input type="hidden" name="cashbox_ticket_id" value={{$ticket->id}} id="cashbox_ticket_id" class="form-control">

                        {{-- <div id="index"></div> --}}
                        <div class="mb-3 ">

                            <label for="example-select" class="form-label">Articles</label>
                            <input type="text" name="item_name" class="form-control">
                            {{-- <select class="form-select select2" id="prestation_id" data-toggle="select" name="prestation_id" onchange="getprestation()" required>
                                <option value="">Tous les articles</option>
                                @foreach ($articles as $article)
                                    <option value="{{ $article->id }}"> {{ $article->name }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <div id="template">

                            <div class="row">


                                <div class="mb-3 col-6">
                                    <label for="simpleinput" class="form-label">Prix</label>
                                    <input type="number" name="unit_price" id="unit_price" class="form-control" required>
                                    {{-- <input type="text" name="price" id="price" class="form-control" required readonly> --}}
                                </div>

                                <div class="mb-3 col-6">
                                    <label for="simpleinput" class="form-label">Quantité</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" required >
                                    {{-- <input type="text" name="mont" id="mont" class="form-control" required readonly> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>




                        </div>
                        {{-- <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div> --}}
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

