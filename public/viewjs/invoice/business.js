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