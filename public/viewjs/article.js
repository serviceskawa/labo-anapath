// SUPPRESSION




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
        initComplete: function () {
            this.api().columns([1]).every(function () {
                var column = this;
                var title = this.header();
                var select = $(
                    '<select class="form-control form-control-sm text-bold" ></select>'
                    )
                var select = $('#qt')
                    .appendTo($(column.header()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search(val)
                            .draw();
                    });

                    select.append('<option value="">Qté en stock</option>');
                    select.append('<option value="atteint">Seuil d\'alerte atteint</option>');
                    select.append('<option value="rupture">Rupture de stock</option>');
            });
        },
    });
});
