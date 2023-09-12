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

// function updateStatus(id)
// {
//         var status = $('#example-select').val();
//         var e_id = id;
//         console.log(e_id,status);

//         $.ajax({
//         url: ROUTEUPDATESTATUSTICKET,
//         type: "POST",
//         data: {
//             "_token": TOKENUPDATESTATUSTICKET,
//             id: e_id,
//             status:status,
//         },
//         success: function (data) {
//             console.log(data);
//             toastr.success("Mis à jour avec succès", 'Ajout réussi');
//             // location.reload();
//         },
//         error: function (error) {
//             console.log(error);
//         }
//     })

// }

$(document).ready(function() {



    $('#quantity').on("input", function(){
        var price = $('#unit_price').val();
        var quantity = $('#quantity').val();
        $('#total').val(price*quantity)
    });


    var dt_basic = $('#datatable1').DataTable({
        ajax: {
            url: ROUTEGETDETAIL,
            dataSrc: ''
        },

        columns: [
            // columns according to JSON
            {
                data: 'id'
            },
            {
                data: 'item_name'
            },
            {
                data: 'unit_price'
            },
            {
                data: 'quantity'
            },
            {
                data: 'line_amount'
            },
            {
                data: null
            }
        ],
        columnDefs: [{
            "targets": -1,
            "render": function(data, type, row) {

                return (
                    '<button type="button" id="deleteBtn" class="btn btn-danger"> <i class="mdi mdi-trash-can-outline"></i> </button>'
                );
                return "";
            }

        }],

        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                    'number' ? i : 0;
            };

            // Total over all pages
            total = api
                .column(4)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
            //
            quantity = api
                .column(3)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            unit_price = api
                .column(2)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(4).footer()).html(total);
            $(api.column(3).footer()).html(quantity);
            $(api.column(2).footer()).html(unit_price);

            sendTotal(ticket.id, total);

            var numRows = api.rows().count();
            if (numRows > 0) {
                var element = document.getElementById('finalisationBtn');
                if (element) {
                    element.classList.remove("disabled");
                }
            }
        },

    });

    if (ticket.status =="approuve" || ticket.status == "rejete") {
        dt_basic.column(5).visible( false );
    }

    $('.detail-list-table tbody').on('click', '#deleteBtn', function() {
        var data = dt_basic.row($(this).parents('tr')).data();
        var id = $(this).data('id');
        console.log(data)
        Swal.fire({
            title: "Voulez-vous supprimer l'élément ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui ",
            cancelButtonText: "Non !",
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: baseUrl +"/cashbox/ticket-detail-delete/"+data.id,
                    success: function(response) {

                        console.log(response);
                        Swal.fire(
                            "Suppression !",
                            "En cours de traitement ...",
                            "success"
                        )
                        dt_basic.ajax.reload()
                    },
                    error: function(response) {
                        console.log(response);
                        dt_basic.ajax.reload()

                    },
                });

            }
        });
    });

    function sendTotal(ticket_id, amount) {
        console.log(ticket_id, unit_price, quantity);

        $.ajax({
            url: ROUTEUPDATETOTALTICKET,
            type: "POST",
            data: {
                "_token": TOKENUPDATETOTALTICKET,
                ticket_id: ticket_id,
                amount: amount
            },
            success: function(response) {
                console.log(response)

            },
            error: function(response) {
                console.log(response)
            },
        });
    };

})

// $('#addDetailForm').on('submit', function(e) {
$('#add_detail').on('click', function(e) {
    e.preventDefault();
    let cashbox_ticket_id = ticket.id;
    // console.log(cashbox_ticket_id);
    let item_name = $('#article-name').val();
    let price = $('#unit_price').val();
    let quantity = $('#quantity').val();
    let total = $('#total').val();
    if (item_name == "") {
        toastr.error("Ajouter un article",'Article')
    }else if (price == "") {
        toastr.error("Le prix de l'artile est requis",'Prix article')
    }else if (quantity == "") {
        toastr.error("La quantité de l'article",'Quantité article')
    }else{

    $.ajax({
        url: ROUTESTOREDETAILTICKET,
        type: "POST",
        data: {
            "_token": TOKENSTOREDETAILTICKET,
            cashbox_ticket_id: cashbox_ticket_id,
            item_name: item_name,
            unit_price: price,
            quantity: quantity,
            total: total

        },
        success: function(response) {
            $('#addDetailForm').trigger("reset")
            console.log(response);
            if (response) {
                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
            }
            $('#datatable1').DataTable().ajax.reload();
            // $('#addDetailForm').trigger("reset")
            // updateSubTotal();
        },
        error: function(response) {
            console.log(response)
        },
    });
    }

});

