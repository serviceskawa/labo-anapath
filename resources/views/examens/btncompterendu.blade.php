<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- Inclure la bibliothèque Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>



@if(!empty($data->invoice->test_order_id) && $data->invoice->paid == 1)
    <a  target="_blank" rel="noopener noreferrer" class="btn btn-secondary" href="{{route('report.pdf', $data->report->id)}}" title="Imprimer compte rendu"><i class="mdi mdi-printer"></i> </a>
 @elseif(!empty($data->invoice->test_order_id) && $data->invoice->paid == 0)
    <a  class="btn btn-secondary" id="showAlertButton" onclick="fenetre()" title="Imprimer compte rendu"><i class="mdi mdi-printer"></i> </a>
@endif



{{-- <a href="#" id="myLink">Cliquer ici</a> --}}

<script>

        function fenetre(){
            event.preventDefault();

        // Afficher le Sweet Alert
        Swal.fire({
  icon: "warning",
  title: "Attention",
  text: "Impossible d'imprimer le compte rendu, la facture n'est pas payée",
});
        }

</script>

