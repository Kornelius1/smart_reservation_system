@extends('layouts.app')

@section('content')
<div class="card-form">
    <h2>Reschedule Reservasi #{{ $reservation->id }}</h2>

    @if($errors->any())
      <div class="alert error">
          <ul>
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <form action="{{ route('reschedule.update', $reservation) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Pemesan</label>
        <input type="text" name="customer_name" value="{{ old('customer_name', $reservation->customer_name) }}" required>

        <label>Ruangan</label>
        <select name="room_id" required>
            @foreach($rooms as $r)
                <option value="{{ $r->id }}" {{ $reservation->room_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
            @endforeach
        </select>

        <label>Start</label>
        <input type="datetime-local" name="start_datetime" value="{{ old('start_datetime', $reservation->start_datetime->format('Y-m-d\TH:i')) }}" required>

        <label>End</label>
        <input type="datetime-local" name="end_datetime" value="{{ old('end_datetime', $reservation->end_datetime->format('Y-m-d\TH:i')) }}" required>

        <label>Notes</label>
        <textarea name="notes">{{ old('notes', $reservation->notes) }}</textarea>

        <div style="margin-top:1rem">
            <button class="btn primary" type="submit">Simpan Reschedule</button>
            <a href="{{ route('reschedule.index') }}" class="btn">Batal</a>
        </div>
    </form>
</div>
@endsection
