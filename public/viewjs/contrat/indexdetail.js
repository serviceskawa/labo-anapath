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
