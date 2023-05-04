<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier les informations de la demande</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
                    <form method="POST" action="{{route('test_order.updateTest')}}" autocomplete="off">
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <input type="hidden" name="test_order_id" id="test_order_id"
                                    value="{{ $test_order->id }}" class="form-control">
                                <input type="hidden" name="row_id" id="row_id" class="form-control">

                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Examen</label>
                                    <select class="form-select select2" id="test_id1"
                                        name="test_id1" required onchange="getTestmodal()">
                                        <option>Sélectionner l'examen</option>
                                        @foreach ($tests as $test)
                                            <option data-category_test_id="{{ $test->category_test_id }}"
                                                value="{{ $test->id }}">{{ $test->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">

                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Prix</label>
                                    <input type="text" name="price" id="price1" class="form-control" required
                                        readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Remise</label>
                                    <input type="text" name="remise1" id="remise1" class="form-control" required
                                        readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="mb-3">
                                    <label for="example-select" class="form-label">Total</label>

                                    <input type="text" name="total1" id="total1" class="form-control" required
                                        readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-warning" id="add_detail">Mettre à jour</button>
                                </div>
                            </div>
                        </div>
                    </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('extra-js')

   <script>
     var ROUTEGETREMISE = "{{ route('examens.getTestAndRemise') }}"
     var TOKENGETREMISE = "{{ csrf_token() }}"
   </script>
@endpush
