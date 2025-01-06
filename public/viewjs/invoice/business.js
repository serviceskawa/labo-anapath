/* DATATABLE */
$(document).ready(function() {
    var table = $('#datatable1').DataTable({

        "columnDefs": [{
            "targets": [0],
            "searchable": false
        }],
        "bFilter": false,
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
        processing: true,
        serverSide: true,
        ajax: {
            url: ROUTEGETDATATABLE,
            data: function(d) {
                // d.periode = $('#periode').val()

            }
        },
        columns: [

            {
                data: 'created_at',
                name: 'created_at'
            },
             {
                data: 'factures',
                name: 'factures',
            },
            {
                data: 'avoirs',
                name: 'avoirs'
            },
            {
                data: 'chiffres',
                name: 'chiffres'
            },
            {
                data: 'encaissements',
                name: 'encaissements'
            }
        ],
        order: [

        ],
    });
});

$('#addDetailForm').on('submit', function(e) {
    e.preventDefault();
    let starting_date = $('#starting_date').val();
    let ending_date = $('#ending_date').val();

    $.ajax({
        url: ROUTESEARCHINVOICE,
        type: "POST",
        data: {
            "_token": TOKENSEARCHINVOICE,
            starting_date: starting_date,
            ending_date: ending_date,

        },
        success: function(response) {
            // $('#addDetailForm').trigger("reset")

            if (response) {
                console.log(response);
                var searchResponse = document.getElementById('searchResponse');
                searchResponse.style.display = 'block';
               var facture = document.getElementById('facture');
               var avoir = document.getElementById('avoir');
               var encaissement = document.getElementById('encaissement');
               facture.setHTML(response.facture);
               avoir.setHTML(response.avoir);
               ca.setHTML(response.ca);
               encaissement.setHTML(response.encaissement);
            }
        },
        error: function(response) {
            console.log(response)
        },
    });

});
