<div class="modal fade" id="bs-example-modal-lg-detail-{{ $data->id }}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Détails - <span style="font-weight: bold;">
                        {{ $data->code }}
                    </span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">

                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            Demande d'examen : <span style="font-weight: bold;"> {{ $data->order->code }} </span>
                        </div>

                        <div class="mb-3 col-lg-12">
                            Date de recupération: <span style="font-weight: bold;"> {{ $data->delivery_date }}
                            </span>
                        </div>

                        <div class="mb-3 col-lg-12">
                            Nom du récupérateur: <span style="font-weight: bold;"> {{ $data->retriever_name }}
                            </span>
                        </div>

                        <div class="mb-3 col-lg-12">
                            <label for="simpleinput" class="form-label">Signature :</label><br>
                            <div>

                                @if (str_starts_with($data->retriever_signature, 'data:image/png;base64,'))
                                    <!-- Affichage pour PNG en base64 -->
                                    <img src="{{ $data->retriever_signature }}" style="width: 700px; height:auto;"
                                    alt="Signature de {{ $data->retriever_name }}" />
                                @elseif (str_starts_with($data->retriever_signature, '<?xml version="1.0"?>') || str_starts_with($data->retriever_signature, '<svg'))
                                    <!-- Affichage pour SVG -->
                                    <div class="col-md-5"  style="width: auto; height:200px;">
                                        {!! $data->retriever_signature !!}
                                    </div>
                                @else
                                    <!-- Gérer le cas où le format est inconnu -->
                                    <p>Signature non disponible</p>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
                </div>

            </div>
        </div>
    </div>
</div>
