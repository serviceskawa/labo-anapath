<style>
     @media print {
            .no-print {
                display: none;
            }
        }
</style>
<!-- Large modal -->
<div class="modal fade section1" id="bs-example-modal-lg-edit-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Affectation des comptes rendu</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <h4>Doctor : {{ $doctor->firstname }} {{ $doctor->lastname }}</h4>
                 {{-- Debut --}}
                 <h5 class="mt-3">Listes des comptes rendu</h5>
                 <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Compte rendu (demande d'examen)</th>
                                <th>Commentaire</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($doctor->assignment as $key => $assignment)
                                <tr>
                                    <td>{{ $key+1}}</td>
                                    <td>{{ $assignment->report->code }} ({{ $assignment->report->order->code }})</td>
                                    <td>{{ $assignment->comment }}</td>
                                    <td>{{ $assignment->created_at->format('d-m-Y (H:i)') }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- Fin --}}
                {{-- <div class="modal-footer no-print">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" id="print-button" class="btn btn-primary">Imprimer</button>
                    <button type="button" id="mail-button" class="btn btn-warning">Envoyer un mail</button>
                </div> --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
