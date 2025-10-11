{{--
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-g">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservasi-Pemesanan Menu</title>


    @vite('resources/js/app.js')
</head>

<body>

    <div id="pesanmenu"></div>

</body>

</html> --}}

{{-- 1. Memberitahu Blade untuk menggunakan layout dari layouts/app.blade.php --}}
@extends('layouts.app')

{{-- 2. Mengisi placeholder 'title' yang ada di layout --}}
@section('title', 'Reservasi - Pemesanan Menu')

{{-- 3. Mengisi placeholder 'content' dengan konten spesifik halaman ini --}}
@section('content')

    {{-- Konten unik Anda sekarang ada di dalam section ini --}}
    <div id="pesanmenu"></div>

    {{-- Jika halaman ini dijalankan oleh Vue/React, elemen dengan id="pesanmenu"
    akan menjadi titik mount-nya, yang dimuat oleh app.js dari layout. --}}

@endsection