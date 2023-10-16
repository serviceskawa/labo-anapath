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
                data: 'test_order_id'
            },
            {
                data: 'note'
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

    });


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
                    url: baseUrl +"assignment/detail/destroy/"+data.id,
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

})

// $('#addDetailForm').on('submit', function(e) {
$('#add_detail').on('click', function(e) {
    e.preventDefault();
    let test_order_id = $('#test_order_id').val();
    let note = $('#note').val();
    console.log(assignment.id);
    console.log(test_order_id);
    console.log(note);

    $.ajax({
        url: ROUTESTOREDETAILTICKET,
        type: "POST",
        data: {
            "_token": TOKENSTOREDETAILTICKET,
            test_order_assignment_id:assignment.id,
            test_order_id: test_order_id,
            note: note

        },
        success: function(response) {
            $('#addDetailForm').trigger("reset")
            console.log(response);
            if (response) {
                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                $('#test_order_id').val('');
                $('#note').val('');
            }
            $('#datatable1').DataTable().ajax.reload();
            // $('#addDetailForm').trigger("reset")
            // updateSubTotal();
        },
        error: function(response) {
            console.log(response)
        },
    });


});

