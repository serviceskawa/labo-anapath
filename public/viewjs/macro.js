// SUPPRESSION
function deleteModal(id) {

    Swal.fire({
        title: "Voulez-vous continuez?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function(result) {
        if (result.value) {
            window.location.href = baseUrl + "/macro/delete/" + id;
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
            url: ROUTETESTORDERDATATABLE,
            data: function(d) {
                d.id_test_pathology_order = $('#id_test_pathology_order').val()
                d.id_employee = $('#id_employee').val()
                d.date = $('#date').val()

            }
        },
        columns: [

            {
                orderable: false,
                data: 'created',
                name: 'created',
                targets: 0,
                render: function (e, l, a, o) {
                    return (
                        "display" === l &&
                            (e =
                                '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"><label class="form-check-label">&nbsp;</label></div>'),
                        e
                    );
                },
                checkboxes: {
                    selectRow: true,
                    selectAllRender:
                        '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"><label class="form-check-label">&nbsp;</label></div>',
                },
            },
            {
                data: 'code',
                name: 'code'
            },
             {
                data: 'add_by',
                name: 'add_by',
            },
             {
                data: 'state',
                name: 'state',
            },
            {
                data: 'action',
                name: 'action',
            },
        ],
        select: {
            style: "multi",
            selector: "td:first-child",
        },
        order: [
            [0, 'asc']
        ],

    });
    var table1 = $('#datatable2').DataTable({

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
            url: ROUTETESTORDERDATATABLE2,
            data: function(d) {
                // d.id_test_pathology_order = $('#id_test_pathology_order').val()
                // d.id_employee = $('#id_employee').val()
                // d.date = $('#date').val()

            }
        },
        columns: [

            {
                data: 'created',
                name: 'created'
            },
            {
               data: 'dateLim',
               name: 'dateLim',
           },
            {
               data: 'date',
               name: 'date',
           },
            {
                data: 'code',
                name: 'code'
            },
             {
                data: 'state',
                name: 'state',
            },
        ],
        order: [],

    });

    table.on( 'select', function ( e, dt, type, indexes ) {
        if ( type === 'row' ) {
            // Vérifiez si des lignes sont sélectionnées
            var selectedRows = table.rows('.selected').data().length > 0;

            // Affichez ou masquez le bouton en fonction de la présence de lignes sélectionnées
            if (selectedRows) {
                $('#changeState').show();
            } else {
                $('#changeState').hide();
            }

        }
    } );
    table.on('deselect', function () {
       // Vérifiez si des lignes sont sélectionnées
       var selectedRows = table.rows('.selected').data().length > 0;

       // Affichez ou masquez le bouton en fonction de la présence de lignes sélectionnées
       if (selectedRows) {
           $('#changeState').show();
       } else {
           $('#changeState').hide();
       }
    });


     // Recherche selon les types d'examen
     $("#id_test_pathology_order").on("change", function() {
        // alert(this.value)
        table.draw();
    });
     // Recherche selon les cas
     $("#id_employee").on("change", function() {
        // alert(this.value)
        table.draw();
    });
    $('#date').on('input', function() {
        console.log($('#date').val());
        table.draw();
        //console.log(search.value);
    });

    // Écoutez l'événement de clic sur le bouton pour effectuer l'action souhaitée
    $("#changeState").on("click", function () {
        var selectedData = table.rows('.selected').data().toArray();
        Swal.fire({
            title: "Confirmation : Changer l'étape des demandes sélectionnées ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Confirmer ",
            cancelButtonText: "Annuler !",
        }).then(function(result) {
            if (result.value) {

                selectedData.forEach(function (rowData) {
                    console.log(rowData);
                    // Créer un élément div temporaire
                    var tempDiv = document.createElement('div');

                    // Injecter la chaîne HTML dans l'élément div
                    tempDiv.innerHTML = rowData.state;
                    // Accéder à l'élément select
                    var selectElement = tempDiv.querySelector('select');
                    var selectId = $(selectElement).attr('id');
                    var selectedValue = $('#' + selectId).val();

                    $.ajax({
                        url: baseUrl + "/macro/update",
                        type: "POST",
                        data: {
                            "_token": TOKENSTOREDOCTOR,
                            id: rowData.id,
                            state: selectedValue,
                        },
                        success: function(response) {
                            $('#datatable1').DataTable().ajax.reload();
                        },
                        error: function(response) {
                            console.log(response)
                        },
                    })
                });



            }
        });

    // Décochez toutes les lignes après l'action
    table.rows().deselect();
});

});

