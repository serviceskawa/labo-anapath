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
                        <label for="simpleinput" class="form-label">Patient</label>
                        <input type="text" name="name" value="{{ $test_order->getPatient()->name }}"
                            class="form-control" readonly>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="simpleinput" class="form-label">Médecin traitant</label>
                        <input type="text" name="name" value="{{ $test_order->getDoctor()->name }}"
                            class="form-control" readonly>
                    </div>

                    <input id="contrat_id" type="hidden" value="{{ $test_order->getContrat()->id }}">
                </div>


                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="exampleFormControlInput1" class="form-label">Référence hôpital</label>
                        <input class="form-control" name="reference_hopital" value="{{ $test_order->reference_hopital }}"
                            readonly>
                    </div>

                    <div class="mb-3 col-md-6">
                        <label for="simpleinput" class="form-label">Hôpital de provenance</label>
                        <input type="text" name="name" value="{{ $test_order->getHospital()->name }}"
                            class="form-control" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-group">
                        <label for="simpleinput" class="form-label">Contrat</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ $test_order->getContrat()->name }}" readonly>
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
                                        <option data-category_test_id="{{ $test->category_test_id }}"
                                            value="{{ $test->id }}">{{ $test->name }}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">

                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Prix</label>
                                <input type="text" name="price" id="price" class="form-control" required readonly>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Remise</label>
                                <input type="text" name="remise" id="remise" class="form-control" required readonly>
                            </div>
                        </div>
                        <div class="col-md-2 col-12">
                            <div class="mb-3">
                                <label for="example-select" class="form-label">Total</label>

                                <input type="text" name="total" id="total" class="form-control" required readonly>
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


                    <table id="datatable1" class="table detail-list-table table-striped dt-responsive nowrap w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Examen</th>
                                <th>Prix</th>
                                <th>Remise</th>
                                <th>Montant</th>
                                <th>Actions</th>

                            </tr>
                        </thead>


                        <tfoot>
                            <tr>
                                <td colspan="1" class="text-right">
                                    <strong>Total:</strong>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td id="val">
                                    <input type="number" id="estimated_ammount" class="estimated_ammount"
                                        value="0" readonly>
                                </td>
                                <td></td>

                            </tr>
                        </tfoot>
                    </table>
                    <div class="row">
                        <div class="col-9">
                            <button type="submit" id="finalisationBtn" class="btn btn-info">Cliquez pour
                                cloturer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-->





    </div>
@endsection


