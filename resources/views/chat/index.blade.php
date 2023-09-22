@extends('layouts.app2')

@section('title', 'Discussion')

@section('content')
    @include('layouts.alerts')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    Text
                </div>
                <h4 class="page-title">Chat</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <!-- start chat users-->
        <div class="col-xxl-3 col-xl-6 order-xl-1">
            <div class="card">
                <div class="card-body p-0">

                    <div class="tab-content">
                        <div class="tab-pane show active p-3" id="newpost">

                            <!-- users -->
                            <div class="row">
                                <div class="col">
                                    <h5>Utilisateurs</h5>
                                    <div data-simplebar="" style="max-height: 550px">
                                        @foreach ($users as $user)
                                            <a type="button" onclick="openMessage({{ $user->id }})" class="text-body">
                                                <div class="d-flex align-items-start mt-1 p-2">
                                                    <div class="avatar-sm" style="margin-right: 10px">
                                                        <span class="avatar-title bg-success rounded">
                                                            xs
                                                        </span>
                                                    </div>
                                                    <div class="w-100 overflow-hidden">
                                                        <h5 class="mt-0 mb-0 font-14">
                                                            <span class="float-end text-muted font-12">4:30am</span>
                                                            {{ $user->firstname }} {{ $user->lastname }}
                                                        </h5>
                                                        <p class="mt-1 mb-0 text-muted font-14">
                                                            <span class="w-25 float-end text-end"><span
                                                                    class="badge badge-danger-lighten">3</span></span>
                                                            <span class="w-75">How are you today?</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach


                                    </div> <!-- end slimscroll-->
                                </div> <!-- End col -->
                            </div>
                            <!-- end users -->
                        </div> <!-- end Tab Pane-->
                    </div> <!-- end tab content-->
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
        <!-- end chat users-->

        {{-- <!-- chat area -->
        <div class="col-xxl-6 col-xl-12 order-xl-2">
            <div class="card" id="card_discussion" style="display: none">
                <div class="card-body">

                    <div>
                        <div>
                            <h4 class="text-center" id="chat_new">Nouvelle discussion</h4>
                        </div>
                        <ul class="conversation-list" style="display: none" id="chat_old" data-simplebar=""
                            style="max-height: 537px">

                        </ul>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mt-2 bg-light p-3 rounded">
                                <form class="needs-validation" novalidate="" name="chat-form" id="chat-form">
                                    <div class="row">
                                        <div class="col mb-2 mb-sm-0">
                                            <input type="text" id="message" class="form-control border-0"
                                                placeholder="Enter your text" required="">
                                            <input type="hidden" name="receve_id" id="receve_id">
                                            <div class="invalid-feedback">
                                                Please enter your messsage
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-light"><i
                                                        class="uil uil-paperclip"></i></a>
                                                <a href="#" class="btn btn-light"> <i class='uil uil-smile'></i>
                                                </a>
                                                <div class="d-grid">
                                                    <button type="button" id="chat-button"
                                                        class="btn btn-success chat-send"><i
                                                            class='uil uil-message'></i></button>
                                                </div>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row-->
                                </form>
                            </div>
                        </div> <!-- end col-->
                    </div><!-- end row -->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div>
        <!-- end chat area--> --}}

        <!-- chat area -->
        <div class="col-xxl-6 col-xl-12 order-xl-2">
            <div class="card" id="card_discussion" style="display: none">
                <div class="card-body" style="max-height: 550px" data-simplebar="">
                    <div>
                        <h4 class="text-center" id="chat_new">Nouvelle discussion</h4>
                    </div>
                    <ul class="conversation-list" style="display: none" id="chat_old">
                    </ul>


                </div> <!-- end card-body -->
                <div class="row">
                    <div class="col">
                        <div class="p-3 rounded">
                            <form class="needs-validation" novalidate="" name="chat-form" id="chat-form">
                                <div class="row">
                                    <div class="col mb-2 mb-sm-0">
                                        <input type="text" class="form-control border-0 bg-light " placeholder="Enter your text" required="">
                                        <div class="invalid-feedback">
                                            Please enter your messsage
                                        </div>
                                    </div>
                                    <div class="col-sm-auto">
                                        <div class="btn-group">
                                            {{-- <a href="#" class="btn btn-light"><i class="uil uil-paperclip"></i></a>
                                            <a href="#" class="btn btn-light"> <i class='uil uil-smile'></i> </a> --}}
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success chat-send"><i class='uil uil-message'></i></button>
                                            </div>
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                            </form>
                        </div>
                    </div> <!-- end col-->
                </div>
                <!-- end row -->
            </div> <!-- end card -->
        </div>
        <!-- end chat area-->

        <!-- start user detail -->
            <div class="col-xxl-3 col-xl-6 order-xl-1 order-xxl-2" id="receve_info" style="display: none">
                <div class="card">
                    <div class="card-body">


                        <div class="mt-3 text-center">
                            <div class="avatar-sm" style="margin-left: 90px">
                                <span class="avatar-title bg-success rounded">
                                    xs
                                </span>
                            </div>
                            <h4 id="receve_name">  </h4>
                        </div>

                        <div class="mt-3">
                            <hr class="">

                            <p class="mt-4 mb-1"><strong><i class='uil uil-at'></i> Email:</strong></p>
                            <p id="receve_email"></p>

                            <p class="mt-3 mb-1"><strong><i class='uil uil-phone'></i> Phone Number:</strong></p>
                            <p id="receve_phone"></p>


                        </div>
                    </div> <!-- end card-body -->
                </div> <!-- end card-->
            </div> <!-- end col -->
        <!-- end user detail -->
    </div> <!-- end row-->