function changeState(id,code) {
    // Sélectionnez l'élément par son ID
    var element = $('#id_test_pathology_order' + id);
    console.log(code);

    // Récupérez la valeur sélectionnée
    var selectedValue = element.val();
    var selected = "";
    if (selectedValue == 'circulation') {
        selected = "CIRCULATION"
    }
    if (selectedValue == 'embedding') {
        selected = "ENROBAGE"
    }
    if (selectedValue == 'microtomy_spreading') {
        selected = "MICROTOMIE ET ETALEMENT"
    }
    if (selectedValue == 'staining') {
        selected = "COLORATION"
    }
    if (selectedValue == 'mounting') {
        selected = "MONTAGE"
    }
    var selectedRows =  $('#datatable1').DataTable().rows('.selected').data().length > 0;
    if (!selectedRows) {
        Swal.fire({
            title: "Confirmation : Étape  ["+selected+"] pour la demande ['"+code+"'] ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Confirmer ",
            cancelButtonText: "Annuler !",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: baseUrl + "/macro/update",
                    type: "POST",
                    data: {
                        "_token": TOKENSTOREDOCTOR,
                        id: id,
                        state: selectedValue,
                    },
                    success: function(response) {
                        $('#datatable1').DataTable().ajax.reload();
                    },
                    error: function(response) {
                        console.log(response)
                    },
                })
            }
        });
    }



}

function addMacro(id,code) {
    var element = $('#' + id);
    var selectedValue = element.val();
    $.ajax({
        url: baseUrl + "/laborantin/" + selectedValue,
        type: "GET",

        success: function(response) {
            Swal.fire({
                title: "Confirmation : Étape [MACROSCOPIE] effectuée pour la demande ["+code+"] par ["+response+"] ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Confirmer ",
                cancelButtonText: "Annuler !",
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: baseUrl + "/macro/one-create",
                        type: "POST",
                        data: {
                            "_token": TOKENSTOREDOCTOR,
                            id: id,
                            id_employee: selectedValue,
                        },
                        success: function(response) {
                            $('#datatable1').DataTable().ajax.reload();
                            $('#datatable2').DataTable().ajax.reload();
                        },
                        error: function(response) {
                            console.log(response)
                        },
                    })
                }
            });
        },
        error: function(response) {
            console.log(response)
        },
    })

}



// //EDITION
// function edit(id) {
//     var e_id = id;

//     // Populate Data in Edit Modal Form
//     $.ajax({
//         type: "GET",
//         url: baseUrl + "/getdoctor/" + e_id,
//         success: function(data) {

//             $('#id2').val(data.id);
//             $('#name').val(data.name);
//             $('#telephone').val(data.telephone);
//             $('#email').val(data.email);
//             $('#role').val(data.role);
//             $('#commission').val(data.commission);



//             console.log(data);
//             $('#editModal').modal('show');
//         },
//         error: function(data) {
//             console.log('Error:', data);
//         }
//     });
//     $.ajax({
//         type: "GET",
//         url: baseUrl + "/gettest/" + test_id,
//         success: function(data) {


//             $('#price').val(data.price);

//         },
//         error: function(data) {
//             console.log('Error:', data);
//         }
//     });
// }

// function updateAttribuate(doctor_id, order_id) {

//     $.ajax({
//         type: "GET",
//         url: URLupdateAttribuate,
//         success: function(data) {
//             console.log(data)
//             if (data) {
//                 toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
//             }
//         },
//         error: function(data) {
//             console.log('Error:', data);
//         }
//     });
// }
