$(document).ready(function() {
    "use strict";
    var tableType = $("#products-datatable").DataTable({
        "columnDefs": [{
            "targets": [0],
            "searchable": false //essai
        }],
        "bFilter": true,
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
        serverSide: false,
        ajax: {
            url: ROUTETESTORDERDATATABLE3,
            data: function(d) {
                d.typeExamenId = $('#typeExamenId').val()
            }
        },
        columns: [{
                orderable: false,
                data: 'created',
                name: 'created',
                targets: 0,
                render: function(e, l, a, o) {
                    return (
                        "display" === l &&
                        (e =
                            '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"><label class="form-check-label">&nbsp;</label></div>'),
                        e
                    );
                },
                checkboxes: {
                    selectRow: true,
                    selectAllRender: '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"><label class="form-check-label">&nbsp;</label></div>',
                },
            },
            {
                orderable: true,
                data: 'dateLim',
                name: 'dateLim',
            },
            {
                orderable: true,
                data: 'code',
                name: 'code'
            },
            {
                orderable: true,
                data: 'state',
                name: 'state',
            },
        ],

        select: {
            style: "multi",
            selector: "td:first-child",
        },
        order: [
            [1, "desc"]
        ],
    });


    $("#typeExamenId").on("change", function() {
        alert($('#typeExamenId').val());
        tableType.draw();
    });


    $.ajax({
        url: baseUrl + "/macro/countData",
        type: "GET",
        data: {

        },
        success: function(response) {
            document.getElementById('count_data').textContent = "[" + response + "]"
        },
        error: function(response) {
            console.log(response)
        },
    })


    // // Écoutez l'événement draw.dt, qui est déclenché chaque fois que la table est redessinée
    tableType.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
            // Vérifiez si des lignes sont sélectionnées
            var selectedRows = tableType.rows('.selected').data().length > 0;

            // Affichez ou masquez le bouton en fonction de la présence de lignes sélectionnées
            if (selectedRows) {
                $('#deleteSelectedRows').show();
            } else {
                $('#deleteSelectedRows').hide();
            }

        }
    });

    tableType.on('deselect', function() {
        // Vérifiez si des lignes sont sélectionnées
        var selectedRows = tableType.rows('.selected').data().length > 0;

        // Affichez ou masquez le bouton en fonction de la présence de lignes sélectionnées
        if (selectedRows) {
            $('#deleteSelectedRows').show();
        } else {
            $('#deleteSelectedRows').hide();
        }
    });

    // Écoutez l'événement de clic sur le bouton pour effectuer l'action souhaitée
    $("#deleteSelectedRows").on("click", function() {
        var selectedData = tableType.rows('.selected').data().toArray();
        Swal.fire({
            title: "Confirmation : Étape [MACROSCOPIE] effectuée pour les demandes sélectionnées ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Confirmer ",
            cancelButtonText: "Annuler !",
        }).then(function(result) {
            if (result.value) {

                selectedData.forEach(function(rowData) {

                    var code = rowData.code;
                    var date = rowData.date;

                    // Extraction du laborantin
                    var selectedEmployeeHTML = rowData.state;
                    var selectId = $(selectedEmployeeHTML).attr('id');
                    var selectedEmployeeId = $('#' + selectId).val();
                    console.log('rowData', rowData);
                    console.log('date', date);
                    console.log('code', code);
                    console.log('selectedEmployeeId', selectedEmployeeId);

                    if (!selectedEmployeeId) {
                        toastr.error("vous devez sélectionné le laboration", 'Laborantin');
                    } else {
                        $.ajax({
                            url: baseUrl + "/macro/one-create",
                            type: "POST",
                            data: {
                                "_token": TOKENSTOREDOCTOR,
                                id: null,
                                code: rowData.code,
                                id_employee: selectedEmployeeId,
                            },
                            success: function(response) {
                                $('#datatable1').DataTable().ajax.reload();
                                $('#datatable2').DataTable().ajax.reload();
                                $('#products-datatable').DataTable().ajax.reload();
                                $.ajax({
                                    url: baseUrl + "/macro/countData",
                                    type: "GET",
                                    data: {

                                    },
                                    success: function(response) {
                                        document.getElementById('count_data').textContent = "[" + response + "]"
                                    },
                                    error: function(response) {
                                        console.log(response)
                                    },
                                })
                            },
                            error: function(response) {
                                console.log(response)
                            },
                        })
                    }

                });



            }
        });

        // Décochez toutes les lignes après l'action
        tableType.rows().deselect();
    });

});