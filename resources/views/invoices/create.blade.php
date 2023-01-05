@extends('layouts.app2')

@section('title', 'Créer un facture')

@section('content')
<div class="">

    @include('layouts.alerts')

    <div class="card my-3">

        <div class="card-body">

            <form action="{{route('invoice.store')}} " method="post" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Demande d'examen<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="test_orders_id"
                            id="test_orders_id" required>
                            <option>Sélectionner une demande d'examen</option>
                            @foreach ($testOrders as $testOrder)
                            <option value="{{ $testOrder->id }}">{{ $testOrder->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date <span style="color:red;">*</span></label>
                        <input type="date" class="form-control" name="invoice_date" id="invoice_date"
                            data-date-format="dd/mm/yyyy" required>
                    </div>

                </div>

        </div>


        <div class="modal-footer">
            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Créer une nouvelle facture</button>
        </div>


        </form>
    </div>
</div>

</div>
@endsection


@push('extra-js')
<script>
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
                window.location.href = "{{ url('contrats_details/delete') }}" + "/" + id;
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
            url: "{{ url('getcontratdetails') }}" + '/' + e_id,
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

    $(document).ready(function(){

        $('#doctor_id').select2({
            placeholder:'Select Category',
            theme:'bootstrap4',
            tags:true,
        }).on('select2:close', function(){
            var element = $(this);
            var new_category = $.trim(element.val());

            if(new_category != '')
            {
                $.ajax({
                  url:"{{route('doctors.storeDoctor')}}",
                  method:"POST",
                  data:{
                    "_token": "{{ csrf_token() }}",
                    name:new_category
                },
                  success:function(data)
                  {

                    if(data.status === "created")
                    {
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                        element.append('<option value="'+data.id+'">'+data.name+'</option>').val(new_category);
                    }
                  }
                })
            }

        });
      
        // Create hopital
        $('#hospital_id').select2({
            placeholder:'Choisissez un hopital',
            theme:'bootstrap4',
            tags:true,
        }).on('select2:close', function(){
            var element = $(this);
            var new_category = $.trim(element.val());

            if(new_category != '')
            {
                $.ajax({
                  url:"{{route('hopitals.storeHospital')}}",
                  method:"POST",
                  data:{
                    "_token": "{{ csrf_token() }}",
                    name:new_category
                },
                  success:function(data)
                  {

                    if(data.status === "created")
                    {
                        toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');

                        element.append('<option value="'+data.id+'">'+data.name+'</option>').val(new_category);
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
            let telephone1 = $('#telephone1').val();
            let genre = $('#genre').val();
            // alert(firstname);
            $.ajax({
                url: "{{ route('patients.storePatient') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    code: code,
                    lastname: lastname,
                    firstname: firstname,
                    age:age,
                    telephone1:telephone1,
                    genre:genre
                },
                success: function(data) {
                    
                    $('#createPatientForm').trigger("reset")
                    $('#standard-modal').modal('hide');
                    toastr.success("Donnée ajoutée avec succès", 'Ajout réussi');
                    $('#patient_id').append('<option value="'+data.id+'">'+data.code+' - '+data.firstname+' '+data.lastname+'</option>').trigger('change').val(data.id);
                    
                },
                error: function(data) {
                    console.log(data)
                },
                // processData: false,
            });

        });
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $('#type_examen').on('change', function(e) {
            var typeExamenOption = $('#type_examen option:selected').text();

            if (typeExamenOption == "Immuno Externe") {
                $(".examenReferenceSelect").hide();
                $(".examenReferenceInput").show();

            } else if (typeExamenOption == "Immuno Interne") {
                $(".examenReferenceSelect").show();
                $(".examenReferenceInput").hide();
                $( "#examen_reference_select" ).select2({
                    ajax: { 
                    url: "{{route('test_order.get_all_test_order')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                        results: response
                        };
                    },
                    cache: true
                    }
            
                });
            }else {
                $(".examenReferenceInput").hide();
                $(".examenReferenceSelect").hide();
            }
           
        });
    });
</script>
@endpush