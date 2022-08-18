@if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert">
        <i class="dripicons-checkmark me-2"></i><strong>Succès!!</strong>{!! $message !!}
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger" role="alert">
        <i class="dripicons-wrong me-2"></i><strong>Erreur!!</strong> {!! $message !!}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <i class="dripicons-wrong me-2"></i><strong>Erreur!!</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    </div>
@endif

<div class="alert alert-success" role="alert" id="success" style="display:none">
    <i class="dripicons-checkmark me-2"></i><strong>Succès!!</strong>
</div>
