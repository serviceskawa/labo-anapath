baseUrl + "/getcontrat/" + e_id,
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