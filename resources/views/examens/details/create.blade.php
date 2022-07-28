<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un hôpital</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
        <form action="{{ route('details_test_order.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="modal-body">
            

                <input type="hidden" name="test_order_id" value="{{ $test_order->id }}" class="form-control" >
           
                <div class="mb-3">
                    <label for="example-select" class="form-label">Test</label>
                    <select class="form-select" id="test_id" name="test_id" required onchange="getTest()" >
                        <option>...</option>
                        @foreach ($tests as $test)
                        <option value="{{ $test->id }}">{{ $test->name }}</option>
                        @endforeach
             
                      
                    </select>
                </div>


                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Prix</label>
                    <input type="text" name="price" id="price" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="simpleinput" class="form-label">Remise</label>
                    <input type="text" name="remise" class="form-control" >
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->