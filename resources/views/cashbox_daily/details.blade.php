<div class="modal fade" id="bs-example-modal-lg-show-{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Détail sur cet employé</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                
                <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3">Recaputilatif</h4>
            
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th>Methode</th>
                                                    <th>Total calculer</th>
                                                    <th>Total confirmer</th>
                                                    <th>Total ecart</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Mobile Money</td>
                                                    <td>{{ $item->mobile_money_calculated}}</td>
                                                    <td>{{ $item->mobile_money_confirmation}}</td>
                                                    <td>{{ $item->mobile_money_ecart}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Virement</td>
                                                    <td>{{ $item->virement_calculated}}</td>
                                                    <td>{{ $item->virement_confirmation}}</td>
                                                    <td>{{ $item->virement_ecart}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Cheques</td>
                                                    <td>{{ $item->cheque_calculated}}</td>
                                                    <td>{{ $item->cheque_confirmation}}</td>
                                                    <td>{{ $item->total_calculated}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Especes</td>
                                                    <td>{{ $item->cash_calculated}}</td>
                                                    <td>{{ $item->cash_confirmation}}</td>
                                                    <td>{{ $item->cash_ecart}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- end table-responsive -->
            
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>

                        <div class="row">
                            <div class="col-6">
                             Solde d'ouverture : {{ $item->opening_balance}}
                        </div>
                        <div class="col-6">
                            Solde de fermeture : {{ $item->close_balance}}
                        </div>
                        </div>

            </div>

            
        </div>
    </div>
</div>