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
                d.cas_status = $('#cas_status').val()
                d.contenu = $('#contenu').val()
                d.dateBegin = $('#dateBegin').val()
                d.dateEnd = $('#dateEnd').val()

            }
        },
        columns: [
           
            {
                data: 'created_at',
                name: 'created_at'
            },
             {
                data: 'demande',
                name: 'demande',
            },
            {
                data: 'patient',
                name: 'patient'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'remise',
                name: 'remise'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action'
            }
        ],
        order: [

        ],
    });

     // Recherche selon les cas
     $("#cas_status").on("change", function() {
        // alert(this.value)
        table.draw();
    });

    $('#contenu').on("input", function(){
        table.draw();
    });

    $('#dateEnd').on('input', function() {
        console.log($('#dateEnd').val());
        table.draw();
        //console.log(search.value);
    });

    $('#dateBegin').on('input', function() {
        console.log($('#dateBegin').val());;
        table.draw();
        //console.log(search.value);
    });
});