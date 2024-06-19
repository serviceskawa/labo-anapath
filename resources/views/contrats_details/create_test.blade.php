<div id="modal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un examen au contrat [<span
                        style="text-transform: uppercase;">{{ $contrat->name }}</span>]
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('contrat_details.store_test') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Examens<span style="color:red;">*</span></label>
                        <select class="form-select" id="example-select" name="test_id" required>
                            <option value="">Sélectionner l'examen</option>
                            @foreach ($tests as $test)
                            <option value="{{ $test->id }}">{{ $test->name }} ({{
                                $test->price }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prix<span style="color:red;">*</span></label>
                        <input type="number" name="price" id="price" class="form-control" readonly required>
                    </div> --}}

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Réduction<span style="color:red;">*</span></label>
                        <input type="number" name="amount_remise" id="amount_remise" class="form-control" required>
                    </div>

                    {{-- <div class="mb-3">
                        <label for="simpleinput" class="form-label">Montant après remise<span
                                style="color:red;">*</span></label>
                        <input type="number" name="amount_after_remise" id="amount_after_remise" class="form-control"
                            readonly required>
                    </div> --}}

                    <input type="hidden" name="contrat_id" value="{{ $contrat->id }}" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau examen</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const selectExam = document.getElementById('example-select');
        const priceInput = document.getElementById('price');
        const amountRemiseInput = document.getElementById('amount_remise');
        const amountAfterRemiseInput = document.getElementById('amount_after_remise');

        // Fonction pour mettre à jour le prix selon l'examen sélectionné
        function updatePrice() {
            const selectedOption = selectExam.options[selectExam.selectedIndex];
            const examPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            priceInput.value = examPrice.toFixed(2);  // Met à jour le champ "Prix" avec la valeur de l'examen sélectionné
            calculateAmountAfterRemise();  // Récalcule le montant après remise à chaque changement
            console.log(selectedOption.value);

        }

        // Fonction pour calculer le montant après remise
        function calculateAmountAfterRemise() {
            const examPrice = parseFloat(priceInput.value) || 0;
            const amountRemise = parseFloat(amountRemiseInput.value) || 0;
            const amountAfterRemise = examPrice - amountRemise;

            amountAfterRemiseInput.value = amountAfterRemise.toFixed(2);  // Met à jour le champ "Montant après remise"
        }

        // Écoute le changement de sélection dans le select
        selectExam.addEventListener('change', updatePrice);

        // Écoute le changement dans le champ de remise
        amountRemiseInput.addEventListener('input', calculateAmountAfterRemise);
    });
</script>

</script>
@endpush

















{{-- <div id="modal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter une nouvelle catégorie d'examen <br>pris en
                    compte par le contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('contrat_details.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Examens<span style="color:red;">*</span></label>
                        <select class="form-select" id="example-select" name="test_id" required>
                            <option value="">Sélectionner l'examen</option>
                            @foreach ($tests as $test)
                            <option value="{{ $test->id }}" data-price="{{ $test->price }}">{{ $test->name }}({{
                                $test->price }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Montant remise<span
                                style="color:red;">*</span></label>
                        <input type="number" name="amount_remise" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Montant après remise<span
                                style="color:red;">*</span></label>
                        <input type="number" name="amount_after_remise" class="form-control" readonly required>
                    </div>

                    <input type="hidden" name="contrat_id" value="{{ $contrat->id }}" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter une nouvelle catégorie d'examen</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
    const selectExam = document.getElementById('example-select');
    const amountRemiseInput = document.querySelector('input[name="amount_remise"]');
    const amountAfterRemiseInput = document.querySelector('input[name="amount_after_remise"]');

    function calculateAmountAfterRemise() {
        const selectedExamOption = selectExam.options[selectExam.selectedIndex];
        // const examPrice = selectedExamOption.getAttribute('data-price') ? parseFloat(selectedExamOption.getAttribute('data-price')) : 0;
        const examPrice = parseFloat(selectedExamOption.getAttribute('data-price')) || 0;
        const amountRemise = parseFloat(amountRemiseInput.value) || 0;
        const amountAfterRemise = examPrice - amountRemise;
console.log(examPrice, amountRemise);
        amountAfterRemiseInput.value = amountAfterRemise.toFixed(2);
    }

    selectExam.addEventListener('change', calculateAmountAfterRemise);
    amountRemiseInput.addEventListener('input', calculateAmountAfterRemise);
});

</script>
@endpush --}}


{{-- <div id="modal3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter une nouvelle catégorie d'examen <br>pris en
                    compte par le contrat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ route('contrat_details.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Examens<span style="color:red;">*</span></label>
                        <select class="form-select" id="example-select" name="test_id" required>
                            <option value="">Sélectionner l'examen</option>
                            @foreach ($tests as $test)
                            <option value="{{ $test->id }}">{{ $test->name }}({{ $test->price }})</option>
                            @endforeach


                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Montant remise<span
                                style="color:red;">*</span></label>
                        <input type="number" name="amount_remise" class="form-control" required>
                    </div>


                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Montant après remise<span
                                style="color:red;">*</span></label>
                        <input type="number" name="amount_after_remise" class="form-control" readonly required>
                    </div>

                    <input type="hidden" name="contrat_id" value="{{ $contrat->id }}" class="form-control" required>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter une nouvelle catégorie d'examen</button>
                </div>
            </form>
        </div>
    </div>
</div>



@push('extra-js')

@endpush --}}