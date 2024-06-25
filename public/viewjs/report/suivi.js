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
            Swal.fire("Suppression !", "En cours de traitement ...", "success");
        }
    });
}

$(document).ready(function () {
    var table = $("#datatablesuivi").DataTable({
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
                d.statusquery = $("#statusquery").val();
                d.contenu = $("#contenu").val();
                d.dateBegin = $("#dateBegin").val();
                d.dateEnd = $("#dateEnd").val();
                d.type_examen = $("#type_examen").val();
                d.cas_status = $("#cas_status").val();
            },
        },
        columns: [
            {
                data: "date",
                name: "date",
            },
            {
                data: "code",
                name: "code",
            },
            {
                data: "macro",
                name: "macro",
            },
            {
                data: "report",
                name: "report",
            },
            {
                data: "call",
                name: "call",
            },
            {
                data: "delivery",
                name: "delivery",
            },
        ],
        // order: [[0, "asc"]],
        // dom: "Bfrtip",
        // lengthMenu: [
        //     [10, 25, 50, 100],
        //     [10, 25, 50, 100],
        // ], // Options de sélection
        // buttons: [
        //     "csv",
        //     "excel",
        //     "pdf",
        //     // "print",
        //     {
        //         extend: "print",
        //         text: "Print",
        //         exportOptions: {
        //             modifier: {
        //                 page: "all",
        //             },
        //         },
        //     },
        // ],

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
    $("#statusquery").on("change", function () {
        // alert(this.value)
        table.draw();
    });

    $("#cas_status").on("change", function () {
        // alert(this.value);
        table.draw();
    });

    $("#type_examen").on("change", function () {
        // alert(this.value)
        table.draw();
    });

    $("#contenu").on("input", function () {
        table.draw();
    });

    $("#dateEnd").on("input", function () {
        console.log($("#dateEnd").val());
        table.draw();
        //console.log(search.value);
    });

    $("#dateBegin").on("input", function () {
        console.log($("#dateBegin").val());
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
            $("#id2").val(data.id);
            $("#name2").val(data.name);
            $("#type2").val(data.type).change();
            $("#status2").val(data.status).change();
            $("#description2").val(data.description);

            console.log(data);
            $("#editModal").modal("show");
        },
        error: function (data) {
            console.log("Error:", data);
        },
    });
}
