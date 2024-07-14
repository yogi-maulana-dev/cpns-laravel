@props(['id', 'name', 'value' => '', 'placeholder' => 'Pilih...'])

<select id="{{ $id ?? $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control ' .
    ($errors->has(trim($name, '[]')) ? 'is-invalid' : '')
    ]) }}
    placeholder="{{ $placeholder }}" autocomplete="off" {{ $attributes }}>
    <option value="" disabled>{{ $placeholder }}</option>
    {{ $slot }}
</select>

@php
$key = str_replace(['[', ']'], '',$id ?? $name);
@endphp

@error(trim($name, '[]'))
<div id="{{ $id ?? $name }}" class="invalid-feedback">
    {{ $message }}
</div>
@enderror