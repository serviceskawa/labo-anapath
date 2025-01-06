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
            window.location.href = baseUrl + "/fournisseur/delete/" + id;
            Swal.fire(
                "Suppression !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}


/* DATATABLE */
$(document).ready(function() {

    $('#datatable1').DataTable({
        "order": [ ],
        "columnDefs": [{
            "targets": [0],
            "searchable": true
        }],
        "language": {
            "lengthMenu": "Afficher _MENU_ enregistrements par page",
            "zeroRecords": "Aucun enregistrement disponible",
            "info": "Afficher page _PAGE_ sur _PAGES_",
            "infoEmpty": "Aucun enregistrement disponible",
            "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
            "sSearch": "Rechercher:",
            "paginate": {
                "previous": "Précédent",
                "next": "Suivant"
            }
        },
    });
});


//EDITION
function edit(id) {
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/fournisseur/get/" + e_id,
        success: function(data) {

            $('#id').val(data.id);
            $('#name2').val(data.name);
            $('#supplier_category_id2').val(data.supplier_category_id).change();
            $('#phone2').val(data.phone);
            $('#address2').val(data.address);
            $('#information2').val(data.information);
            $('#email2').val(data.email);

            //

            $('#editModal').modal('show');
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}
