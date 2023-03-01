@extends('layouts.app2')

@section('title', 'Details2')

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
                        <label for="simpleinput" class="form-label">Contrat</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ $test_order->getContrat()->name }}" readonly>
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="simpleinput" class="form-label">Patient</label>
                        <input type="text" name="name" value="{{ $test_order->getPatient()->name }}"
                            class="form-control" readonly>
                    </div>



                </div>


                <div class="row">


                    <div class="mb-3 col-md-6">
                        <label for="simpleinput" class="form-label">Doctor</label>
                        <input type="text" name="name" value="{{ $test_order->getDoctor()->name }}"
                            class="form-control" readonly>
                    </div>


                    <div class="mb-3 col-md-6">
                        <label for="simpleinput" class="form-label">Hopital</label>
                        <input type="text" name="name" value="{{ $test_order->getHospital()->name }}"
                            class="form-control" readonly>
                    </div>


                </div>


                <div class="mb-3">
                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="form-label">Reference Hopital </label>
                        <textarea class="form-control" readonly rows="5" name="reference_hopital">{{ $test_order->reference_hopital }}</textarea>
                    </div>
                </div>




            </div>
        </div>

        <div class="card mb-md-0 mb-3">
            <div class="card-body">
                <div class="card-widgets">
                    <div class="page-title-right mr-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#standard-modal">Ajouter un détail</button>
                    </div>
                </div>



                <h5 class="card-title mb-0">Détails de la demande d'examen</h5>



                <div id="cardCollpase1" class="collapse pt-3 show">
                    <div class="row d-flex align-items-end">
                        <div class="col-md-4 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="itemname">Libelé test</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="itemquantity">Remise</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="itemquantity">Montant Contrat</label>
                            </div>
                        </div>

                        <div class="col-md-2 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="staticprice">Montant Patient</label>
                            </div>
                        </div>
                        <div class="col-md-1 col-12">
                            <div class="mb-1">
                                <label class="form-label" for="staticprice">Total</label>
                            </div>
                        </div>

                        <div class="col-md-1 col-12 ">
                            <div class="mb-1">
                                <label class="form-label" for="staticprice">Actions</label>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="#" class="invoice-repeater" id="index_container">
                    <div class="nested-form">
                        <div data-repeater-list="invoice">
                            <div data-repeater-item>
                                <div class="row d-flex align-items-end">
                                    <div class="col-md-4 col-12">
                                        <div class="mb-1">
                                            <select class="form-select" id="test_id" name="test_id[]" required>
                                                <option>...</option>
                                                @foreach ($tests as $test)
                                                    <option value="{{ $test->id }}">{{ $test->name }}
                                                        {{ $test->price }}</option>
                                                @endforeach


                                            </select>
                                            <input type="hidden" name="test_order_id" value="{{ $test_order->id }}"
                                                class="form-control">

                                            {{-- <input type="text" class="form-control" id="itemname"
                                                aria-describedby="itemname" placeholder="Ajouter un test" /> --}}
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <input type="number" class="form-control" id="itemquantity"
                                                aria-describedby="itemquantity" placeholder="1" />
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <input type="number" class="form-control" id="itemquantity"
                                                aria-describedby="itemquantity" placeholder="1" />
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-12">
                                        <div class="mb-1">
                                            <input type="text" readonly class="form-control-plaintext"
                                                id="staticprice" value="$32" />
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-12">
                                        <div class="mb-1">
                                            <input type="text" readonly class="form-control-plaintext"
                                                id="staticprice" value="$32" />
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-12 mb-50">
                                        <div class="mb-1">
                                            <button class="btn btn-outline-danger text-nowrap px-1" data-repeater-delete
                                                type="button">
                                                <i data-feather="x" class="me-25"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-icon btn-primary" type="button" id="index_add" data-repeater-create>
                                <i data-feather="plus" class="me-25"></i>
                                <span>Add New</span>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div> <!-- end card-->
    </div>
@endsection


