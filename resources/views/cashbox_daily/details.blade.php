<div class="modal fade" id="bs-example-modal-lg-show-{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="myLargeModalLabel"><span style="font-weight: 900">ID {{ $item->id }}</span>
                    : {{ $item->created_at
                    }} - {{ $item->updated_at }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12 w-100">
                        <div class="card">
                            <div class="card-body">
                                {{-- <h4 class="header-title mb-3">Recaputilatif</h4> --}}

                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mode de paiement</th>
                                                <th>Fond initial</th>
                                                <th>Vente</th>
                                                <th>Solde</th>
                                                <th>Comptage</th>
                                                <th>Ecart</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Especes</td>
                                                <td>{{ $item->opening_balance }}</td>
                                                <td>{{ $item->cash_calculated }}</td>
                                                <td>{{ $item->opening_balance + $item->cash_calculated }}</td>
                                                <td>{{ $item->cash_confirmation }}</td>
                                                <td>{{ $item->cash_ecart }}</td>
                                            </tr>

                                            <tr>
                                                <td>Mobile Money</td>
                                                <td>-</td>
                                                <td>{{ $item->mobile_money_calculated }}</td>
                                                <td>-</td>
                                                <td>{{ $item->mobile_money_confirmation }}</td>
                                                <td>{{ $item->mobile_money_ecart }}</td>
                                            </tr>

                                            <tr>
                                                <td>Cheques</td>
                                                <td>-</td>
                                                <td>{{ $item->cheque_calculated }}</td>
                                                <td>-</td>
                                                <td>{{ $item->cheque_confirmation }}</td>
                                                <td>{{ $item->total_ecart }}</td>
                                            </tr>

                                            <tr>
                                                <td>Virement</td>
                                                <td>-</td>
                                                <td>{{ $item->virement_calculated }}</td>
                                                <td>-</td>
                                                <td>{{ $item->virement_confirmation }}</td>
                                                <td>{{ $item->virement_ecart }}</td>
                                            </tr>

                                            <tr style="font-weight: 900;">
                                                <td>Total</td>
                                                <td>{{ $item->opening_balance }}</td>
                                                <td>{{ $item->total_calculated }}</td>
                                                <td>{{ $item->opening_balance + $item->cash_calculated }}</td>
                                                <td>{{ $item->total_confirmation }}</td>
                                                <td>{{ $item->total_ecart }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label for="" class="form-label">Commentaire</label>
                                    <input value="{{ $item->description }}" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6" style="font-size: 20px; font-weight:900;">
                        SOLDE DE FERMETURE : {{ $item->close_balance}}
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>