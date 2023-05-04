/* DATATABLE */
$(document).ready(function() {

    $('#datatable1').DataTable({
        "order": [
            [0, "desc"]
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

function getuser(id) {
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/log/user/" + e_id,
        success: function(data) {
            console.log(data);
          $('#by').val(data.lastname +" "+ data.firstname);
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}