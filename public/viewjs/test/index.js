/* DATATABLE */
$(document).ready(function () {
    $("#datatable1").DataTable({
        order: [],
        columnDefs: [
            {
                targets: [0],
                searchable: false,
            },
        ],
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
    });
});

$(document).ready(function () {
    var table = $("#datatable11").DataTable({
        order: [],
        columnDefs: [
            {
                targets: [0],
                searchable: false,
            },
        ],
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

        processing: true,
        serverSide: true,
        ajax: {
            url: ROUTEGETDATATABLE2,
            data: function (d) {
                d.statusquery = $("#statusquery").val();
                d.contenu = $("#contenu").val();
            },
        },
        columns: [
            {
                data: "name",
                name: "name",
            },
            {
                data: "category_name",
                name: "category_name",
            },

            {
                data: "price",
                name: "price",
            },
            {
                data: "status",
                name: "status",
            },
            {
                data: "action",
                name: "action",
            },
        ],
        order: [[0, "desc"]],
    });

    // Recherche selon les cas
    $("#statusquery").on("change", function () {
        table.draw();
    });

    $("#contenu").on("input", function () {
        table.draw();
    });
});

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
            window.location.href = baseUrl + "/test/delete/" + id;
            Swal.fire("Suppression !", "En cours de traitement ...", "success");
        }
    });
}

//EDITION
function edit(id) {
    var e_id = id;
    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/gettest/" + e_id,
        success: function (data) {
            $("#id2").val(data.id);
            $("#price2").val(data.price);
            $("#category_test_id2").val(data.category_test_id).change();
            $("#status2").val(data.status).change();
            $("#name2").val(data.name);
            $("#editModal").modal("show");
        },
        error: function (data) {
            console.log("Error:", data);
        },
    });
}
