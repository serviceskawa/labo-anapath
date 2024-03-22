@extends('layouts.app2')

@section('title', 'Signaler un problème')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right mr-3 mb-1">
                <a href="{{ route('probleme.report.index') }}" type="button" class="btn btn-primary"> <i
                        class="dripicons-reply"></i> Retour</a>
            </div>
            <h4 class="page-title d-flex">
                Ticket : <strong>{{ $ticket->ticket_code }} &nbsp;</strong>
                <p id="status_page_title">
                    @if ($ticket->status == "ouvert")
                    <span style="color: green;"> [Ouvert]</span>
                    @elseif ($ticket->status == "fermé")
                    <span style="color: red;"> [Fermé]</span>
                    @elseif ($ticket->status == "repondu")
                    <span style="color: green;"> [Répondu]</span>
                    @endif
                </p>
            </h4>
        </div>
    </div>
</div>
<div class="">



    @include('layouts.alerts')

    <div class="row" style="margin-left: px;margin-right:3px; display:flex; justify-content:space-between;">
        <div class="col-6" style="padding-right:10px">
            <div class="card" style="padding-bottom:20px">

                <div class="card-body">

                    <form action="{{ route('probleme.report.update') }} " method="post" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$ticket->id}}">
                        <div class="row">
                            <div class="col-6">
                                <label for="exampleFormControlInput1" class="form-label mt-3">Créé par</label><br>
                                {{$ticket->user->fullname()}}
                            </div>
                            <div class="col-6">
                                <label for="exampleFormControlInput1" class="form-label mt-3">Statut</label><br>
                                <?php
                    $ticketStatus = $ticket->status;
                    $statusText = '';

                    if ($ticketStatus === 'fermé') {
                        $statusText = 'Fermé';
                    } elseif ($ticketStatus === 'ouvert') {
                        $statusText = 'Ouvert';
                    } elseif ($ticketStatus === 'repondu') {
                        $statusText = 'Répondu';
                    } else {
                        $statusText = 'Status inconnu';
                    }
                ?>
                                <p id="oldStatus"><?php echo $statusText; ?> <button type="button" id="openSelect"
                                        style="border:none; background:white"><i
                                            class="mdi mdi-square-edit-outline"></i></button></p>

                                <div class="d-flex">
                                    <select name="status" id="ticket_status" style="display: none" class="form-control">
                                        <option value="">Selectionner un statut</option>
                                        <!-- <option value="ouvert">Ouvert</option>
                        <option value="repondu">Répondu</option> -->
                                        <option value="fermé">Fermé</option>
                                    </select>
                                    <button id="closeSelect" type="button"
                                        style="display: none; border:none; background:white"><i
                                            class="mdi mdi-close"></i></button>
                                </div>


                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label mt-3">Objet</label>
                            <input type="text" name="subject" placeholder="Entrer une description" id=""
                                value="{{$ticket->subject}}" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="simpleinput" class="form-label">Description</label>
                            <textarea type="text" name="" placeholder="" id="" class="form-control"
                                readonly>{{ $ticket->description }}</textarea>
                        </div>

                        @if($ticketStatus !== 'fermé')
                        <div class="py-2 px-2 d-flex align-content-between justify-content-between">
                            <!-- utilisateur qui a cree ou le super admin -->
                            @if ($user_role || $ticket->user_id == Auth::user()->id)
                            <button type="submit" class="btn btn-primary float-end">Mettre à jour</button>
                            @endif
                        </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>

        <div class="card col-6">

            <div class="card-body">
                <h5 class="card-title mb-0">Discussion sur le ticket</h5>
                <p>L'historique des commentaires pour ce ticket sera disponible ici.</p>
                <hr>
                <ul class="conversation-list mt-3" data-simplebar="" id="chat_old">
                    @foreach ($ticket_comments as $comment)
                    @if ($comment->user_id != Auth::user()->id)
                    <li class="clearfix">
                        <div class="chat-avatar">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-primary rounded-circle">
                                    {{getNameInitials($comment->user->firstname." ".$comment->user->lastname." ")}}
                                </span>
                            </div>
                            <i>
                                {{ date_format($comment->created_at, 'H:i')}}
                            </i>

                        </div>
                        <div class="conversation-text">
                            <i>{{$comment->user->firstname." ".$comment->user->lastname}}</i><br>
                            <div class="ctext-wrap">
                                <p>
                                    {{$comment->comment}}
                                </p>
                            </div>
                        </div>
                    </li>
                    @else
                    <li class="clearfix odd">
                        <div class="chat-avatar">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-primary rounded-circle">
                                    {{getNameInitials($comment->user->firstname." ".$comment->user->lastname." ")}}
                                </span>
                            </div>
                            <i>
                                {{ date_format($comment->created_at, 'H:i')}}
                            </i>

                        </div>
                        <div class="conversation-text">
                            <i>{{$comment->user->firstname." ".$comment->user->lastname}}</i><br>
                            <div class="ctext-wrap">
                                <p>
                                    {{$comment->comment}}
                                </p>
                            </div>
                        </div>
                    </li>
                    @endif

                    @endforeach
                </ul>

            </div>
            <div class="row">
                <div class="col">
                    <div class="mt-2 p-2 rounded">
                        {{-- <form class="needs-validation" novalidate="" name="chat-form" id="chat-form"> --}}
                        <div class="row">
                            <div class="col mb-2 mb-sm-0">
                                <input type="hidden" name="id" id="ticket_id" value="{{$ticket->id}}">
                                <input type="text" id="message" class="bg-light form-control border-0"
                                    placeholder="Ecrivez un commentaire" required="">
                                <div class="invalid-feedback">
                                    Veiullez saisir un message enter your messsage
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="btn-group">
                                    <div class="d-grid">
                                        <button type="submit" id="chat-button" class="btn btn-success chat-send"><i
                                                class='uil uil-message'></i></button>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                        </form>
                    </div>
                </div> <!-- end col-->
            </div>
            <!-- end row -->
        </div>
    </div>
