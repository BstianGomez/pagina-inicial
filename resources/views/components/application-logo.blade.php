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

<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    <img src="{{ asset('img/logo.png') }}" alt="Fundación SOFOFA Capital Humano" style="max-width: {{ $maxWidth }}; width: 100%; height: auto; object-fit: contain;">
</div>
