@props(['size' => 'md'])

@php
    $maxWidth = match($size) {
        'xs' => '60px',
        'sm' => '100px',
        'md' => '130px',
        'lg' => '160px',
        default => '130px'
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }} style="background: white; padding: 10px; border-radius: 12px; box-shadow: 0 4px 15px rgba(255,255,255,0.1);">
    <img src="{{ asset('img/logo.png') }}" alt="Fundación SOFOFA Capital Humano" style="max-width: {{ $maxWidth }}; width: 100%; height: auto; object-fit: contain;">
</div>
