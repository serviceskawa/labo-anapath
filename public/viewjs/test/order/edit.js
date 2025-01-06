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
    }).then(function (result) {
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
        success: function (data) {

            $('#category_test_id2').val(data.category_test_id).change();
            $('#pourcentage2').val(data.pourcentage);
            $('#contrat_id2').val(data.contrat_id);
            $('#contrat_details_id2').val(data.id);



            console.log(data);
            $('#editModal').modal('show');
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

$(document).ready(function () {

    $('#doctor_id').select2({
        placeholder: 'Select Category',
        theme: 'bootstrap4',
        tags: true,
    }).on('select2:close', function () {
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
                success: function (data) {

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
        placeholder: 'Choisissez un hopital',
        theme: 'bootstrap4',
        tags: true,
    }).on('select2:close', function () {
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
                success: function (data) {

                    if (data.status === "created") {
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                        element.append('<option value="' + data.id + '">' + data.name +
                            '</option>').val(new_category);
                    }
                }
            })
        }

    });

    $('#createPatientForm').on('submit', function (e) {
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
                langue: langue
            },
            success: function (data) {

                $('#createPatientForm').trigger("reset")
                $('#standard-modal').modal('hide');
                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                $('#patient_id').append('<option value="' + data.id + '">' + data.code +
                    ' - ' + data.firstname + ' ' + data.lastname + '</option>')
                    .trigger('change').val(data.id);

            },
            error: function (data) {
                console.log(data)
            },
            // processData: false,
        });

    });
});

$(document).ready(function () {

    $('#type_examen').on('change', function (e) {
        var typeExamenOption = $('#type_examen option:selected').text();

        const types = ["Immuno Externe", "Immuno Interne", "Apple", "Mango"];
        console.log(types.includes("Mango"));
        if (typeExamenOption == "Immuno Externe") {
            $(".examenReferenceSelect").hide();
            $(".examenReferenceInput").show();

        } else if (typeExamenOption == "Immuno Interne") {
            $(".examenReferenceSelect").hide();
            $(".examenReferenceInput").show();

        } else {
            $(".examenReferenceInput").hide();
            $(".examenReferenceSelect").hide();
        }
    });

    // Pour le chargement des données
    var typeExamenOption = $('#type_examen option:selected').text();

    const string1 = typeExamenOption;
    const string2 = 'linux';
    console.log(typeExamenOption);
    console.log(string1 === string2);
    if (typeExamenOption == 'Immuno Externe') {
        $(".examenReferenceSelect").hide();
        $(".examenReferenceInput").show();

    }
    if (typeExamenOption == 'Immuno Interne') {
        $(".examenReferenceSelect").hide();
        $(".examenReferenceInput").show();
        console.log('a')

    } else {
        $(".examenReferenceInput").hide();
        $(".examenReferenceSelect").hide();
    }
});
