<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-show-{{$item->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Les details sur l'article</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="mb-3 col-lg-12">
                        <label for="" style="font-weight: 900;">Nom de l'article</label>
                        <div>
                            {{ $item->article_name}}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="mb-3 col-lg-12">
                        <label for="" style="font-weight: 900;">Description</label>
                        <div>
                            {{ $item->description}}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="mb-3 col-lg-12">
                        <label for="" style="font-weight: 900;">Quantite de en stock</label>
                        <div>
                            {{ $item->quantity_in_stock}}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="mb-3 col-lg-12">
                        <label for="" style="font-weight: 900;">Unite de mesure</label>
                        <div>
                            {{ $item->unit_of_measurement}}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="mb-3 col-lg-12">
                        <label for="" style="font-weight: 900;">Date d'expiration</label>
                        <div>
                            {{ $item->expiration_date}}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="mb-3 col-lg-12">
                        <label for="" style="font-weight: 900;">Numero du lot</label>
                        <div>
                            {{ $item->lot_number}}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="mb-3 col-lg-12">
                        <label for="" style="font-weight: 900;">Seuil minimum</label>
                        <div>
                            {{ $item->minimum}}
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->