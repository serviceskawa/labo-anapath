$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    startDate: '-3d'
});
// SUPPRESSION
function deleteModal(id) {

    Swal.fire({
        title: "Voulez-vous supprimer l'élément ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function(result) {
        if (result.value) {
            window.location.href = baseUrl + "/contrats_details/delete/" + id;
            Swal.fire(
                "Suppression !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}

//EDITION
function edit(id) {
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/getcontratdetails/" + e_id,
        success: function(data) {

            $('#category_test_id2').val(data.category_test_id).change();
            $('#pourcentage2').val(data.pourcentage);
            $('#contrat_id2').val(data.contrat_id);
            $('#contrat_details_id2').val(data.id);

            console.log(data);
            $('#editModal').modal('show');
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}

$(document).ready(function() {

    // Create doctor
    $('#doctor_id').select2({
        placeholder: 'Sélectionner le médecin traitant',
        // theme: 'bootstrap4',
        tags: true,
    }).on('select2:close', function() {
        var element = $(this);
        var new_category = $.trim(element.val());

        if (new_category != '') {
            $.ajax({
                url: ROUTESTOREDOCTOR,
                method: "POST",
                data: {
                    "_token": TOKENSTOREDOCTOR,
                    name: new_category
                },
                success: function(data) {

                    if (data.status === "created") {
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                        element.append('<option value="' + data.id + '">' + data.name +
                            '</option>').val(new_category);
                    }
                }
            })
        }

    });

    // Create hopital
    $('#hospital_id').select2({
        placeholder: 'Sélectionner le centre hospitalier de provenance',
        // theme: 'bootstrap4',
        tags: true,
    }).on('select2:close', function() {
        var element = $(this);
        var new_category = $.trim(element.val());

        if (new_category != '') {
            $.ajax({
                url: ROUTESTOREHOSPITAL,
                method: "POST",
                data: {
                    "_token": TOKENSTOREHOSPITAL,
                    name: new_category
                },
                success: function(data) {

                    if (data.status === "created") {
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                        element.append('<option value="' + data.id + '">' + data.name +
                            '</option>').val(new_category);
                    }
                }
            })
        }

    });

    $('#createPatientForm').on('submit', function(e) {
        e.preventDefault();
        let code = $('#code').val();
        let lastname = $('#lastname').val();
        let firstname = $('#firstname').val();
        let age = $('#age').val();
        let year_or_month = $('#year_or_month').val();
        let telephone1 = $('#telephone1').val();
        let genre = $('#genre').val();
        let langue = $('#langue').val();
        // alert(firstname);
        $.ajax({
            url: ROUTESTOREPATIENT,
            type: "POST",
            data: {
                "_token": TOKENSTOREPATIENT,
                code: code,
                lastname: lastname,
                firstname: firstname,
                age: age,
                year_or_month: year_or_month,
                telephone1: telephone1,
                genre: genre,
                langue: langue,
            },
            success: function(data) {

                $('#createPatientForm').trigger("reset")
                $('#standard-modal').modal('hide');
                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                $('#patient_id').append('<option value="' + data.id + '">' + data.code +
                        ' - ' + data.firstname + ' ' + data.lastname + '</option>')
                    .trigger('change').val(data.id);

            },
            error: function(data) {
                console.log(data)
            },
            // processData: false,
        });

    });
});


$(document).ready(function() {

    $('#type_examen').on('change', function(e) {
        var typeExamenOption = $('#type_examen option:selected').text();

        // if (typeExamenOption == "Immuno Externe") {

        //     $(".examenReferenceInputInterne").hide();
        //     $(".examenReferenceInputExterne").show();

        //     // Désactiver le champ "Examen de Référence Interne"
        //     $("#examen_reference_select").prop("disabled", true);
        //     // Réinitialiser le champ "Examen de Référence Interne"
        //     $("#examen_reference_select").val('');

        // } else if (typeExamenOption == "Immuno Interne") {

        //     $(".examenReferenceInputExterne").hide();
        //     $(".examenReferenceInputInterne").show();

        //     // Désactiver le champ "Examen de Référence Externe"
        //     $("#examen_reference_input").prop("disabled", true);
        //     // Réinitialiser le champ "Examen de Référence Externe"
        //     $("#examen_reference_input").val('');

        // } else {
        //     $(".examenReferenceInputExterne").hide();
        //     $(".examenReferenceInputInterne").hide();

        //     // Activer les deux champs au cas où ils ont été désactivés
        //     $("#examen_reference_select").prop("disabled", false);
        //     $("#examen_reference_input").prop("disabled", false);
        //     // Réinitialiser les deux champs
        //     $("#examen_reference_select").val('');
        //     $("#examen_reference_input").val('');
        // }
        // Vérifier quelle option a été sélectionnée
        if (typeExamenOption == "Immuno Externe") {
            // Si "Immuno Externe" est sélectionné, masquer le champ "Examen de Référence Interne" et afficher le champ "Examen de Référence Externe"
            $(".examenReferenceInputInterne").hide();
            $(".examenReferenceInputExterne").show();
            // Désactiver le champ "Examen de Référence Interne"
            $("#examen_reference_select").prop("disabled", true);
            // Réinitialiser le champ "Examen de Référence Interne"
            $("#examen_reference_select").val('');

            $("#examen_reference_input").prop("disabled", false);

        } else if (typeExamenOption == "Immuno Interne") {
            // Si "Immuno Interne" est sélectionné, masquer le champ "Examen de Référence Externe" et afficher le champ "Examen de Référence Interne"
            $(".examenReferenceInputExterne").hide();
            $(".examenReferenceInputInterne").show();
            // Désactiver le champ "Examen de Référence Externe"
            $("#examen_reference_input").prop("disabled", true);
            // Réinitialiser le champ "Examen de Référence Externe"
            $("#examen_reference_input").val('');

            $("#examen_reference_select").prop("disabled", false);

        } else {
            // Si aucune des options spécifiques n'est sélectionnée, cacher les deux champs
            $(".examenReferenceInputExterne").hide();
            $(".examenReferenceInputInterne").hide();
            // Activer les deux champs au cas où ils ont été désactivés
            $("#examen_reference_select").prop("disabled", false);
            $("#examen_reference_input").prop("disabled", false);
            // Réinitialiser les deux champs
            $("#examen_reference_select").val('');
            $("#examen_reference_input").val('');
        }

    });
});