@extends('layouts.app2')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3 mb-1">
                <a href="{{ route('contrats.index') }}" type="button" class="btn btn-primary" >Retour à la liste des contrats</a>
            </div>
            <h4 class="page-title"></h4>
        </div>

        <!----MODAL---->

         @include('contrats_details.create')

         @include('contrats_details.edit')
        
    </div>
</div>     


<div class="">


    @include('layouts.alerts')

    <div class="card mt-3">
        <h5 class="card-header">Contrat : {{ $contrat->name }}</h5>
        <div class="card-body">

            <div class="mb-3">
                <label  class="form-label">Libellé</label>
                <input type="text" class="form-control" name="exampleFormControlInput1" readonly value="{{ $contrat->name }}" >
            </div>

            <div class="mb-3">
                <label  class="form-label">Type</label>
                <input type="text" class="form-control" name="exampleFormControlInput1" value="{{ $contrat->type }}" readonly>
            </div>

            <div class="mb-3">
                <label  class="form-label">Email</label>
                <input type="text" class="form-control" name="exampleFormControlInput1" value="{{ $contrat->description }}" readonly>
            </div>

         

        </div>
    </div>

    <div class="card mb-md-0 mb-3">

        
        <div class="card-body">
            <div class="card-widgets">
                <button type="button" class="btn btn-warning float-left" data-bs-toggle="modal" data-bs-target="#modal2">Ajouter</button>
            </div>
            <h5 class="card-title mb-0">Détails du contrat </h5>
           
                            
            <div id="cardCollpase1" class="collapse pt-3 show">
                

                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                       
                            <th>Descriptions</th>
                            <th>Pourcentage</th>
                          

                            <th>Actions</th>
                          
                        </tr>
                    </thead>
                
                
                    <tbody>

                        @foreach ($details as $item)
                        <tr>
                     
                            <td>{{ $item->categorytest()->name }}</td>
                            <td>{{ $item->pourcentage.' %' }}</td>
                           
                            <td>
                                {{-- <button type="button" onclick="edit({{$item->id}})" class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                <button type="button" onclick="deleteModal({{$item->id}})" class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button> --}}
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

</script>

@endpush
