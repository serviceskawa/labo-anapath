<select name="doctor_id" id="" class="form-control" onchange="updateAttribuate(this.value,{{ $order->id }})">
    <option value="">Selectionnez un docteur signataire</option>
    @foreach (getUsersByRole('docteur') as $item)
        <option value="{{ $item->id }}" {{ $order->attribuate_doctor_id == $item->id ? 'selected' : '' }}>
            {{ $item->lastname }} {{ $item->firstname }} </option>
    @endforeach
</select>
