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
{{-- 
         @include('contrats.create')

         @include('doctors.edit')
         --}}
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
                <a href="javascript:;" data-bs-toggle="reload"><i class="mdi mdi-refresh"></i></a>
                <a data-bs-toggle="collapse" href="#cardCollpase1" role="button" aria-expanded="false" aria-controls="cardCollpase1"><i class="mdi mdi-minus"></i></a>
                <a href="#" data-bs-toggle="remove"><i class="mdi mdi-close"></i></a>
            </div>
            <h5 class="card-title mb-0">Détails du contrat </h5>
                            
            <div id="cardCollpase1" class="collapse pt-3 show">
                

                <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descriptions</th>
                            <th>Popurcentage</th>
                          

                            <th>Actions</th>
                          
                        </tr>
                    </thead>
                
                
                    <tbody>

                        @foreach ($details as $item)
                        <tr>
                     
                            <td>{{ $item->category_test_id }}</td>
                            <td>{{ $item->pourcentage }}</td>
                           
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
            window.location.href="{{url('doctors/delete')}}"+"/"+id;
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
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            {
                "targets": [ 0 ],
                "searchable": false
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
        url: "{{url('getdoctor')}}" + '/' + e_id,
        success: function (data) {
          
            $('#id2').val(data.id);
            $('#name').val(data.name);
            $('#telephone').val(data.telephone);
            $('#email').val(data.email);
            $('#role').val(data.role);
            $('#commission').val(data.commission);
            
        

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
