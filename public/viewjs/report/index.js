// SUPPRESSION
function deleteModal(id) {

    Swal.fire({
        title: "Voulez-vous supprimer l'élément ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function (result) {
        if (result.value) {
            window.location.href = baseUrl + "/contrats/delete/" + id;
            Swal.fire(
                "Suppression !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}

$(document).ready(function () {
    var table = $('#datatable1').DataTable({
        "order": [],
        // language: {
        //     url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json",
        // },
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
            this.api().columns([0, 1, 2, 3, 4, 5]).every(function () {
                var column = this;
                var title = this.header();
                title = $(title).html().replace(/[\W]/g, ' ');
                console.log(title);
                var select = $(
                    '<select class="form-control form-control-sm text-bold" ><option value="">' +
                    title + '</option></select>')
                    .appendTo($(column.header()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    select.append('<option value="' + d + '">' + d +
                        '</option>')
                });
            });
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: ROUTEGETDATATABLE,
            data: function (d) {
                d.statusquery = $('#statusquery').val()
                d.contenu = $('#contenu').val()
                d.dateBegin = $('#dateBegin').val()
                d.dateEnd = $('#dateEnd').val()
            }
        },
        columns: [
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'codepatient',
                name: 'codepatient'
            },
            {
                data: 'patient',
                name: 'patient',
            },
            {
                data: 'telephone',
                name: 'telephone'
            }, {
                data: 'created_at',
                name: 'created_at'
            }, {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
            },
        ],
        order: [
            [0, 'asc']
        ],

    });
     // Recherche selon les cas
     $("#statusquery").on("change", function() {
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


//EDITION
function edit(id) {
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/getcontrat/" + e_id,
        success: function (data) {

            $('#id2').val(data.id);
            $('#name2').val(data.name);
            $('#type2').val(data.type).change();
            $('#status2').val(data.status).change();
            $('#description2').val(data.description);

            console.log(data);
            $('#editModal').modal('show');
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}