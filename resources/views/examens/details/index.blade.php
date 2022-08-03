@extends('layouts.app2')

@section('content')

<div class="">
    @include('layouts.alerts')


        @include('examens.details.create')

        <div class="card my-3">
            <div class="card-header">
              Demande d'examen
            </div>
            <div class="card-body">


                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="simpleinput" class="form-label">Patient</label>
                        <input type="text" name="name" value="{{ $test_order->getPatient()->name }}" class="form-control" readonly>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="simpleinput" class="form-label">Médecin traitant</label>
                        <input type="text" name="name" value="{{ $test_order->getDoctor()->name }}" class="form-control" readonly>
                    </div>

                    <input id="contrat_id" type="hidden" value="{{ $test_order->getContrat()->id }}">
                </div>


                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Référence hôpital</label>
                        <input class="form-control" name="reference_hopital" value="{{ $test_order->reference_hopital }}" readonly>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="simpleinput" class="form-label">Hôpital de provenance</label>
                        <input type="text" name="name" value="{{ $test_order->getHospital()->name }}" class="form-control" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-group">
                        <label for="simpleinput" class="form-label">Contrat</label>
                        <input type="text" name="name" class="form-control" value="{{ $test_order->getContrat()->name }}" readonly>
                    </div>
                </div>
            </div>
        </div>



        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="card-widgets">
                    <div class="page-title-right mr-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#standard-modal">Ajouter un examen</button>
                    </div>
                </div>
                <h5 class="card-title mb-0">Liste des examens</h5>
                <div id="cardCollpase1" class="collapse pt-3 show">

                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Examen</th>
                                <th>Prix</th>
                                <th>Remise</th>
                                <th>Montant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->test_name }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->discount }}</td>
                                <td>{{ $item->total}}</td>
                                <td>
                                    <button type="button" onclick="edit({{$item->id}})" class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                    <button type="button" onclick="deleteModal({{$item->id}})" class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                </td>

                            </tr>
                            @endforeach




                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end card-->





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


function getTest(){
    var test_id = $('#test_id').val();

    $.ajax({
        type: "GET",
        url: "{{url('gettest')}}" + '/' + test_id,
        success: function (data) {

            $('#price').val(data.price);

        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
    getRemise();
}

function getRemise(){
    let element = document.getElementById("test_id");
    let category_test_id = element.options[element.selectedIndex].getAttribute("data-category_test_id");
    alert("Price: " + category_test_id);

    var contrat_id = $('#contrat_id').val();
    //var category_test_id = element.getAttribute('data-content');

    $.ajax({
        type: "GET",
        url: "{{url('gettestremise')}}" + '/' + contrat_id + '/' + category_test_id,
        success: function (data) {
           var discount =  $('#price').val() * data / 100;
            $('#discount').val(discount );

            var total =  $('#price').val() - discount;
            $('#total').val(total);
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

</script>

@endpush
