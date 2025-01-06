// function deleteModal(id) {
//     Swal.fire({
//         title: "Voulez-vous supprimer l'examen du contrat ?",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonText: "Oui ",
//         cancelButtonText: "Non !",
//     }).then(function (result) {
//         if (result.value) {
//             window.location.href = baseUrl + "/contrat/delete_detail/" + id;
//             Swal.fire("Clôture !", "En cours de traitement ...", "success");
//         }
//     });
// }

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
            Swal.fire("Suppression !", "En cours de traitement ...", "success");
        }
    });
}

function edit(id) {
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/getcontratdetails/" + e_id,
        success: function (data) {
            $("#category_test_id2").val(data.category_test_id).change();
            $("#pourcentage2").val(data.pourcentage);
            $("#contrat_id2").val(data.contrat_id);
            $("#contrat_details_id2").val(data.id);

            console.log(data);
            $("#editModal").modal("show");
        },
        error: function (data) {
            console.log("Error:", data);
        },
    });
}

function closeModal(id) {
    Swal.fire({
        title: "Voulez-vous clôturer le contrat ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function (result) {
        if (result.value) {
            window.location.href = baseUrl + "/contrats/close/" + id;
            Swal.fire("Clôture !", "En cours de traitement ...", "success");
        }
    });
}

function activeContratModal(id) {
    Swal.fire({
        title: "Voulez-vous activer le contrat ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function (result) {
        if (result.value) {
            window.location.href = baseUrl + "/updatecontratstatus/" + id;
            Swal.fire("Clôture !", "En cours de traitement ...", "success");
        }
    });
}
