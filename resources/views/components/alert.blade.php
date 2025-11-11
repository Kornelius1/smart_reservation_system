{{-- resources/views/components/alert.blade.php --}}
@props(['type' => 'info', 'message'])

@php
    $typeClasses = [
        'success' => 'alert-success text-white', // DaisyUI success class
        'error' => 'alert-error text-white',   // DaisyUI error class
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];
    // Ganti warna kustom Anda di sini jika perlu
    // 'success' => 'bg-[#414939] text-white',
    // 'error' => 'bg-[#9CAF88] text-white',
@endphp

<div role="alert" class="alert {{ $typeClasses[$type] ?? $typeClasses['info'] }} mb-6">
    @if($type === 'success')
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    @endif
    <span>{{ $message }}</span>
</div>