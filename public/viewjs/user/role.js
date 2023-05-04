// SUPPRESSION
function deleteRole(id) {
    Swal.fire({
        title: "Voulez-vous vraiment supprimer ce rôle ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, supprimer",
        cancelButtonText: "Annuler",
    }).then(function(result) {
        if (result.value) {
            axios.delete(baseUrl + "/roles/" + id)
                .then(function(response) {
                    Swal.fire({
                        title: "Rôle supprimé",
                        text: "Le rôle a été supprimé avec succès",
                        icon: "success"
                    }).then(function() {
                        // Recharger la page
                        location.reload();
                    });
                })
                .catch(function(error) {
                    Swal.fire({
                        title: "Erreur lors de la suppression",
                        text: "Une erreur est survenue lors de la suppression du rôle",
                        icon: "error"
                    });
                });
        }
    });
}

