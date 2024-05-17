
// $(document).ready(function() {

//     var table = $('#reports-rapports').DataTable({
//         "order": [],
//         // language: {
//         //     url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json",
//         // },
//         "language": {
//             "lengthMenu": "Afficher _MENU_ enregistrements par page",
//             "zeroRecords": "Aucun enregistrement disponible",
//             "info": "Afficher page _PAGE_ sur _PAGES_",
//             "infoEmpty": "Aucun enregistrement disponible",
//             "infoFiltered": "(filtré à partir de _MAX_ enregistrements au total)",
//             "sSearch": "Rechercher:",
//             "paginate": {
//                 "previous": "Précédent",
//                 "next": "Suivant"
//             }
//         },
//         initComplete: function() {

//         },
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: ROUTEREPORTRAPPORTDATATABLE,
//             data: function(d) {
//                 // d.statusquery = $('#statusquery').val()
//                 // d.contenu = $('#contenu').val()
//                 // d.dateBegin = $('#dateBegin').val()
//                 // d.dateEnd = $('#dateEnd').val()
//                 // d.type_examen = $('#type_examen').val()
//                 // d.cas_status = $('#cas_status').val()
//             }
//         },
//         columns: [
//             {
//                 data: 'total_general',
//                 name: 'total_general'
//             },
//             {
//                 data: 'exam_request',
//                 name: 'exam_request'
//             },
//             {
//                 data: 'macro',
//                 name: 'macro'
//             },
//             {
//                 data: 'test_report',
//                 name: 'test_report'
//             },
//             {
//                 data: 'patient_informed',
//                 name: 'patient_informed',
//             },
//             {
//                 data: 'patient_delivered',
//                 name: 'patient_delivered'
//             }
//         ],
//         order: [
//             [0, 'asc']
//         ],


//     });
//     // Recherche selon les cas
//     $("#statusquery").on("change", function() {
//         // alert(this.value)
//         table.draw();
//     });

//     $("#cas_status").on("change", function() {
//         // alert(this.value)
//         table.draw();
//     });

//     $("#type_examen").on("change", function() {
//         // alert(this.value)
//         table.draw();
//     });

//     $('#contenu').on("input", function() {
//         table.draw();
//     });

//     $('#dateEnd').on('input', function() {
//         console.log($('#dateEnd').val());
//         table.draw();
//     });

//     $('#dateBegin').on('input', function() {
//         console.log($('#dateBegin').val());;
//         table.draw();
//     });

// });

$(document).ready(function() {
    $('form #rapports-datatables').on('submit', function(e) {
        e.preventDefault();

        var month = $('#month').val();
        var year = $('#year').val();

        $.ajax({
            url: '/suivi/index',
            type: 'GET',
            data: {
                month: month,
                year: year
            },
            success: function(response) {

                 // Construire l'URL dynamiquement avec les valeurs de month et year
                 var newUrl = "http://127.0.0.1:8000/report/suivi/index?year=" + year + "&month=" + month + "#rapports";
                 location.href = newUrl;
            }
        });
    });
});