</div>
@endsection


@push('extra-js')
<script>
var ROUTECOMMENTTICKET = "{{route('probleme.report.sendMessage')}}"
var TOKENSENDMESSAGE = "{{ csrf_token() }}"
var ticket = {!!json_encode($ticket) !!}

$('#openSelect').on('click', function() {
    var openSelect = document.getElementById('openSelect')
    var closeSelect = document.getElementById('closeSelect')
    var status = document.getElementById('ticket_status')
    var oldStatus = document.getElementById('oldStatus')
    oldStatus.style.display = 'none'
    status.style.display = 'block'
    closeSelect.style.display = 'block'
})
$('#closeSelect').on('click', function() {
    var openSelect = document.getElementById('openSelect')
    var closeSelect = document.getElementById('closeSelect')
    var status = document.getElementById('ticket_status')
    var oldStatus = document.getElementById('oldStatus')
    oldStatus.style.display = 'block'
    status.style.display = 'none'
    closeSelect.style.display = 'none'
})


//Gestion des commentaires

//Formater la date
function formatTime(dateTimeString) {
    var date = new Date(dateTimeString);
    var hours = date.getHours();
    var minutes = date.getMinutes();

    // Ajoutez un zéro devant les chiffres uniques (par exemple, 03 au lieu de 3)
    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;

    return hours + ':' + minutes;
}

//Récupérer les initiaux de l'utilisateur
function getNameInitials(fullName) {
    var initials = "";
    var nameParts = fullName.split(" ");

    for (var i = 0; i < nameParts.length; i++) {
        var namePart = nameParts[i].trim();
        if (namePart.length > 0) {
            initials += namePart[0].toUpperCase();
        }
    }

    return initials;
}


$('#chat-button').on('click', function() {
    var message = $('#message').val()
    var ticket_id = $('#ticket_id').val()
    $(('#message')).val('')
    var receve_id = $('#receve_id').val()
    $.ajax({
        url: ROUTECOMMENTTICKET,
        type: "POST",
        data: {
            "_token": TOKENSENDMESSAGE,
            message: message,
            ticket_id: ticket_id
        },
        success: function(response) {
            console.log(response);
            var status_page_title = document.getElementById('status_page_title');
            if (response.is_read) {
                status_page_title.textContent = '[Ouvert]'
                status_page_title.style.color = 'green'
            }

            // Ajoutez une nouvelle ligne li à la liste de conversation  <i>${response.sender_name}</i>
            var newLi = $('<li class="clearfix odd" id="line_message">');
            newLi.html(`
                    <div class="chat-avatar">
                            <div class="avatar-xs">
                                <span class="avatar-title bg-primary rounded-circle">
                                    ${getNameInitials(response.sender.firstname+' '+response.sender.lastname)}
                                </span>
                            </div>
                            <i>${formatTime(response.comment.created_at)}</i>

                        </div>
                        <div class="conversation-text">
                            <i>${response.sender.firstname+' '+response.sender.lastname}</i><br>
                            <div class="ctext-wrap">
                                <p>
                                    ${message}
                                </p>
                            </div>
                        </div>
                `);

            // Ajoutez le nouveau li à la liste de conversation
            $('#chat_old').append(newLi);
        },
        error: function(response) {
            console.log(response)
        },
    });
})
</script>

@endpush