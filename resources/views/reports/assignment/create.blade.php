@extends('layouts.app2')

@section('title', 'Reports')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            {{-- <div class="page-title-right mr-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#standard-modal">Affecter des comptes rendu</button>
            </div> --}}
            <h4 class="page-title">Affectation de comptes rendu au docteur : {{ $doctor->firstname }} {{ $doctor->lastname }}</h4>
        </div>

        <div class="">
            <div class="card mb-md-0 mb-3">
                <div class="card-body">

                    <h5 class="card-title mb-3">Ajouter un compte rendu</h5>
                    <form action="{{route('report.assignment.store')}}" method="post"  >
                        @csrf
                        <input type="hidden" name="id" value="{{$doctor->id}}">
                        <div  id="dynamic">
                            <div class="row mt-3" id="dynamic-form">
                                <div class="col-4">
                                    <label for="example-select" class="form-label">Compte rendu (demande d'examen)</label>
                                    <select class="form-select select2" name="report_id[]" required>
                                        <option value="">Sélectionner un compte rendu</option>
                                        @foreach ($reports as $report)
                                            <option value="{{ $report->id }}">{{ $report->code }} ({{ $report->order->code }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label for="example-select" class="form-label">Commentaire</label>
                                    <textarea name="comment[]" class="form-control" cols="30" rows="3"></textarea>
                                </div>
                                <div class="col-2 mt-3 float-end">
                                    <button type="button" id="add-row" class="btn btn-primary">Plus</button>
                                    <button type="button" class="btn btn-danger" style="display: none">Supprimer</button>
                                </div>
                            </div>

                            <!-- Champs de menu déroulant caché (initial) pour les comptes rendus -->
                            {{-- <div style="display: none;">
                                <select class="form-select select2" data-toggle="select2" name="report_id[]">
                                    <option value="">Sélectionner un compte rendu</option>
                                    @foreach ($reports as $report)
                                        <option value="{{ $report->id }}">{{ $report->code }} ({{ $report->order->code }})</option>
                                    @endforeach
                                </select>
                            </div> --}}

                        </div>

                        <div class="float-end mt-4">
                            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary" style="margin-left: 10px">Affecter</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('extra-js')
<script type="text/javascript">
    $(document).ready(function () {
        function addNewRow() {
             // Masquez le bouton "Plus" de l'ancienne ligne
             // Clonez la dernière ligne du formulaire
             $("#dynamic-form .btn-primary").hide();
            var newRow = $("#dynamic-form").clone();

            // Effacez les valeurs des champs
            newRow.find('input[type=text], textarea').val('');

            // Réinitialisez le menu déroulant pour la nouvelle ligne
            newRow.find('select').val('');

            // Affichez le bouton "Supprimer" pour la nouvelle ligne
            newRow.find('.btn-danger').show();
            // Affichez le bouton "Supprimer" pour la nouvelle ligne
            newRow.find('.btn-primary').show();

            // Ajoutez la nouvelle ligne au formulaire
            newRow.appendTo("#dynamic");

            // Attachez un gestionnaire d'événements pour le bouton "Plus" de la nouvelle ligne
            newRow.find('.btn-primary').click(addNewRow);
        }

        $("#add-row").click(addNewRow);

        $("#dynamic-form").on("click", ".btn-danger", function (event) {
            $(this).closest(".row").remove();
            // Affichez le bouton "Plus" de la dernière ligne restante
            $("#dynamic-form .btn-primary").show();
        });
    });
</script>

@endpush
