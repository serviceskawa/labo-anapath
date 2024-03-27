@if($data->is_delivered == 0)

@if ($data->status == 1)
<button class="btn btn-danger" id="delivery-{{ $data->id }}" data-id="{{ $data->id }}" data-code={{ $data->order->code }}>Non</button>
@else
<button class="btn btn-danger" disabled>Non</button>
@endif

@elseif($data->is_delivered == 1)

<button class="btn btn-success">Oui</button>

@endif




<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
<script>
    $(document).ready(function() {

        $('#delivery-{{ $data->id }}').click(function(e) {
            e.preventDefault();

            
            let reportTestOrderCode = $(this).data('code');
            let data_id_report = $(this).data('id');
            let val_informe = 1;





            // Afficher une boîte de dialogue de confirmation
            Swal.fire({
                title: 'Êtes-vous sûr?'
                , html: "<span style='font-size: 20px;'>Demande d'examen : <b>" + reportTestOrderCode + "</b> <br> Opération : <b>Patient livré => OUI </b></span><br><br> Voulez-vous vraiment effectuer cette opération ?"
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Oui, continuer'
                , cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {




                    // Effectuez la requête POST avec les données récupérées
                    $.ajax({
                        url: "{{ route('report.UpdateLivrePatient') }}"
                        , type: "POST"
                        , data: {
                            "_token": "{{ csrf_token() }}"
                            , data_id_report: data_id_report
                            , val_informe: val_informe
                        }
                        , success: function(response) {
                            // button.disabled = false;
                            console.log(response);
                            // window.location.href = "/invoice-tdl/" + response.id;
                            const Toast = Swal.mixin({
                                toast: true
                                , position: "top-end"
                                , showConfirmButton: false
                                , timer: 3000
                                , timerProgressBar: true
                                , didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "success"
                                , title: "Opération réussie avec succès"
                            });

                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        }
                        , error: function(response) {
                            console.log(response)

                            const Toast = Swal.mixin({
                                toast: true
                                , position: "top-end"
                                , showConfirmButton: false
                                , timer: 3000
                                , timerProgressBar: true
                                , didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: "error"
                                , title: "Opération échouée"
                            });
                        }




                    });


                }




            });



            // setInterval(function() {
            //     location.reload();
            // }, 3000);

        });

    });

</script>
