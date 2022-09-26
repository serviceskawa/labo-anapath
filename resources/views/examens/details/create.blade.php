<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouvel examen</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('details_test_order.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <input type="hidden" name="test_order_id" value="{{ $test_order->id }}" class="form-control">

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Examen<span style="color:red;">*</span></label>
                        <select class="form-select" id="test_id" name="test_id" required onchange="getTest()">
                            <option value="">Sélectionner l'examen</option>
                            @foreach ($tests as $test)
                                <option data-category_test_id="{{ $test->category_test_id }}"
                                    value="{{ $test->id }}">{{ $test->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prix<span style="color:red;">*</span></label>
                        <input type="text" name="price" id="price" class="form-control" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Remise<span style="color:red;">*</span></label>
                        <input type="text" name="discount" id="discount" class="form-control" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Total<span style="color:red;">*</span></label>
                        <input type="text" name="total" id="total" class="form-control" required readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouvel examen</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
