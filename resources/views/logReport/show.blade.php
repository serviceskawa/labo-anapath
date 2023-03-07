<div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Modifier les informations de la demande</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('prestations_order.update') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <input type="hidden" name="id" id="id" class="form-control">

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Opération effectuée</label>
                        <input type="text" name="operation" id="operation" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Code du compte rendu</label>
                        <input type="text" name="report" id="report" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Effectué par </label>
                        <input type="text" name="by" id="by" class="form-control" readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('extra-js')

   <script>
     function getprestation() {
        var prestation_id = $('#prestation_id2').val();

        $.ajax({
            type: "POST",
            url: "{{ route('prestations_order.getPrestationOrder') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                prestationId: prestation_id,
            },
            success: function(data) {
                console.log(data.total);
                $('#total2').val(data.total);
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });

    }
   </script>
@endpush
