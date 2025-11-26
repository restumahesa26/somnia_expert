@props(['value'])

@php
    if (is_array($value)) {
        dd('ERROR DETECTED: Variabel $value berisi Array!', $value);
    }
@endphp

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
