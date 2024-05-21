$('.dropify').dropify();

Dropzone.autoDiscover = false;

$(document).ready(function() {


    console.log(invoiceTest)


    $.ajax({
        url: '/examen-images/' + test_order_code,
        method: 'GET',
        success: function(response) {
            var fileNames = response.file_names;

            if (fileNames && fileNames.length > 0) {
                var dropzone = $("#image-dropzone").dropzone({
                    url: ROUTEFILEUPLOAD,
                    headers: {
                        'X-CSRF-TOKEN': TOKENGETFILEUPDATE
                    },
                    params: {
                        code: test_order_code, // Valeur du paramètre exam_code à envoyer avec la requête
                    },
                    paramName: "image",
                    thumbnailWidth: 300,
                    thumbnailHeight: 300,
                    maxFilesize: 2,
                    acceptedFiles: ".jpg, .jpeg, .png",
                    addRemoveLinks: true,
                    dictRemoveFile: "Supprimer",
                    success: function(file, response) {
                        console.log(response);
                    },
                    removedfile: function(file) {
                        var _ref;
                        if ((_ref = file.previewElement) != null) {
                            _ref.parentNode.removeChild(file.previewElement);
                        }
                        console.log(file);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

                dropzone.on('addedfile', function(file) {
                    // Supprimez le fichier ajouté par défaut
                    dropzone.removeFile(file);
                });

                for (var i = 0; i < fileNames.length; i++) {
                    var fileName = fileNames[i];

                    var imageUrl = '/storage/examen_images/' + test_order_code + '/' + fileName;

                    dropzone.createThumbnailFromUrl(
                        imageUrl,
                        dropzone.options.thumbnailWidth,
                        dropzone.options.thumbnailHeight,
                        dropzone.options.thumbnailMethod,
                        true,
                        function(thumbnail) {
                            dropzone.emit('addedfile', thumbnail);
                            dropzone.emit('complete', thumbnail);
                        }
                    );
                }
            } else {
                $("#image-dropzone").dropzone({
                    url: ROUTEFILEUPLOAD,
                    headers: {
                        'X-CSRF-TOKEN': TOKENGETFILEUPDATE
                    },
                    params: {
                        code: test_order_code, // Valeur du paramètre exam_code à envoyer avec la requête
                    },
                    paramName: "image",
                    maxFilesize: 2,
                    acceptedFiles: ".jpg, .jpeg, .png",
                    addRemoveLinks: true,
                    dictRemoveFile: "Supprimer",
                    success: function(file, response) {
                        console.log(response);
                    },
                    removedfile: function(file) {
                        var _ref;
                        if ((_ref = file.previewElement) != null) {
                            _ref.parentNode.removeChild(file.previewElement);
                        }
                        console.log(file);
                    },
                    error: function(file) {
                        console.log(file);
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });


    var dtDetailTable = $('.detail-list-table')

    var dt_basic = $('#datatable1').DataTable({
        ajax: {
            url: baseUrl + '/test_order/detailstest/' + test_order.id,
            dataSrc: ''
        },
        // deferRender: true,
        columns: [
            // columns according to JSON
            {
                data: 'id'
            },
            {
                data: 'test_name'
            },
            {
                data: 'price'
            },
            {
                data: 'discount'
            },
            {
                data: 'total'
            },
            {
                data: null
            }
        ],
        columnDefs: [{
            "targets": -1,
            //"targets": [0],
            "render": function(data, type, row) {
                // if (test_order.status != 1) {
                //     if (row["status"] == 1) {
                //         return (
                //             '<button type="button" id="deleteBtn" class="btn btn-danger"> <i class="mdi mdi-trash-can-outline"></i> </button>'
                //         );
                //     }
                // } else {

                if (invoiceTest) {
                    if (invoiceTest.paid == 1) {
                        return (
                            ''
                        );
                    } else {
                        return (
                            '<button type="button" id="deleteBtn" class="btn btn-danger"> <i class="mdi mdi-trash-can-outline"></i> </button>'
                        );
                    }
                } else {
                    return (
                        '<button type="button" id="deleteBtn" class="btn btn-danger"> <i class="mdi mdi-trash-can-outline"></i> </button>'
                    );
                }
                // }

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
            discount = api
                .column(3)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            subTotal = api
                .column(2)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(4).footer()).html(total);
            $(api.column(3).footer()).html(discount);
            $(api.column(2).footer()).html(subTotal);

            sendTotal(test_order.id, total, discount, subTotal);

            var numRows = api.rows().count();
            if (numRows > 0) {
                var element = document.getElementById('finalisationBtn');
                if (element) {
                    element.classList.remove("disabled");
                }
            }
        },

    });

    if (invoiceTest) {
        if (invoiceTest.paid == 1) {
            dt_basic.column(5).visible(false);
        }
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
                    url: baseUrl + "/test_order/detailsdelete",
                    type: "post",
                    data: {
                        "_token": TOKENDETAILDELETE,
                        id: data.id,
                    },
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

                        // Command: toastr["error"]("Error")
                    },
                });

            }
        });
    });

    $('.detail-list-table tbody').on('click', '#editBtn', function() {
        var data = dt_basic.row($(this).parents('tr')).data();
        var dataline = dt_basic.row($(this).parents('tr'))
        console.log(dataline[0][0]);
        $('#row_id').val(dataline[0][0]);
        $('#test_id1').val(data.test_id);
        $('#price1').val(data.price);
        $('#remise1').val(data.discount);

        $('#total1').val(data.total);
        $('#editModal').modal('show');
    });

    function sendTotal(test_order_id, total, discount, subTotal) {
        console.log(test_order_id, total, discount, subTotal);

        $.ajax({
            url: ROUTEUPDATEORDER,
            type: "POST",
            data: {
                "_token": TOKENUPDATEORDER,
                test_order_id: test_order_id,
                discount: discount,
                subTotal: subTotal,
                total: total

            },
            success: function(response) {
                console.log(response)

            },
            error: function(response) {
                console.log(response)
            },
        });
    };
});

$('#addDetailForm').on('submit', function(e) {
    e.preventDefault();
    let test_order_id = $('#test_order_id').val();
    let test_id = $('#test_id').val();
    let price = $('#price').val();
    let remise = $('#remise').val();
    let total = $('#total').val();

    $.ajax({
        url: ROUTESTOREDETAILTESTORDER,
        type: "POST",
        data: {
            "_token": TOKENSTOREDETAILTESTORDER,
            test_order_id: test_order_id,
            test_id: test_id,
            price: price,
            discount: remise,
            total: total

        },
        success: function(response) {
            $('#addDetailForm').trigger("reset")

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

});

function getTest() {
    var test_id = $('#test_id').val();

    // Importation des paramètres de getRemise
    var contrat_id = $('#contrat_id').val();

    let element = document.getElementById("test_id");
    let category_test_id = element.options[element.selectedIndex].getAttribute("data-category_test_id");

    $.ajax({
        type: "POST",
        url: ROUTEGETREMISE,
        data: {
            "_token": TOKENGETREMISE,
            testId: test_id,
            contratId: contrat_id,
            categoryTestId: category_test_id
        },
        success: function(data) {

            $('#price').val(data.data.price);

            var discount = $('#price').val() * data.detail / 100;
            $('#remise').val(discount);

            var total = $('#price').val() - discount;
            $('#total').val(total);

        },
        error: function(data) {
            console.log('Error:', data);
        }
    });

}

function edit(id) {
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        url: baseUrl + '/test_order/detailstest/' + e_id,
        dataSrc: '',
        success: function(data) {
            console.log(data);
            $('#test_id1').val(data.test_id);
            $('#price1').val(data.price);
            $('#remise1').val(data.discount);
            $('#total1').val(data.total);
            $('#editModal').modal('show');
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}
$('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    startDate: '-3d'
});

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
            window.location.href = baseUrl + "/contrats_details/delete" + id;
            Swal.fire(
                "Suppression !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}

//EDITION
function edit(id) {
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: baseUrl + "/getcontratdetails" + e_id,
        success: function(data) {

            $('#category_test_id2').val(data.category_test_id).change();
            $('#pourcentage2').val(data.pourcentage);
            $('#contrat_id2').val(data.contrat_id);
            $('#contrat_details_id2').val(data.id);



            console.log(data);
            $('#editModal').modal('show');
        },
        error: function(data) {
            console.log('Error:', data);
        }
    });
}

$(document).ready(function() {

    $('#doctor_id').select2({
        placeholder: 'Sélectionner le médecin traitant',
        // theme: 'bootstrap4',
        tags: true,
    }).on('select2:close', function() {
        var element = $(this);
        var new_category = $.trim(element.val());

        if (new_category != '') {
            $.ajax({
                url: ROUTESTOREDOCTOR,
                method: "POST",
                data: {
                    "_token": TOKENSTOREDOCTOR,
                    name: new_category
                },
                success: function(data) {

                    if (data.status === "created") {
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                        element.append('<option value="' + data.id + '">' + data.name +
                            '</option>').val(new_category);
                    }
                }
            })
        }

    });

    // Create hopital
    $('#hospital_id').select2({
        placeholder: 'Sélectionner le centre hospitalier de provenance',
        // theme: 'bootstrap4',
        tags: true,
    }).on('select2:close', function() {
        var element = $(this);
        var new_category = $.trim(element.val());

        if (new_category != '') {
            $.ajax({
                url: ROUTESTOREHOSPITAL,
                method: "POST",
                data: {
                    "_token": TOKENSTOREHOSPITAL,
                    name: new_category
                },
                success: function(data) {

                    if (data.status === "created") {
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                        element.append('<option value="' + data.id + '">' + data.name +
                            '</option>').val(new_category);
                    }
                }
            })
        }

    });

    $('#createPatientForm').on('submit', function(e) {
        e.preventDefault();
        let code = $('#code').val();
        let lastname = $('#lastname').val();
        let firstname = $('#firstname').val();
        let age = $('#age').val();
        let year_or_month = $('#year_or_month').val();
        let telephone1 = $('#telephone1').val();
        let genre = $('#genre').val();
        let langue = $('#langue').val();
        // alert(firstname);
        $.ajax({
            url: ROUTESTOREPATIENT,
            type: "POST",
            data: {
                "_token": TOKENSTOREPATIENT,
                code: code,
                lastname: lastname,
                firstname: firstname,
                age: age,
                year_or_month: year_or_month,
                telephone1: telephone1,
                genre: genre,
                langue: langue,
            },
            success: function(data) {

                $('#createPatientForm').trigger("reset")
                $('#standard-modal').modal('hide');
                toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                $('#patient_id').append('<option value="' + data.id + '">' + data.code +
                        ' - ' + data.firstname + ' ' + data.lastname + '</option>')
                    .trigger('change').val(data.id);

            },
            error: function(data) {
                console.log(data)
            },
            // processData: false,
        });

    });
});


// $(document).ready(function() {

//     $('#type_examen').on('change', function(e) {
//         var typeExamenOption = $('#type_examen option:selected').text();

//         // alert(typeExamenOption);
//         // if (typeExamenOption == "Immuno Externe") {
//         //     $(".examenReferenceSelect").hide();
//         //     $(".examenReferenceInput").show();

//         // } else if (typeExamenOption == "Immuno Interne") {
//         //     $(".examenReferenceSelect").hide();
//         //     $(".examenReferenceInput").show();

//         // } else {
//         //     $(".examenReferenceInput").hide();
//         //     $(".examenReferenceSelect").hide();
//         // }


//         if (typeExamenOption == "Immuno Externe") {
//             // Si "Immuno Externe" est sélectionné, masquer le champ "Examen de Référence Interne" et afficher le champ "Examen de Référence Externe"
//             $(".examenReferenceInputInterne").hide();
//             $(".examenReferenceInputExterne").show();
//             // Désactiver le champ "Examen de Référence Interne"
//             $("#examen_reference_select").prop("disabled", true);
//             // Réinitialiser le champ "Examen de Référence Interne"
//             $("#examen_reference_select").val('');

//             $("#examen_reference_input").prop("disabled", false);

//         } else if (typeExamenOption == "Immuno Interne") {
//             // Si "Immuno Interne" est sélectionné, masquer le champ "Examen de Référence Externe" et afficher le champ "Examen de Référence Interne"
//             $(".examenReferenceInputExterne").hide();
//             $(".examenReferenceInputInterne").show();
//             // Désactiver le champ "Examen de Référence Externe"
//             $("#examen_reference_input").prop("disabled", true);
//             // Réinitialiser le champ "Examen de Référence Externe"
//             $("#examen_reference_input").val('');

//             $("#examen_reference_select").prop("disabled", false);

//         } else {
//             // Si aucune des options spécifiques n'est sélectionnée, cacher les deux champs
//             $(".examenReferenceInputExterne").hide();
//             $(".examenReferenceInputInterne").hide();
//             // Activer les deux champs au cas où ils ont été désactivés
//             $("#examen_reference_select").prop("disabled", false);
//             $("#examen_reference_input").prop("disabled", false);
//             // Réinitialiser les deux champs
//             $("#examen_reference_select").val('');
//             $("#examen_reference_input").val('');
//         }




//     });


// });



$(document).ready(function() {
    // Fonction pour vérifier et mettre à jour les champs d'examen de référence au chargement de la page
    function updateExamenReferenceFields() {
        var typeExamenOption = $('#type_examen option:selected').text();


        // $(document).ready(function() {
        //     function updateExamenFields() {
        //         var typeExamenOption = $('#type_examen option:selected').text();

        //         if (typeExamenOption == "Immuno Externe") {
        //             // Hide the internal exam field and remove its required attribute
        //             $(".examenReferenceInputInterne").hide();
        //             $("#examen_reference_select").prop("required", false).val('').prop("disabled", true);

        //             // Show the external exam field and add the required attribute
        //             $(".examenReferenceInputExterne").show();
        //             $("#examen_reference_input").prop("required", true).prop("disabled", false);

        //         } else if (typeExamenOption == "Immuno Interne") {
        //             // Hide the external exam field and remove its required attribute
        //             $(".examenReferenceInputExterne").hide();
        //             $("#examen_reference_input").prop("required", false).val('').prop("disabled", true);

        //             // Show the internal exam field and add the required attribute
        //             $(".examenReferenceInputInterne").show();
        //             $("#examen_reference_select").prop("required", true).prop("disabled", false);

        //         } else {
        //             // Hide both fields and remove their required attributes
        //             $(".examenReferenceInputExterne, .examenReferenceInputInterne").hide();
        //             $("#examen_reference_input").prop("required", false).val('').prop("disabled", true);
        //             $("#examen_reference_select").prop("required", false).val('').prop("disabled", true);
        //         }
        //     }

        //     $('#type_examen').on('change', function(e) {
        //         updateExamenFields();
        //     });

        //     // Check the initial state on page load
        //     updateExamenFields();
        // });







        // if (typeExamenOption == "Immuno Externe") {
        //     // Si "Immuno Externe" est sélectionné, masquer le champ "Examen de Référence Interne" et afficher le champ "Examen de Référence Externe"
        //     $(".examenReferenceInputInterne").hide();
        //     $(".examenReferenceInputExterne").show();
        //     // Désactiver le champ "Examen de Référence Interne"
        //     $("#examen_reference_select").prop("required", false);
        //     // Réinitialiser le champ "Examen de Référence Interne"
        //     $("#examen_reference_select").val('');

        //     $("#examen_reference_input").prop("required", true);

        // } else if (typeExamenOption == "Immuno Interne") {
        //     // Si "Immuno Interne" est sélectionné, masquer le champ "Examen de Référence Externe" et afficher le champ "Examen de Référence Interne"
        //     $(".examenReferenceInputExterne").hide();
        //     $(".examenReferenceInputInterne").show();
        //     // Désactiver le champ "Examen de Référence Externe"
        //     $("#examen_reference_input").prop("required", false);
        //     // Réinitialiser le champ "Examen de Référence Externe"
        //     $("#examen_reference_input").val('');

        //     $("#examen_reference_select").prop("required", true);

        // } else {
        //     // Si aucune des options spécifiques n'est sélectionnée, cacher les deux champs
        //     // $(".examenReferenceInputExterne").hide();
        //     // $(".examenReferenceInputInterne").hide();
        //     // // Activer les deux champs au cas où ils ont été désactivés
        //     // $("#examen_reference_select").prop("disabled", false);
        //     // $("#examen_reference_input").prop("disabled", false);
        //     // // Réinitialiser les deux champs
        //     // $("#examen_reference_select").val('');
        //     // $("#examen_reference_input").val('');
        //     $(".examenReferenceInputExterne, .examenReferenceInputInterne").hide();
        //     $("#examen_reference_input").prop("required", false).val('');
        //     $("#examen_reference_select").prop("required", false).val('');
        // }
    }

    // Appeler la fonction lors du chargement initial de la page
    updateExamenReferenceFields();

    // Ajouter un gestionnaire d'événement pour mettre à jour les champs d'examen de référence lorsque le type d'examen change
    $('#type_examen').on('change', function(e) {
        updateExamenReferenceFields();
    });
});


window.addEventListener("load", (event) => {
    var typeExamenOption = $('#type_examen option:selected').text();
    // alert(typeExamenOption);
    if (typeExamenOption == "Immuno Externe") {
        $(".examenReferenceSelect").hide();
        $(".examenReferenceInput").show();

    } else if (typeExamenOption == "Immuno Interne") {
        $(".examenReferenceSelect").hide();
        $(".examenReferenceInput").show();

    } else {
        $(".examenReferenceInput").hide();
        $(".examenReferenceSelect").hide();
    }

});