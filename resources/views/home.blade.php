@extends('layouts.app2')

@section('title', 'Home')

@section('content')
<div class="container">
    @if (getOnlineUser()->can('view-dashboard'))
        @include('dashboard')
    @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-3">
                    <div class="card-header">{{ __('Tableau de bord') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ 'Bienvenu '.auth()->user()->name }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
