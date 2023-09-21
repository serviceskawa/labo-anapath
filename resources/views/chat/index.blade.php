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
                                            <a type="button" onclick="openMessage({{$user->id}})" class="text-body">
                                                <div class="d-flex align-items-start mt-1 p-2">
                                                    <div class="avatar-sm" style="margin-right: 10px">
                                                        <span class="avatar-title bg-success rounded">
                                                            xs
                                                        </span>
                                                    </div>
                                                    <div class="w-100 overflow-hidden">
                                                        <h5 class="mt-0 mb-0 font-14">
                                                            <span class="float-end text-muted font-12">4:30am</span>
                                                            {{$user->firstname}} {{$user->lastname}}
                                                        </h5>
                                                            <p class="mt-1 mb-0 text-muted font-14">
                                                                <span class="w-25 float-end text-end"><span class="badge badge-danger-lighten">3</span></span>
                                                                <span class="w-75">How are you today?</span>
                                                            </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach

                                        {{-- <a href="javascript:void(0);" class="text-body">
                                            <div class="d-flex align-items-start mt-1 p-2">
                                                <div class="avatar-sm" style="margin-right: 10px">
                                                    <span class="avatar-title bg-success rounded">
                                                        Md
                                                    </span>
                                                </div>
                                                <div class="w-100 overflow-hidden">
                                                    <h5 class="mt-0 mb-0 font-14">
                                                        <span class="float-end text-muted font-12">4:30am</span>
                                                        Brandon Smith
                                                    </h5>
                                                    <p class="mt-1 mb-0 text-muted font-14">
                                                        <span class="w-25 float-end text-end"><span class="badge badge-danger-lighten">3</span></span>
                                                        <span class="w-75">How are you today?</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="javascript:void(0);" class="text-body">
                                            <div class="d-flex align-items-start mt-1 p-2">
                                                <div class="avatar-sm" style="margin-right: 10px">
                                                    <span class="avatar-title bg-success rounded">
                                                        xs
                                                    </span>
                                                </div>
                                                <div class="w-100 overflow-hidden">
                                                    <h5 class="mt-0 mb-0 font-14">
                                                        <span class="float-end text-muted font-12">4:30am</span>
                                                        Brandon Smith
                                                    </h5>
                                                    <p class="mt-1 mb-0 text-muted font-14">
                                                        <span class="w-25 float-end text-end"><span class="badge badge-danger-lighten">3</span></span>
                                                        <span class="w-75">How are you today?</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </a> --}}


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

        <!-- chat area -->
        <div class="col-xxl-6 col-xl-12 order-xl-2">
            <div class="card">
                <div class="card-body">
                    <ul class="conversation-list" data-simplebar="" style="max-height: 537px">
                        <li class="clearfix">
                            {{-- Utilisateur --}}
                            <div class="chat-avatar">
                                <div class="avatar-xs" style="margin-right: 10px">
                                    <span class="avatar-title bg-success rounded">
                                        xs
                                    </span>
                                </div>
                                <i>10:00</i>
                            </div>
                            {{-- Message --}}
                            <div class="conversation-text">
                                <div class="ctext-wrap">
                                    <i>Shreyu N</i>
                                    <p>
                                        Hello!
                                    </p>
                                </div>
                            </div>
                            {{-- Menu contextuel --}}
                            <div class="conversation-actions dropdown">
                                <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Copy Message</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </li>

                        <li class="clearfix odd">
                            {{-- Utilisateur --}}
                            <div class="chat-avatar">
                                <div class="avatar-xs" style="margin-right: 10px">
                                    <span class="avatar-title bg-success rounded">
                                        xs
                                    </span>
                                </div>
                                <i>10:00</i>
                            </div>
                            {{-- Message --}}
                            <div class="conversation-text">
                                <div class="ctext-wrap">
                                    <i>Shreyu N</i>
                                    <p>
                                        Hello!
                                    </p>
                                </div>
                            </div>
                            {{-- Menu contextuel --}}
                            <div class="conversation-actions dropdown">
                                <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Copy Message</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </li>

                        <li class="clearfix">
                            {{-- Utilisateur --}}
                            <div class="chat-avatar">
                                <div class="avatar-xs" style="margin-right: 10px">
                                    <span class="avatar-title bg-success rounded">
                                        xs
                                    </span>
                                </div>
                                <i>10:00</i>
                            </div>
                            {{-- Message --}}
                            <div class="conversation-text">
                                <div class="ctext-wrap">
                                    <i>Shreyu N</i>
                                    <p>
                                        Hello!
                                    </p>
                                </div>
                            </div>
                            {{-- Menu contextuel --}}
                            <div class="conversation-actions dropdown">
                                <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Copy Message</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </li>

                        <li class="clearfix odd">
                            {{-- Utilisateur --}}
                            <div class="chat-avatar">
                                <div class="avatar-xs" style="margin-right: 10px">
                                    <span class="avatar-title bg-success rounded">
                                        xs
                                    </span>
                                </div>
                                <i>10:00</i>
                            </div>
                            {{-- Message --}}
                            <div class="conversation-text">
                                <div class="ctext-wrap">
                                    <i>Shreyu N</i>
                                    <p>
                                        Hello!
                                    </p>
                                </div>
                            </div>
                            {{-- Menu contextuel --}}
                            <div class="conversation-actions dropdown">
                                <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Copy Message</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </li>

                        <li class="clearfix">
                            {{-- Utilisateur --}}
                            <div class="chat-avatar">
                                <div class="avatar-xs" style="margin-right: 10px">
                                    <span class="avatar-title bg-success rounded">
                                        xs
                                    </span>
                                </div>
                                <i>10:00</i>
                            </div>
                            {{-- Message --}}
                            <div class="conversation-text">
                                <div class="ctext-wrap">
                                    <i>Shreyu N</i>
                                    <p>
                                        Hello!
                                    </p>
                                </div>
                            </div>
                            {{-- Menu contextuel --}}
                            <div class="conversation-actions dropdown">
                                <button class="btn btn-sm btn-link" data-bs-toggle="dropdown" aria-expanded="false"><i class='uil uil-ellipsis-v'></i></button>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Copy Message</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </li>


                    </ul>

                    <div class="row">
                        <div class="col">
                            <div class="mt-2 bg-light p-3 rounded">
                                <form class="needs-validation" novalidate="" name="chat-form" id="chat-form">
                                    <div class="row">
                                        <div class="col mb-2 mb-sm-0">
                                            <input type="text" class="form-control border-0" placeholder="Enter your text" required="">
                                            <div class="invalid-feedback">
                                                Please enter your messsage
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <div class="btn-group">
                                                <a href="#" class="btn btn-light"><i class="uil uil-paperclip"></i></a>
                                                <a href="#" class="btn btn-light"> <i class='uil uil-smile'></i> </a>
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
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div>
        <!-- end chat area-->

        {{-- <!-- start user detail -->
        <div class="col-xxl-3 col-xl-6 order-xl-1 order-xxl-2">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-horizontal"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">View full</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Edit Contact Info</a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">Remove</a>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <img src="assets/images/users/avatar-5.jpg" alt="shreyu" class="img-thumbnail avatar-lg rounded-circle">
                        <h4>Shreyu N</h4>
                        <button class="btn btn-primary btn-sm mt-1"><i class='uil uil-envelope-add me-1'></i>Send Email</button>
                        <p class="text-muted mt-2 font-14">Last Interacted: <strong>Few hours back</strong></p>
                    </div>

                    <div class="mt-3">
                        <hr class="">

                        <p class="mt-4 mb-1"><strong><i class='uil uil-at'></i> Email:</strong></p>
                        <p>support@coderthemes.com</p>

                        <p class="mt-3 mb-1"><strong><i class='uil uil-phone'></i> Phone Number:</strong></p>
                        <p>+1 456 9595 9594</p>

                        <p class="mt-3 mb-1"><strong><i class='uil uil-location'></i> Location:</strong></p>
                        <p>California, USA</p>

                        <p class="mt-3 mb-1"><strong><i class='uil uil-globe'></i> Languages:</strong></p>
                        <p>English, German, Spanish</p>

                        <p class="mt-3 mb-2"><strong><i class='uil uil-users-alt'></i> Groups:</strong></p>
                        <p>
                            <span class="badge badge-success-lighten p-1 font-14">Work</span>
                            <span class="badge badge-primary-lighten p-1 font-14">Friends</span>
                        </p>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col --> --}}
        <!-- end user detail -->
    </div> <!-- end row-->
@endsection


@push('extra-js')
<script>
    var ROUTEGETMESSAGE = "{{ route('chat.getMessage') }}"
    var TOKENGETMESSAGE = "{{ csrf_token() }}"
    function openMessage(id){
        var e_id = id;
        console.log(id,e_id);

        // Populate Data in Edit Modal Form
        $.ajax({
            type: "POST",
            url: ROUTEGETMESSAGE,
            data: {
                "_token": TOKENGETMESSAGE,
                recever_id: e_id
            },
            success: function (data) {
                console.log(data);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
</script>
@endpush



