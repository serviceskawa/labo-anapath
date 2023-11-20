// SUPPRESSION
function deleteModal(id) {

    Swal.fire({
        title: "Voulez-vous supprimer l'élément ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function(result) {
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

var checkbox = document.getElementById("switch3");
var textField = document.getElementById("show-client");
var selectElement = document.getElementById("client_id");

checkbox.addEventListener('change', function () {
    if (checkbox.checked) {
        textField.style.display = 'block';
        // Ajouter l'attribut "required" au champ de sélection
        selectElement.setAttribute("required", "required");
    } else {
        textField.style.display = 'none';
        // Supprimer l'attribut "required" du champ de sélection
        selectElement.removeAttribute("required");
    }
});

var checkbox1 = document.getElementById("switch4");
var textField1 = document.getElementById("show-client1");
var selectElement1 = document.getElementById("client_id1");

checkbox1.addEventListener('change', function () {
    if (checkbox1.checked) {
        textField1.style.display = 'block';
        // Ajouter l'attribut "required" au champ de sélection
        selectElement1.setAttribute("required", "required");
    } else {
        textField1.style.display = 'none';
        // Supprimer l'attribut "required" du champ de sélection
        selectElement1.removeAttribute("required");
    }
});

function closeModal(id) {

    Swal.fire({
        title: "Voulez-vous clôturer l'élément ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function(result) {
        if (result.value) {
            window.location.href = baseUrl + "/contrats/close/" + id;
            Swal.fire(
                "Clôture !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}


/* DATATABLE */
$(document).ready(function() {

    $('#datatable1').DataTable({
        "order": [],
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
    });
});


//EDITION
function edit(id) {
    var e_id = id;
    var checkbox2 = document.getElementById("switch4");
    var textField2 = document.getElementById("show-client1");
    // var baseUrl = "{{ url('/') }}";

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/getcontrat/" + e_id,
        success: function(data) {

            $('#id2').val(data.id);
            $('#name2').val(data.name);
            $('#type2').val(data.type).change();
            $('#status2').val(data.status).change();
            $('#description2').val(data.description);
            $('#nbr_examen').val(data.nbr_tests);

            if (data.invoice_unique == 0) {
                checkbox2.checked = true
                textField2.style.display = 'block';
                $('#client_id1').val(data.client_id).change()
            }

            $('#editModal').modal('show');
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}