@push('extra-js')
    <script></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var test_order = {!! json_encode($test_order) !!}
            console.log(test_order)

            var dtDetailTable = $('.detail-list-table')

            var dt_basic = $('#datatable1').DataTable({
                ajax: {
                    url: '/test_order/detailstest/' + test_order.id,
                    dataSrc: ''
                },
                deferRender: true,
                columns: [
                    // columns according to JSON
                    {
                        data: 'id'
                    },
                    {
                        data: 'test_name'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'discount'
                    },
                    {
                        data: 'total'
                    },
                    {
                        data: null
                    }
                ],
                columnDefs: [{
                    "targets": -1,
                    "render": function(data, type, row) {
                        return (
                            '<button type="button" id="deleteBtn" class="btn btn-danger"><i class="mdi mdi-trash-can-outline"></i> </button>'
                        );
                    }

                }],

                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(4)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    // 
                    discount = api
                        .column(3)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    subTotal = api
                        .column(2)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Total over this page
                    pageTotal = api
                        .column(4, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(4).footer()).html(total);
                    $(api.column(3).footer()).html(discount);
                    $(api.column(2).footer()).html(subTotal);

                    sendTotal(test_order.id, total, discount, subTotal);

                    // if ($(api.column(4).footer()).html(total)) {
                    //     // console.log('footer');
                    // }

                },

            });

            setInterval(function() {
                dt_basic.ajax.reload();
            }, 3000);

            // 
            $('.detail-list-table tbody').on('click', '#deleteBtn', function() {
                var data = dt_basic.row($(this).parents('tr')).data();
                var id = $(this).data('id');
                console.log(data)
                Swal.fire({
                    title: "Voulez-vous supprimer l'élément ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Oui ",
                    cancelButtonText: "Non !",
                }).then(function(result) {
                    if (result.value) {
                        // window.location.href = "{{ url('contrats_details/delete') }}" + "/" + id;
                        $.ajax({
                            url: "/test_order/detailsdelete",
                            type: "post",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id: data.id,
                            },
                            success: function(response) {

                                console.log(response);
                                Swal.fire(
                                    "Suppression !",
                                    "En cours de traitement ...",
                                    "success"
                                )
                                dt_basic.ajax.reload()
                                // window.location.reload();
                            },
                            error: function(response) {
                                console.log(response);
                                dt_basic.ajax.reload()

                                // Command: toastr["error"]("Error")
                            },
                        });

                    }
                });
            });

            function sendTotal(test_order_id, total, discount, subTotal) {
                console.log(test_order_id, total, discount, subTotal);

                $.ajax({
                    url: "{{ route('test_order.updateorder') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        test_order_id: test_order_id,
                        discount: discount,
                        subTotal: subTotal,
                        total: total

                    },
                    success: function(response) {
                        console.log(response)

                    },
                    error: function(response) {
                        console.log(response)
                    },
                });
            }
        });

        $('#addDetailForm').on('submit', function(e) {
            e.preventDefault();
            let test_order_id = $('#test_order_id').val();
            let test_id = $('#test_id').val();
            let price = $('#price').val();
            let remise = $('#remise').val();
            let total = $('#total').val();
            console.log(test_order_id);

            $.ajax({
                url: "{{ route('details_test_order.store') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    test_order_id: test_order_id,
                    test_id: test_id,
                    price: price,
                    discount: remise,
                    total: total

                },
                success: function(response) {
                    console.log(response)
                    $('#addDetailForm').trigger("reset")
                    updateSubTotal();
                },
                error: function(response) {
                    console.log(response)
                },
            });

        });

        function updateSubTotal() {
            var table = document.getElementById("datatable1");
            let subTotal = Array.from(table.rows).slice(6).reduce((total, row) => {
                return total + parseFloat(row.cells[6].innerHTML);
            }, 0);
            document.getElementById("val").innerHTML = "SubTotal = $" + subTotal;
        };

        // function getTest() {
        //     var test_id = $('#test_id').val();

        //     $.ajax({
        //         type: "GET",
        //         url: "{{ url('gettest') }}" + '/' + test_id,
        //         success: function(data) {


        //             $('#price').val(data.price);

        //         },
        //         error: function(data) {
        //             console.log('Error:', data);
        //         }
        //     });
        // };

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
            getRemise();
        }

        function getRemise() {
            let element = document.getElementById("test_id");
            let category_test_id = element.options[element.selectedIndex].getAttribute("data-category_test_id");
            alert("Price: " + category_test_id);

            var contrat_id = $('#contrat_id').val();
            //var category_test_id = element.getAttribute('data-content');

            $.ajax({
                type: "GET",
                url: "{{ url('gettestremise') }}" + '/' + contrat_id + '/' + category_test_id,
                success: function(data) {
                    var discount = $('#price').val() * data / 100;
                    $('#remise').val(discount);

                    var total = $('#price').val() - discount;
                    $('#total').val(total);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        $('#finalisationBtn').on('click', function() {
            let test_order_id = $('#test_order_id').val()
            console.log(test_order_id)
            Swal.fire({
                title: "Avez-vous fini ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Oui ",
                cancelButtonText: "Non !",
            }).then(function(result) {
                if (result.value) {
                    // window.location.href = "{{ url('contrats_details/delete') }}" + "/" + id;
                    $.ajax({
                        url: "/test_order/updatesatus",
                        type: "post",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            test_order_id: test_order_id.id,
                        },
                        success: function(response) {

                            console.log(response);
                            Swal.fire(
                                "Suppression !",
                                "En cours de traitement ...",
                                "success"
                            )

                        },
                        error: function(response) {
                            console.log(response);
                        },
                    });

                }
            });
        });
    </script>
@endpush
