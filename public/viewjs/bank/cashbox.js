
// SUPPRESSION
function deleteModalVente(id) {

Swal.fire({
        title: "Voulez-vous supprimer l'élément ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui ",
        cancelButtonText: "Non !",
    }).then(function(result) {
        if (result.value) {
            window.location.href=baseUrl+"/cashbox/vente-delete/"+id;
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

    $('#datatable1').DataTable({
        "order": [],
        "columnDefs": [
            {
                "targets": [ 0 ],
                "searchable": true
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
} );


//EDITION
function editVente(id){
    var e_id = id;


    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl+"/cashbox/getcashvente/" + e_id,
        success: function (data) {
            console.log(data);

            $('#id2').val(data.data.id);
            $('#bank_id2').val(data.data.bank_id);
            $('#bank_name2').val(data.bank);
            $('#invoice_id2').val(data.data.invoice_id);
            $('#invoice_name2').val(data.invoice);
            $('#cheque_number2').val(data.data.cheque_number);
            $('#amount2').val(data.data.amount);
            $('#date2').val(data.data.date);
            $('#description2').val(data.data.description);

            // $('#lignebudgetaire_id2').val(data.lignebudgetaire_id).change();

            $('#editModal').modal('show');
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}
