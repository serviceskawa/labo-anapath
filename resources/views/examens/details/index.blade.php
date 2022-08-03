@extends('layouts.app2')

@section('content')
    <div class="">


        @include('layouts.alerts')


        {{-- @include('examens.details.create') --}}



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

                <form method="POST" id="addDetailForm" autocomplete="off">
                    @csrf
                    <div class="row d-flex align-items-end">
                        <div class="col-md-4 col-12">
                            <input type="hidden" name="test_order_id" id="test_order_id" value="{{ $test_order->id }}"
                                class="form-control">

                            <div class="mb-3">
                                <label for="example-select" class="form-label">Test</label>
                                <select class="form-select" id="test_id" name="test_id" required onchange="getTest()">
                                    <option>...</option>
                                    @foreach ($tests as $test)
                                        <option value="{{ $test->id }}">{{ $test->name }}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">

                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Prix</label>
                                <input type="text" name="price" id="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3 col-12">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Remise</label>
                                <input type="text" name="remise" id="remise" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" id="add_detail">Ajouter</button>

                            </div>
                        </div>
                    </div>

                </form>


                <h5 class="card-title mb-0">Détails de la demande d'examen</h5>



                <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Libellé</th>
                                <th>Prix</th>
                                <th>Remise</th>
                                <th>Montant Contrat</th>
                                <th>Montant Patient</th>
                                <th>Montant Total</th>
                                <th>Actions</th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($details as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->lib_test }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->remise }}</td>
                                    <td>{{ $item->montant_contrat }}</td>
                                    <td>{{ $item->montant_patient }}</td>
                                    <td class="amount_total">{{ $item->montant_total }}</td>
                                    <td>
                                        <button type="button" onclick="edit({{ $item->id }})"
                                            class="btn btn-primary"><i class="mdi mdi-lead-pencil"></i> </button>
                                        <button type="button" onclick="deleteModal({{ $item->id }})"
                                            class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>
                                    </td>

                                </tr>
                            @endforeach




                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="1" class="text-right">
                                    <strong>Total:</strong>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td id="val">
                                    <input type="number" id="estimated_ammount" class="estimated_ammount" value="0"
                                        readonly>
                                </td>
                                <td></td>

                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div> <!-- end card-->





    </div>
@endsection


@push('extra-js')
    <script type="text/javascript">
        var sum_total_data = 0;

        $("tr .amount_total").each(function(index, value) {
            getEachRow = parseFloat($(this).text());
            sum_total_data += getEachRow
        });

        document.getElementById('val').innerHTML = sum_total_data;

        $('#addDetailForm').on('submit', function(e) {
            e.preventDefault();
            let test_order_id = $('#test_order_id').val();
            let test_id = $('#test_id').val();
            let price = $('#price').val();
            let remise = $('#remise').val();
            console.log(test_order_id);

            $.ajax({
                url: "{{ route('details_test_order.store') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    test_order_id: test_order_id,
                    test_id: test_id,
                    price: price,
                    remise: remise

                },
                success: function(response) {
                    console.log(response)
                    $('#addDetailForm').trigger("reset")
                    total_ammount_price();
                    updateSubTotal();
                },
                error: function(response) {
                    console.log(response)
                },
            });

        })

        function updateSubTotal() {
            var table = document.getElementById("datatable1");
            let subTotal = Array.from(table.rows).slice(6).reduce((total, row) => {
                return total + parseFloat(row.cells[6].innerHTML);
            }, 0);
            document.getElementById("val").innerHTML = "SubTotal = $" + subTotal;
        }

        function total_ammount_price() {
            var sum = 0;
            $('.amount_total').each(function() {
                var value = $(this).val();
                if (value.length != 0) {
                    sum += parseFloat(value);
                }
                console.log(value);

            });
            $('#estimated_ammount').val(sum);
        }
    </script>
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


        function getTest() {
            var test_id = $('#test_id').val();

            $.ajax({
                type: "GET",
                url: "{{ url('gettest') }}" + '/' + test_id,
                success: function(data) {


                    $('#price').val(data.price);

                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>
@endpush
