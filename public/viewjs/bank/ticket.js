// SUPPRESSION
function deleteTicket(id) {
    Swal.fire({
            title: "Voulez-vous supprimer l'élément ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui ",
            cancelButtonText: "Non !",
        }).then(function(result) {
            if (result.value) {
                window.location.href=baseUrl+"/cashbox/ticket-delete/"+id;
                Swal.fire(
                    "Suppression !",
                    "En cours de traitement ...",
                    "success"
                )
            }
    });
}
function deleteTicketDetail(id) {
    Swal.fire({
            title: "Voulez-vous supprimer l'élément ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui ",
            cancelButtonText: "Non !",
        }).then(function(result) {
            if (result.value) {
                window.location.href=baseUrl+"/cashbox/ticket-detail-delete/"+id;
                Swal.fire(
                    "Suppression !",
                    "En cours de traitement ...",
                    "success"
                )
            }
    });
}

function updateStatus(id)
{
        var status = $('#example-select').val();
        var e_id = id;
        console.log(e_id,status);

        $.ajax({
        url: ROUTEUPDATESTATUSTICKET,
        type: "POST",
        data: {
            "_token": TOKENUPDATESTATUSTICKET,
            id: e_id,
            status:status,
        },
        success: function (data) {
            console.log(data)
        },
        error: function (error) {
            console.log(error);
        }
    })

}
