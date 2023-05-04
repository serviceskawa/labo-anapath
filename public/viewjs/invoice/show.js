var code = new QRCode(document.getElementById("qrcode"), {
    text: invoice.qrcode,
    width: 100,
    height: 105,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

function invoicebtn(id) {
    console.log(invoice);

    $.ajax({
        url: baseUrl + "/invoices/updateStatus/" + invoice.id,
        type: "GET",

        success: function(response) {
            console.log(response);
            if (response) {
                //console.log(response);
                Swal.fire({
                    title: "Voulez-vous terminé la facture ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirm ",
                    cancelButtonText: "Annuler",
                }).then(function(result) {

                    if (result.value) {
                        $.ajax({
                            url: ROUTECONFIRMINVOICE,
                            type: "POST",
                            data: {
                                "_token": TOKENCONFIRMINVOICE,
                                uid: response.uid,
                                id: response.id,
                                invoice_id: invoice.id,
                            },
                            success: function(response) {
                                if (response) {
                                    console.log(response);
                                    toastr.success("Confirmé avec succès",
                                        'Confirmation réussi');
                                    window.location.href = baseUrl + "/invoices/print/" + invoice.id;
                                }
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    } else {
                        $.ajax({
                            url: ROUTECANCELINVOICE,
                            type: "POST",
                            data: {
                                "_token": TOKENCANCELINVOICE,
                                uid: response.uid,
                                id: response.id,
                                invoice_id: invoice.id,
                            },
                            success: function(response) {
                                if (response) {
                                    console.log(response);
                                    toastr.success("Annulé avec succès",
                                        'Annulation réussi');
                                    location.reload();
                                }
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                });
            }

        },
        error: function(response) {
            console.log(response)
        },
    })
}

function validPayment() {
    var payment = $('#payment').val();
    $.ajax({
        url: ROUTEVALIDATEPAYMENT,
        type: "POST",
        data: {
            "_token": TOKENVALIDATEPAYMENT,
            id: invoice.id,
            payment: payment,
        },
        success: function(response) {
            console.log(response);
            location.reload();
        },
        error: function(response) {
            console.log('error', response);
        }
    })
}