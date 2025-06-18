$(document).ready(function () {
    var table = $("#datatables-global-searchs").DataTable({
        columnDefs: [
            {
                targets: [0],
                searchable: false,
            },
        ],
        bFilter: false,
        language: {
            lengthMenu: "Afficher _MENU_ enregistrements par page",
            zeroRecords: "Aucun enregistrement disponible",
            info: "Afficher page _PAGE_ sur _PAGES_",
            infoEmpty: "Aucun enregistrement disponible",
            infoFiltered: "(filtré à partir de _MAX_ enregistrements au total)",
            sSearch: "Rechercher:",
            paginate: {
                previous: "Précédent",
                next: "Suivant",
            },
        },

        initComplete: function () {},
        processing: true,
        serverSide: true,
        ajax: {
            url: ROUTEGETDATATABLE,
            data: function (d) {
                d.type_examen_ids = $("#type_examen_ids").val();
                console.log('Données envoyées :', d.type_examen_ids);

                d.contrat_ids = $("#contrat_ids").val();
                console.log('Données envoyées :', d.contrat_ids);

                d.patient_ids = $("#patient_ids").val();
                console.log('Données envoyées :', d.patient_ids);

                d.doctor_ids = $("#doctor_ids").val();
                console.log('Données envoyées :', d.doctor_ids);

                d.hospital_ids = $("#hospital_ids").val();
                console.log('Données envoyées :', d.hospital_ids);

                d.reference_hopital = $("#reference_hopital").val();
                console.log('Données envoyées :', d.reference_hopital);

                d.dateBegin = $("#dateBegin").val();
                console.log('Données envoyées :', d.dateBegin);

                d.dateEnd = $("#dateEnd").val();
                console.log('Données envoyées :', d.dateEnd);

                d.content = $("#content").val();
                console.log('Données envoyées :', d.content);

                d.status_urgence_test_order_id = $("#status_urgence_test_order_id").val();
                console.log('Données envoyées :', d.status_urgence_test_order_id);
            },
        },
        columns: [
            {
                data: "codeReport",
                name: "codeReport",
            },
            {
                data: "codeExamen",
                name: "codeExamen",
            },
            {
                data: "type_examen",
                name: "type_examen",
            },
            {
                data: "contract_name",
                name: "contract_name",
            },
            {
                data: "patient_name",
                name: "patient_name",
            },
            {
                data: "doctor_name",
                name: "doctor_name",
            },
            {
                data: "hospital_name",
                name: "hospital_name",
            },
            {
                data: "reference_hospital_name",
                name: "reference_hospital_name",
            },
            {
                data: "dateCreation",
                name: "dateCreation",
            },
            {
                data: "urgenceStatusTestOrder",
                name: "urgenceStatusTestOrder",
            },
        ],

        order: [[0, "asc"]],
        dom: '<"top"lfB>rt<"bottom"ip><"clear">', // Ajout de 'l' pour afficher la longueur de la page
        lengthMenu: [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "Tout"],
        ], // Options de sélection
        buttons: [
            "csv",
            "excel",
            "pdf",
            {
                extend: "print",
                text: "Imprimer",
                exportOptions: {
                    modifier: {
                        page: "all",
                    },
                },
            },
        ],
    });

    // Recherche selon les cas
    $("#type_examen_ids").on("change", function () {
        console.log($("#type_examen_ids").val());
        // alert(this.value);
        table.draw();
    });

    $("#contrat_ids").on("change", function () {
        console.log($("#contrat_ids").val());
        // alert(this.value);
        table.draw();
    });

    $("#patient_ids").on("change", function () {
        console.log($("#patient_ids").val());
        // alert(this.value);
        table.draw();
    });

    $("#doctor_ids").on("change", function () {
        console.log($("#doctor_ids").val());
        // alert(this.value);
        table.draw();
    });

    $("#hospital_ids").on("change", function () {
        console.log($("#hospital_ids").val());
        // alert(this.value);
        table.draw();
    });

    $("#reference_hopital").on("input", function () {
        table.draw();
    });

    $("#dateEnd").on("input", function () {
        console.log($("#dateEnd").val());
        table.draw();
    });

    $("#dateBegin").on("input", function () {
        console.log($("#dateBegin").val());
        table.draw();
    });

    $("#content").on("input", function () {
        table.draw();
    });

    $("#status_urgence_test_order_id").on("change", function () {
        table.draw();
    });

});


$('#exportBtn').on('click', function () {
    $('#export_type_examen_ids').val($('#type_examen_ids').val());
    $('#export_contrat_ids').val($('#contrat_ids').val());
    $('#export_patient_ids').val($('#patient_ids').val());
    $('#export_doctor_ids').val($('#doctor_ids').val());
    $('#export_hospital_ids').val($('#hospital_ids').val());

    $('#export_reference_hopital').val($('#reference_hopital').val());
    $('#export_dateBegin').val($('#dateBegin').val());
    $('#export_dateEnd').val($('#dateEnd').val());
    $('#export_content').val($('#content').val());
    $('#export_status_urgence_test_order_id').val($('#status_urgence_test_order_id').val());

    $('#exportForm').submit();
});



// $('#export-excel').on('click', function (e) {
//     e.preventDefault();

//     const params = {
//         type_examen_ids: $('#type_examen_ids').val(),
//         contrat_ids: $('#contrat_ids').val(),
//         patient_ids: $('#patient_ids').val(),
//         doctor_ids: $('#doctor_ids').val(),
//         hospital_ids: $('#hospital_ids').val(),
//         reference_hopital: $('#reference_hopital').val(),
//         dateBegin: $('#dateBegin').val(),
//         dateEnd: $('#dateEnd').val(),
//         content: $('#content').val(),
//         status_urgence_test_order_id: $('#status_urgence_test_order_id').val(),
//     };

//     console.log(hospital_ids);
    

//     fetch('/export-reports', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
//         },
//         body: JSON.stringify(params),
//     })
//     .then(response => response.blob())
//     .then(blob => {
//         const url = window.URL.createObjectURL(blob);
//         const a = document.createElement('a');
//         a.href = url;
//         a.download = "rapports.xlsx";
//         document.body.appendChild(a);
//         a.click();
//         a.remove();
//         window.URL.revokeObjectURL(url);
//     });
// });

