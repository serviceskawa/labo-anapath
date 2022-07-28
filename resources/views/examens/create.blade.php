@extends('layouts.app2')

@section('content')

 


<div class="">


    @include('layouts.alerts')

  

    

        
        <div class="card my-3">
            <div class="card-header">
              Création d'une demande d'examen
            </div>
            <div class="card-body">
             
            <form action="{{ route('test_order.store') }}" method="post" autocomplete="off">
                @csrf
                <div class="row mb-3">

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Contrat</label>
                        <select class="form-select" aria-label="Default select example" required name="contrat_id">
                            <option label="Choisir.."></option>
                            @foreach ($contrats as $contrat)
                                <option value="{{ $contrat->id }}">{{ $contrat->name }}</option>
                            @endforeach
                          </select>
                    </div>


                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Patient</label>
                        <select class="form-select" aria-label="Default select example" required name="patient_id">
                            <option label="Choisir.."></option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                            @endforeach
                          </select>
                    </div>

                    
                </div>


                <div class="row mb-3">

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Hopital</label>
                        <select class="form-select" aria-label="Default select example" required name="hospital_id">
                            <option label="Choisir.."></option>
                            @foreach ($hopitals as $hopital)
                                <option value="{{ $hopital->id }}">{{ $hopital->name }}</option>
                            @endforeach
                          </select>
                    </div>


                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Doctor</label>
                        <select class="form-select" aria-label="Default select example" required name="doctor_id">
                            <option label="Choisir.."></option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                          </select>
                    </div>

                    
                </div>

                

                <div class="mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="form-label">Reference Hopital </label>
                        <textarea class="form-control" rows="5" name="reference_hopital"></textarea>
                      </div>
                </div>


                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
                

            </form>
            </div>
          </div>




    
</div>
@endsection


@push('extra-js')
<script>

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
            window.location.href="{{url('contrats_details/delete')}}"+"/"+id;
            Swal.fire(
                "Suppression !",
                "En cours de traitement ...",
                "success"
            )
        }
    });
}





//EDITION
function edit(id){
    var e_id = id;

    // Populate Data in Edit Modal Form
    $.ajax({
        type: "GET",
        url: "{{url('getcontratdetails')}}" + '/' + e_id,
        success: function (data) {
          
            $('#category_test_id2').val(data.category_test_id).change();
            $('#pourcentage2').val(data.pourcentage);
            $('#contrat_id2').val(data.contrat_id);
            $('#contrat_details_id2').val(data.id);
            
        

            console.log(data);
            $('#editModal').modal('show'); 
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

</script>

@endpush
