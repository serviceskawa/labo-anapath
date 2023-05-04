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
                window.location.href= baseUrl + "/hopitals/delete/" + id;
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
    function edit(id){
        var e_id = id;
    
        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: baseUrl + "/gethopital/" + e_id,
            success: function (data) {
    
                $('#id2').val(data.id);
                $('#name2').val(data.name);
                $('#telephone2').val(data.telephone);
                $('#email2').val(data.email);
                $('#adresse2').val(data.adresse);
                $('#commission2').val(data.commission);
    
    
    
                console.log(data);
                $('#editModal').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }