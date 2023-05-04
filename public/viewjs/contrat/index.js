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
            window.location.href = "{{ url('contrats/delete') }}" + "/" + id;
            Swal.fire(
                "Suppression !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}

function closeModal(id) {

    Swal.fire({
        title: "Voulez-vous clôturer l'élément ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function(result) {
        if (result.value) {
            window.location.href = "{{ url('contrats/close') }}" + "/" + id;
            Swal.fire(
                "Clôture !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}


/* DATATABLE */
$(document).ready(function() {

    $('#datatable1').DataTable({
        "order": [
            [0, "asc"]
        ],
        "columnDefs": [{
            "targets": [0],
            "searchable": false
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
    // var baseUrl = "{{ url('/') }}";

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/getcontrat/" + e_id,
        success: function(data) {

            $('#id2').val(data.id);
            $('#name2').val(data.name);
            $('#type2').val(data.type).change();
            $('#status2').val(data.status).change();
            $('#description2').val(data.description);
            $('#nbr_examen').val(data.nbr_tests);

            $('#editModal').modal('show');
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}