@endsection


@push('extra-js')
    <script>
        var ROUTEGETMESSAGE = "{{ route('chat.getMessage') }}"
        var TOKENGETMESSAGE = "{{ csrf_token() }}"
        var ROUTESENDMESSAGE = "{{ route('chat.sendMessage') }}"
        var ROUTECHECKMESSAGE = "{{ route('chat.checkMessage') }}"
        var TOKENSENDMESSAGE = "{{ csrf_token() }}"

        function formatTime(dateTimeString) {
            var date = new Date(dateTimeString);
            var hours = date.getHours();
            var minutes = date.getMinutes();

            // Ajoutez un zéro devant les chiffres uniques (par exemple, 03 au lieu de 3)
            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? '0' + minutes : minutes;

            return hours + ':' + minutes;
        }

        function checkForNewMessages() {
            // Récupérer le dernier message existant dans la liste
            var lastMessage = $('#chat_old li:last-child');
            var lastMessageId = lastMessage.find('#content');
            var receve_id = $('#receve_id').val()
            console.log(lastMessageId.text(),receve_id);

            // Effectuer une requête AJAX pour vérifier les nouveaux messages
            $.ajax({
                url: ROUTECHECKMESSAGE,
                type: "POST",
                data: {
                    "_token": TOKENSENDMESSAGE,
                    message: lastMessageId.text(),
                    receve_id: receve_id
                },
                success: function (response) {
                    console.log(response);
                    if (response.messages && response.messages.length > 0) {
                        var chatOld = $('#chat_old');
                         // Ajouter chaque message existant à la liste <i>${message.sender_name}</i>
                         data.messages.forEach(function(message) {
                                if (message.message) {
                                    var newLi = "";
                                    console.log(e_id);
                                    if (data.user_connect != message.sender_id) {
                                        newLi = $('<li class="clearfix">')
                                    } else {
                                        newLi = $('<li class="clearfix odd">')
                                    }
                                    newLi.html(`
                                    <!-- Utilisateur -->
                                    <div class="chat-avatar">
                                        <div class="avatar-xs" style="margin-right: 10px">
                                            <span class="avatar-title bg-success rounded">
                                                xs
                                            </span>
                                        </div>
                                        <i>${formatTime(message.created_at)}</i>
                                    </div>
                                    <!-- Message -->
                                    <div class="conversation-text">
                                        <div class="ctext-wrap">
                                            <i>Me</i>
                                            <p>${message.message}</p>
                                        </div>
                                    </div>
                                    <!-- Menu contextuel -->
                                    <div class="conversation-actions dropdown">
                                        <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Copy Message</a>
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                    `);
                                    chatOld.append(newLi);
                                }

                            });
                    }
                },
                error: function (response) {
                    console.log(response);
                },
            });
        }

        function openMessage(id) {
            var e_id = id;
            console.log(id, e_id);
            var card_discussion = document.getElementById('card_discussion');
            var chat_old = document.getElementById('chat_old');
            var chat_new = document.getElementById('chat_new');
            var receve_info = document.getElementById('receve_info');
            console.log(card_discussion, chat_new, chat_old, receve_info);

            // Populate Data in Edit Modal Form
            $.ajax({
                type: "POST",
                url: ROUTEGETMESSAGE,
                data: {
                    "_token": TOKENGETMESSAGE,
                    recever_id: e_id
                },
                success: function(data) {
                    console.log(data);
                    $('#receve_id').val(e_id);
                    if (card_discussion.style.display == 'none') {
                        card_discussion.style.display = 'block'
                    }

                    if (data.message == 'new') {
                        chat_old.style.display = 'none'
                        chat_new.style.display = 'block'
                        receve_info.style.display = 'block'
                        $('#receve_name').val(data.receve.firstname+' '+data.receve.lastname)
                        $('#receve_email').val(data.receve.email)
                        // $('#receve_phone').val(data.receve.phone)

                    } else {
                        chat_new.style.display = 'none'
                        chat_old.style.display = 'block'
                        chat_old.style.display = 'max-height: 537px'
                        receve_info.style.display = 'block'
                        var receve_name = document.getElementById('receve_name');
                        var receve_email = document.getElementById('receve_email');
                        receve_name.textContent = data.receve.firstname+' '+data.receve.lastname
                        receve_email.textContent = data.receve.email

                        if (data.content_message.length ==1) {
                            chat_new.style.display = 'block'
                        }

                        if (data.content_message && data.content_message.length > 0) {
                            var chatOld = $('#chat_old');
                            chatOld.empty();

                            // Ajouter chaque message existant à la liste <i>${message.sender_name}</i>
                            data.content_message.forEach(function(message) {
                                if (message.message) {
                                    var newLi = "";
                                    console.log(e_id);
                                    if (data.user_connect != message.sender_id) {
                                        newLi = $('<li class="clearfix">')
                                    } else {
                                        newLi = $('<li class="clearfix odd">')
                                    }
                                    newLi.html(`
                                    <!-- Utilisateur -->
                                    <div class="chat-avatar">
                                        <div class="avatar-xs" style="margin-right: 10px">
                                            <span class="avatar-title bg-success rounded">
                                                xs
                                            </span>
                                        </div>
                                        <i>${formatTime(message.created_at)}</i>
                                    </div>
                                    <!-- Message -->
                                    <div class="conversation-text">
                                        <div class="ctext-wrap">
                                            <i>Me</i>
                                            <p>${message.message}</p>
                                        </div>
                                    </div>
                                    <!-- Menu contextuel -->
                                    <div class="conversation-actions dropdown">
                                        <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Copy Message</a>
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                    `);
                                    chatOld.append(newLi);
                                }

                            });
                        }
                        // setInterval(checkForNewMessages, 10000);
                    }
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }

        $('#chat-button').on('click', function() {
            var message = $('#message').val()
            var receve_id = $('#receve_id').val()
            $.ajax({
                url: ROUTESENDMESSAGE,
                type: "POST",
                data: {
                    "_token": TOKENSENDMESSAGE,
                    message: message,
                    receve_id: receve_id
                },
                success: function(response) {
                    // Réinitialisez le formulaire
                    $('#chat-form').trigger("reset");

                    // Ajoutez une nouvelle ligne li à la liste de conversation  <i>${response.sender_name}</i>
                    var newLi = $('<li class="clearfix odd">');
                    newLi.html(`
                <!-- Utilisateur -->
                <div class="chat-avatar">
                    <div class="avatar-xs" style="margin-right: 10px">
                        <span class="avatar-title bg-success rounded">
                            xs
                        </span>
                    </div>
                    <i>10:00</i>
                </div>
                <!-- Message -->
                <div class="conversation-text">
                    <div class="ctext-wrap">
                        <i>Me</i>
                        <p id="content">${message}</p>
                    </div>
                </div>
                <!-- Menu contextuel -->
                <div class="conversation-actions dropdown">
                    <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">Copy Message</a>
                        <a class="dropdown-item" href="#">Edit</a>
                        <a class="dropdown-item" href="#">Delete</a>
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
