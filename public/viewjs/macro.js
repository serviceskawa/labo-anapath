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
                data: 'code',
                name: 'code'
            },
             {
                data: 'date',
                name: 'date',
            },
             {
                data: 'state',
                name: 'state',
            },
        ],
        order: [
            [0, 'asc']
        ],

    });

    $.fn.dataTable.ext.search.push(
        function(settings, searchData, index, rowData, counter) {
            var row = table.row(index).node();
            var filterValue = $(row).data('mytag');
            var e = document.getElementById("cas_status");
            var filter = e.options[e.selectedIndex].value;

            if (filterValue == filter) {
                return true;
            } else if (filter == "") {
                return true;
            } else {
                return false;
            }

        }
    );

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
});

function changeState(id) {
    // Sélectionnez l'élément par son ID
    var element = $('#id_test_pathology_order' + id);

    // Récupérez la valeur sélectionnée
    var selectedValue = element.val();
    var selected = "";
    if (selectedValue == 'circulation') {
        selected = "Circulation"
    }
    if (selectedValue == 'embedding') {
        selected = "Enrobage"
    }
    if (selectedValue == 'microtomy_spreading') {
        selected = "Microtomie et Etalement"
    }
    if (selectedValue == 'staining') {
        selected = "Coloration"
    }
    if (selectedValue == 'mounting') {
        selected = "Montage"
    }

    Swal.fire({
        title: "Voulez-vous changer l'état de ce macro en "+selected+" ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
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
function addMacro(id) {
    var element = $('#' + id);
    var selectedValue = element.val();
    Swal.fire({
        title: "Voulez-vous ajouter ce macro en ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
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
