<!-- Large modal -->
<div class="modal fade" id="bs-example-modal-lg-history-version-{{$doc->id}}" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Historique des versions du fichier  {{$doc->title}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                {{-- <h4>Fichier : {{$ticket->code}}</h4> --}}

                 <div id="cardCollpase1" class="collapse pt-3 show">


                    <table id="datatable1" class="table table-striped dt-responsive nowrap w-100">
                        {{-- <thead>
                            <tr>
                                <th>#</th>
                                <th>Article</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                        </thead> --}}

                        <tbody>

                            @foreach (App\Models\DocVersion::where('doc_id',$doc->id)->latest()->get() as $key => $doc_ver)
                                <tr>
                                    {{-- <td>{{ ++$key}}</td> --}}
                                    <td>{{ date_format($doc_ver->created_at,'d/m/Y H:i:s') }}</td>
                                    <td>{{ $doc_ver->title }} {{ $doc_ver->version == 1 ? '(Version initiale)':'' }}</td>
                                    <td>Ajouté par {{ $doc_ver->user->fullname() }}</td>
                                    <td>
                                        @if (App\Models\DocVersion::where('doc_id',$doc->id)->count() == $doc_ver->version)
                                        <span class="badge bg-success">Version actuelle </span>
                                        @else

                                            <div class="row">
                                                <div class="col-2">
                                                    <a class="dropdown-item"  href="{{asset('storage/'.$doc_ver->attachment)}}" target="_blank" type="application/pdf">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                </div>
                                                <div class="col-2">
                                                    <a class="dropdown-item" href="{{asset('storage/'.$doc_ver->attachment)}}" download>
                                                        <i class="mdi mdi-download"></i>
                                                    </a>
                                                </div>

                                            </div>

                                        @endif
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{-- Fin --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
