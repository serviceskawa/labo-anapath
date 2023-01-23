@extends('layouts.app2')

@section('title', 'Créer un examen')

@section('content')
<div class="">

    @include('layouts.alerts')

    {{-- @include('patients.create') --}}

    <div class="card my-3">
        <div class="card-header">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right mt-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un nouveau patient</button>
                    </div>
                    Ajouter une nouvelle demande d'examen
                </div>


            </div>

        </div>
        <div class="card-body">

            <form action="{{ route('test_order.store') }}" method="post" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    {{-- <div class="alert alert-warning">
                        <strong>Attention! </strong><small>Si un élément ne s'affiche pas dans la liste déroulante,
                            ajoutez-le à la section correspondante et rechargez la page avant de procéder.</small>
                    </div> --}}

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Patient<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="patient_id" id="patient_id"
                            required>
                            <option>Sélectionner le nom du patient</option>
                            @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->code }} - {{ $patient->firstname }}
                                {{ $patient->lastname }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Médecin traitant<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="doctor_id" id="doctor_id"
                            required>
                            <option>Sélectionner le médecin traitant</option>
                            @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->name }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Hôpital de provenance<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="hospital_id" id="hospital_id"
                            required>
                            <option>Sélectionner le centre hospitalier de provenance</option>
                            @foreach ($hopitals as $hopital)
                            <option value="{{ $hopital->name }}">{{ $hopital->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleFormControlInput1" class="form-label">Référence hôpital</label>
                            <input type="text" class="form-control" name="reference_hopital"
                                placeholder="Le numéro de référence qui se trouve sur le bon d'examen du patient.">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Date prélèvement<span style="color:red;">*</span></label>
                        <input type="date" class="form-control" name="prelevement_date" id="prelevement_date"
                            data-date-format="dd/mm/yyyy" required>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="example-fileinput" class="form-label">Pièce jointe</label>
                            <input type="file" name="examen_file" id="example-fileinput" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Type d'examen<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" required id="type_examen"
                            name="type_examen">
                            <option>Sélectionner le type d'examen</option>
                            @forelse ($types_orders as $type)
                            <option value="{{ $type->id }}">{{ $type->title }}</option>
                            @empty
                            Ajouter un Type d'examen
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Contrat<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" required name="contrat_id">
                            <option>Sélectionner le contrat</option>
                            @forelse ($contrats as $contrat)
                            <option value="{{ $contrat->id }}">{{ $contrat->name }}</option>
                            @empty
                            Ajouter un contrat
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="col-md-12 mb-3">

                    <div class="examenReferenceSelect" style="display: none !important">
                        <label for="exampleFormControlInput1" class="form-label">Examen de Référence<span
                                style="color:red;">*</span></label>
                        <select class="form-select select2" data-toggle="select2" name="examen_reference_select"
                            id="examen_reference_select">
                            <option value="">Sélectionner dans la liste</option>

                        </select>
                    </div>
                    <div class="examenReferenceInput" style="display: none !important">
                        <label for="exampleFormControlInput1" class="form-label">Examen de Référence<span
                                style="color:red;">*</span></label>
                        <input type="text" name="examen_reference_input" class="form-control"
                            placeholder="Saisir l'examen de reference">
                    </div>

                </div>
                <div class="col-md-6">
                    <input type="checkbox" class="form-check-input" name="is_urgent" id="">
                    <label class="form-label">Cas urgent</label>
                </div>

        </div>


        <div class="modal-footer">
            <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-primary">Ajouter une nouvelle demande d'examen</button>
        </div>


        </form>
    </div>
</div>



{{-- Modal --}}
<div id="standard-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Ajouter un nouveau patient</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form method="POST" id="createPatientForm" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div style="text-align:right;"><span style="color:red;">*</span>champs obligatoires</div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Code</label>
                        <input type="text" name="code" id="code"
                            value="<?php echo substr(md5(rand(0, 1000000)), 0, 10); ?>" class="form-control" readonly>
                    </div>


                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Nom <span style="color:red;">*</span></label>
                        <input type="text" name="firstname" id="firstname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Prénom<span style="color:red;">*</span></label>
                        <input type="text" name="lastname" id="lastname" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="example-select" class="form-label">Genre<span style="color:red;">*</span></label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option value="">Sélectionner le genre</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="example-date" class="form-label">Date de naissance</label>
                        <input class="form-control" id="example-date" type="date" name="birthday">
                    </div>
                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Age<span style="color:red;">*</span></label>
                        <input type="number" name="age" id="age" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Profession</label>
                        <input type="text" name="profession" class="form-control">
                    </div>


                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact 1<span style="color:red;">*</span></label>
                        <input type="tel" name="telephone1" id="telephone1" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Contact 2</label>
                        <input type="tel" name="telephone2" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adresse<span style="color:red;">*</span></label>
                        <textarea type="text" name="adresse" class="form-control" required></textarea>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter un nouveau patient</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


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
                $(".examenReferenceSelect").hide();
                $(".examenReferenceInput").show();
                // $( "#examen_reference_select" ).select2({
                //     ajax: { 
                //     url: "{{route('test_order.get_all_test_order')}}",
                //     dataType: 'json',
                //     delay: 250,
                //     data: function (params) {
                //         return {
                //             _token: CSRF_TOKEN,
                //             search: params.term // search term
                //         };
                //     },
                //     processResults: function (response) {
                //         return {
                //         results: response
                //         };
                //     },
                //     cache: true
                //     }
            
                // });
            }else {
                $(".examenReferenceInput").hide();
                $(".examenReferenceSelect").hide();
            }
            // alert(typeExamenId);

            // $.ajax({
            //     url: "{{ route('template.report-getTemplate') }}",
            //     type: "POST",
            //     data: {
            //         "_token": "{{ csrf_token() }}",
            //         id: template_id,
            //     },
            //     success: function(data) {
            //         console.log(data);
            //         // $('#page_id').val()
            //         if (data) {
            //             $('#editor').val(data.content);

            //             ClassicEditor
            //                 .create(document.querySelector('#editor'))
            //                 .then(editor => {})
            //                 .catch(error => {
            //                     console.error(error);
            //                 });

            //             document.querySelector('.ck-editor__editable').ckeditorInstance
            //                 .destroy()

            //         } else {
            //             $('#editor').val("Texte");

            //             ClassicEditor
            //                 .create(document.querySelector('#editor'))
            //                 .then(editor => {})
            //                 .catch(error => {
            //                     console.error(error);
            //                 });

            //             document.querySelector('.ck-editor__editable').ckeditorInstance
            //                 .destroy()

            //         }

            //     },
            //     error: function(error) {
            //         $('#editor').val(report.description);

            //         ClassicEditor
            //             .create(document.querySelector('#editor'))
            //             .then(editor => {})
            //             .catch(error => {
            //                 console.error(error);
            //             });

            //         document.querySelector('.ck-editor__editable').ckeditorInstance
            //             .destroy()

            //     }
            // })
        });
    });
</script>
@endpush