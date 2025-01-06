@php
    $response = App\Models\test_pathology_macro::where('id_test_pathology_order',$data->test_order_id)->exists();
    // dd($response);
@endphp

@if($response)

<button class="btn btn-success">Oui</button>

@else

<button class="btn btn-danger">Non</button>

@endif
