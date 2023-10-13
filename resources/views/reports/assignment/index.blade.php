@extends('layouts.app2')

@section('title', 'Reports')


@section('css')

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                {{-- <div class="page-title-right mr-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#standard-modal">Affecter des comptes rendu</button>
                </div> --}}
                <h4 class="page-title">Affectation des comptes rendu</h4>
            </div>

            <!----MODAL---->

        </div>
    </div>

    <div class="">

        @include('layouts.alerts')

        <div class="card mb-md-0 mb-3">
            <div class="card-body">

                <h5 class="card-title mb-0">Liste des affectations</h5>

                <div id="cardCollpase1" class="show collapse pt-3">

                    <table id="datatable1" class="table-striped dt-responsive nowrap w-100 table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Docteur</th>
                                <th>Nombre d'affectation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $key => $doctor)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $doctor->fullname() }}</td>
                                    <td>
                                        @forelse ($doctor->assignment as $assignment)
                                            <ul>
                                                <li> {{ $assignment->report->code }}
                                                    ({{ $assignment->report->order->code }})
                                                </li>
                                            </ul>
                                        @empty
                                            <span class="text-muet">Aucune affectation</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <a type="button" href="{{ route('report.assignment.create', $doctor->id) }}"
                                            class="btn btn-primary" title="Affecter un compte rendu"><i
                                                class="mdi mdi-lead-pencil"></i>
                                        </a>
                                        @if (!empty($doctor->assignment()->get()))
                                            <a class="btn btn-warning" href="#" data-bs-toggle="modal"
                                                data-bs-target="#bs-example-modal-lg-edit-{{ $doctor->id }}"><i
                                                    class="mdi mdi-eye"></i>
                                            </a>
                                            @include('reports.assignment.edit', ['item' => $doctor])

                                            <a type="button" href="{{ route('report.assignment.pdf', $doctor->id) }}"
                                                class="btn btn-primary" title="Affecter un compte rendu"><i
                                                    class="mdi mdi-printer"></i>
                                            </a>
                                        @endif

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
    {{-- <script>
        document.getElementById('print-button').addEventListener('click', function() {
            var buttonsToHide = document.querySelectorAll('.no-print');
            // Masquez les boutons en utilisant JavaScript
            buttonsToHide.forEach(function(button) {
                button.style.display = 'none';
            });
            // Sélectionnez le contenu du modal que vous souhaitez imprimer
            var modalContent = document.querySelector('.modal-content');
            // window.print()

            // Créez une fenêtre d'impression
            var printWindow = window.open('', '', 'width=2000,height=1000');

            // Copiez le contenu du modal dans la fenêtre d'impression
            // printWindow.document.open();
            printWindow.document.write('<html><head><title>Impression</title></head><body>');
            printWindow.document.write(modalContent.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Attendez que le contenu soit chargé, puis imprimez
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();

                // Réaffichez les boutons après l'impression
                buttonsToHide.forEach(function(button) {
                    button.style.display = '';
                });
            };
        });
    </script> --}}
@endpush
