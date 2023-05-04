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