@push('extra-js')
    <script src="{{ asset('/adminassets/js/forms/repeater/jquery-nested-form.js') }}"></script>
    <script src="{{ asset('/adminassets/js/forms/repeater/jquery.repeater.min.js') }}"></script>
    {{-- <script src="{{ asset('/adminassets/js/scripts/form-repeater.js') }}"></script> --}}

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

        $("#index_container").nestedForm({
            forms: ".nested-form",

            adder: "#index_add",

            increment: 1,

            startIndex: 0,

            maxIndex: 15,

            onBuildForm: function($elem, index) {
                // $elem.find('input[type="number"]').val(index);
                $elem.find('input[id="itemcost"]').val(index);

                $elem.find('input[id="itemcost"]').each(function(i, input) {
                    // $(input).attr("id") = $(input).attr("id") + index;
                    document.getElementById("itemcost").id = "itemcost" + index;
                    console.log("a");
                });
            },
        });

        function getTest() {
            var test_id = $('#test_id').val();

            $.ajax({
                type: "GET",
                url: "{{ url('gettest') }}" + '/' + test_id,
                success: function(data) {


                    $('#itemcost').val(data.price);

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>
@endpush
@extends('layouts.app2')
<tbody>

    @foreach ($details as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->test_name }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->discount }}</td>
            <td class="amount_total">{{ $item->total }}</td>
            <td>
                <button type="button" onclick="edit({{ $item->id }})" class="btn btn-primary"><i
                        class="mdi mdi-lead-pencil"></i> </button>
                <button type="button" onclick="deleteModal({{ $item->id }})" class="btn btn-danger"><i
                        class="mdi mdi-trash-can-outline"></i> </button>
            </td>

        </tr>
    @endforeach




</tbody>
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
    // function edit(id) {
    //     var e_id = id;

    //     // Populate Data in Edit Modal Form
    //     $.ajax({
    //             type: "GET",
    //             url: "{{ url('getcontratdetails') }}" + '/' + e_id,
    //             success: function(data) {

    //                 $('#category_test_id2').val(data.category_test_id).change();
    //                 $('#pourcentage2').val(data.pourcentage);
    //                 $('#contrat_id2').val(data.contrat_id);
    //                 $('#contrat_details_id2').val(data.id);


    //                 // Populate Data in Edit Modal Form
    //                 $.ajax({
    //                     type: "GET",
    //                     url: "{{ url('getcontratdetails') }}" + '/' + e_id,
    //                     success: function(data) {

    //                         $('#category_test_id2').val(data.category_test_id).change();
    //                         $('#pourcentage2').val(data.pourcentage);
    //                         $('#contrat_id2').val(data.contrat_id);
    //                         $('#contrat_details_id2').val(data.id);



    //                         console.log(data);
    //                         $('#editModal').modal('show');
    //                     },
    //                     error: function(data) {
    //                         console.log('Error:', data);
    //                     }
    //                 });
    //             }


    //             function getTest() {
    //                 var test_id = $('#test_id').val();

    //                 $.ajax({
    //                     type: "GET",
    //                     url: "{{ url('gettest') }}" + '/' + test_id,
    //                     success: function(data) {

    //                         $('#price').val(data.price);

    //                     },
    //                     error: function(data) {
    //                         console.log('Error:', data);
    //                     }
    //                 });
    //                 getRemise();
    //             }

    //             function getRemise() {
    //                 let element = document.getElementById("test_id");
    //                 let category_test_id = element.options[element.selectedIndex].getAttribute(
    //                     "data-category_test_id");
    //                 alert("Price: " + category_test_id);

    //                 var contrat_id = $('#contrat_id').val();
    //                 //var category_test_id = element.getAttribute('data-content');

    //                 $.ajax({
    //                     type: "GET",
    //                     url: "{{ url('gettestremise') }}" + '/' + contrat_id + '/' + category_test_id,
    //                     success: function(data) {
    //                         var discount = $('#price').val() * data / 100;
    //                         $('#discount').val(discount);

    //                         var total = $('#price').val() - discount;
    //                         $('#total').val(total);
    //                     },
    //                     error: function(data) {
    //                         console.log('Error:', data);
    //                     }
    //                 });
    //             }


    //             $('#price').val(data.price);

    //         },
    //         error: function(data) {
    //             console.log('Error:', data);
    //         }
    //     });
    // }
</script>