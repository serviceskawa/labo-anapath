// SUPPRESSION
function deleteModal(id) {

    Swal.fire({
        title: "La supression d'un examen entraine la suppression du Rapport. Voulez-vous continuez?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function(result) {
        if (result.value) {
            window.location.href = baseUrl + "/test_order/delete/" + id;
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

    var table = $('#datatable3').DataTable({

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
            url: ROUTETESTORDERDATATABLE3,
            data: function(d) {
                d.contrat_id = $('#contrat_immuno_id').val()
                d.exams_status = $('#exams_immuno_status').val()
                d.type_examen = $('#type_immuno_examen').val()
                d.cas_status = $('#cas_immuno_status').val()
                d.attribuate_doctor_id = $('#doctor_immuno_signataire').val()
                d.contenu = $('#contenu_immuno').val()
                d.dateBegin = $('#dateBegin_immuno').val()
                d.dateEnd = $('#dateEnd_immuno').val()

                d.appel = $('#appel').val()
                d.appel = $('#appel_status').val()
            }
        },
        columns: [{
                data: 'action',
                name: 'action',
            },
            {
                data: 'appel',
                name: 'appel'
            },
            {
                data: 'created_at',
                name: 'created_at'
            }, {
                data: 'code',
                name: 'code'
            }, {
                data: 'dropdown',
                name: 'dropdown'
            },
            {
                data: 'patient',
                name: 'patient',
            },
            {
                data: 'details',
                name: 'examens'
            },
            {
                data: 'contrat',
                name: 'contrat'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'rendu',
                name: 'rendu'
            },
            {
                data: 'urgence',
                name: 'urgence',
                visible: false,
            },
        ],
        order: [
            [0, 'asc']
        ],

    });


    // Recherche selon les contrats
    $("#contrat_immuno_id").on("change", function() {
        // alert(this.value)
        table.draw();
    });

    // Recherche selon le status d'examen
    $("#exams_immuno_status").on("change", function() {
        // alert(this.value)
        table.draw();
    });

    // Recherche selon les types d'examen
    $("#type_immuno_examen").on("change", function() {
        // alert(this.value)
        table.draw();
    });



    // Recherche selon les docteurs signataires
    $("#doctor_immuno_signataire").on("change", function() {
        // alert(this.value)
        table.draw();
    });


    // Recherche selon les cas
    $("#cas_immuno_status").on("change", function() {
        // alert(this.value)
        table.draw();
    });

    // $("#appel").on("change", function() {
    //     table.draw();
    // })
    $('#contenu_immuno').on("input", function() {
        table.draw();
    });

    $('#dateEnd_immuno').on('input', function() {
        // console.log($('#dateEnd').val());
        table.draw();
        //console.log(search.value);
    });

    $('#dateBegin_immuno').on('input', function() {
        // console.log($('#dateBegin').val());
        table.draw();
        //console.log(search.value);
    });

    $('#appel_status').on('change', function() {
        // alert(this.value);
        table.draw();
    });
















    var table_2 = $('#datatable4').DataTable({

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
            url: ROUTETESTORDERDATATABLE4,
            data: function(d) {
                // d.attribuate_doctor_id = $('#doctor_signataire').val()
                // d.cas_status = $('#cas_status').val()
                // d.appel = $('#appel').val()
                // d.contrat_id = $('#contrat_id').val()
                // d.exams_status = $('#exams_status').val()
                // d.type_examen = $('#type_examen').val()
                // d.contenu = $('#contenu').val()
                // d.dateBegin = $('#dateBegin').val()
                // d.dateEnd = $('#dateEnd').val()
                // d.appel = $('#appel_status').val()

            }
        },
        columns: [{
                data: 'action',
                name: 'action',
            },
            // {
            //     data: 'appel',
            //     name: 'appel'
            // },
            {
                data: 'created_at',
                name: 'created_at'
            }, {
                data: 'code',
                name: 'code'
            }, {
                data: 'dropdown',
                name: 'dropdown'
            },
            {
                data: 'patient',
                name: 'patient',
            },
            {
                data: 'details',
                name: 'examens'
            },
            {
                data: 'contrat',
                name: 'contrat'
            },
            {
                data: 'total',
                name: 'total'
            },
            {
                data: 'rendu',
                name: 'rendu'
            },
            {
                data: 'urgence',
                name: 'urgence',
                visible: false,
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


    // Recherche selon les docteurs signataires
    $("#doctor_signataire").on("change", function() {
        // alert(this.value)
        table.draw();
    });

    // Recherche selon les contrats
    $("#contrat_id").on("change", function() {
        alert(this.value)
        table.draw();
    });
    // Recherche selon le status d'examen
    $("#exams_status").on("change", function() {
        // alert(this.value)
        table.draw();
    });
    // Recherche selon les types d'examen
    $("#type_examen").on("change", function() {
        // alert(this.value)
        table.draw();
    });
    // Recherche selon les cas
    $("#cas_status").on("change", function() {
        // alert(this.value)
        table.draw();
    });

    // $("#appel").on("change", function() {
    //     table.draw();
    // })
    $('#contenu').on("input", function() {
        table.draw();
    });

    $('#dateEnd').on('input', function() {
        console.log($('#dateEnd').val());
        table.draw();
        //console.log(search.value);
    });

    $('#dateBegin').on('input', function() {
        console.log($('#dateBegin').val());
        table.draw();
        //console.log(search.value);
    });

    $('#appel_status').on('change', function() {
        // alert(this.value);
        table.draw();
    });

});


//EDITION
function edit(id) {
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/getdoctor/" + e_id,
        success: function(data) {

            $('#id2').val(data.id);
            $('#name').val(data.name);
            $('#telephone').val(data.telephone);
            $('#email').val(data.email);
            $('#role').val(data.role);
            $('#commission').val(data.commission);



            console.log(data);
            $('#editModal').modal('show');
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
    $.ajax({
        type: "GET",
        url: baseUrl + "/gettest/" + test_id,
        success: function(data) {


            $('#price').val(data.price);

        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}

function updateAttribuate(doctor_id, order_id) {

    $.ajax({
        type: "GET",
        url: URLupdateAttribuate,
        success: function(data) {
            console.log(data)
            if (data) {
                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
            }
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}

function confirmAction(id) {


    Swal.fire({
        title: "Voulez-vous marquer comme retirer ce compte rendu ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function(result) {
        $.ajax({
            type: "GET",
            url: baseUrl + "/report/updateDeliver/" + id,
            success: function(data) {
                console.log(data)
                if (data) {
                    // toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                    $('#datatable1').DataTable().ajax.reload();
                    $('#datatable2').DataTable().ajax.reload();
                }
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    });

}