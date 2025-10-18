@extends('layouts.app')

@section('content')
<div class="rooms-grid">
    @foreach($rooms as $room)
    <div class="room-card">
        <div class="room-image">
            <img src="{{ asset($room->image ?? 'images/rooms/default.jpg') }}" alt="{{ $room->name }}">
        </div>
        <div class="room-body">
            <h3>{{ $room->name }}</h3>
            <p class="meta">
                <span class="icon">👥</span> Kapasitas: {{ $room->capacity }} Orang<br>
                <span class="icon">🔥</span> Min. Order: Rp{{ number_format($room->min_order,0,',','.') }} / 3 jam<br>
                <span class="icon">⏱️</span> Extra Time: Rp{{ number_format($room->extra_time_price,0,',','.') }} / jam
            </p>

            <div class="actions">
                <a href="#" class="btn primary" onclick="document.getElementById('reserve-{{ $room->id }}').scrollIntoView({behavior:'smooth'})">Pilih Ruangan Ini</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<h2 style="margin-top:30px">Daftar Reservasi</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID</th><th>Nama</th><th>Ruangan</th><th>Start</th><th>End</th><th>Status</th><th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($reservations as $res)
        <tr>
            <td>{{ $res->id }}</td>
            <td>{{ $res->customer_name }}</td>
            <td>{{ $res->room->name }}</td>
            <td>{{ $res->start_datetime->format('d M Y H:i') }}</td>
            <td>{{ $res->end_datetime->format('d M Y H:i') }}</td>
            <td>{{ ucfirst($res->status) }}</td>
            <td>
                <a href="{{ route('reschedule.edit', $res) }}" class="btn small">Reschedule</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
