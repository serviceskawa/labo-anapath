## Etapes

# Composer install

# Composer dump-autoload

# php artisan key:generate

# php artisan migrate

# php artisan db:seed pour tout les seeders ou php artisan db:seed --class=DbSeeder pour un seeder specific

# /login mdp: P@ssw0rd et email: admin@admin.com

# faire les configurations dans settings

### A mettre dans chaque fonction du controller pour activer la fonctionnalité rôle et permission

# php artisan db:seed

# view-roles est le slug disponible dans la table permissions

if (!getOnlineUser()->can('view-roles')) {
return back()->with('error', "Vous n'êtes pas autorisé");
}

### Correction de 05/12/22 après deploiement

## hospitals

Sauf le champs name est obligatoire

### Sauvegarde d'ajout de detail de demande d'examen

$('#addDetailForm').on('submit', function(e) {
e.preventDefault();
let test_order_id = $('#test_order_id').val();
let test_id = $('#test_id').val();
let price = $('#price').val();
let remise = $('#remise').val();
let total = $('#total').val();

            $.ajax({
                url: "{{ route('details_test_order.store') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
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

            $.ajax({
                type: "GET",
                url: "{{ url('gettest') }}" + '/' + test_id,
                success: function(data) {

                    $('#price').val(data.price);
                    getRemise();

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
            // getRemise();
        }

        function getRemise() {
            let element = document.getElementById("test_id");
            let category_test_id = element.options[element.selectedIndex].getAttribute("data-category_test_id");
            // alert("Price: " + category_test_id);

            var contrat_id = $('#contrat_id').val();
            //var category_test_id = element.getAttribute('data-content');

            $.ajax({
                type: "GET",
                url: "{{ url('gettestremise') }}" + '/' + contrat_id + '/' + category_test_id,
                success: function(data) {
                    var discount = $('#price').val() * data / 100;
                    $('#remise').val(discount);

                    var total = $('#price').val() - discount;
                    $('#total').val(total);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        // $('#finalisationBtn').on('click', function() {
        //     let test_order_id = $('#test_order_id').val()
        //     console.log(test_order_id)
        //     Swal.fire({
        //         title: "Avez-vous fini ?",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonText: "Oui ",
        //         cancelButtonText: "Non !",
        //     }).then(function(result) {
        //         if (result.value) {
        //             // window.location.href = "{{ url('contrats_details/delete') }}" + "/" + id;
        //             $.ajax({
        //                 url: "/test_order/updatestatus",
        //                 type: "post",
        //                 data: {
        //                     "_token": "{{ csrf_token() }}",
        //                     test_order_id: test_order_id.id,
        //                 },
        //                 success: function(response) {

        //                     console.log(response);
        //                     Swal.fire(
        //                         "Suppression !",
        //                         "En cours de traitement ...",
        //                         "success"
        //                     )

        //                 },
        //                 error: function(response) {
        //                     console.log(response);
        //                 },
        //             });

        //         }
        //     });
        // });
