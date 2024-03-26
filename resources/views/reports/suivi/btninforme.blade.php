
@if($data->is_called == 0 || $data->is_called == null)

<button class="btn btn-danger" id="informe-{{ $data->id }}" data-id="{{ $data->id }}">Non</button>

@elseif($data->is_called == 1)

<button class="btn btn-success">Oui</button>

@endif



<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
<script>
    $(document).ready(function() {

        $('#informe-{{ $data->id }}').click(function(e) {
            e.preventDefault();

            // let data_id_report = $('#data_id_report').val();
            let data_id_report = $(this).data('id');
            let val_informe = 1;
    // Effectuez la requête POST avec les données récupérées
    $.ajax({
        url: "{{ route('report.UpdateInformePatient') }}"
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
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
                });
                Toast.fire({
                icon: "success",
                title: "Opération réussie avec succès"
                });

        }
        , error: function(response) {
                    console.log(response)

                    const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                    });
                    Toast.fire({
                    icon: "error",
                    title: "Opération échouée"
                    });
                }
            });

            setInterval(function() {
location.reload();
}, 1000); 

        });

    });
</script>