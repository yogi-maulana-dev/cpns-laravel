@props(['id', 'name', 'required' => true, 'label', 'bagName' => null])

@php
$errorMessage = $bagName ? $errors->$bagName->first($name) : $errors->has($name);
$classes = $attributes->merge(['class' => 'form-control ' . ($errorMessage ? 'is-invalid' : '')]);
@endphp

<div class="form-floating">
    <textarea {{ $classes }} id="{{ $id ?? $name }}" name="{{ $name }}" {{ $attributes }}>{{ $slot }}</textarea>
    <x-forms.label id="{{ $id ?? $name }}" :required="$required">{{ $label }}</x-forms.label>
</div>

@error($name, $bagName)
<small id="{{ $id ?? $name }}" class="text-danger fw-medium d-block mt-1">
    {{ $message }}
</small>
@enderror