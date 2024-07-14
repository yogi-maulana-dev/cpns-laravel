@props(['id', 'name', 'error' => null])

<textarea {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($error ?? $name) ? 'is-invalid' : '')
    ]) }} id="{{ $id ?? $name }}" name="{{ $name }}" {{
    $attributes }}>{{ $slot }}</textarea>

@error($error ?? $name)
<div id="{{ $id ?? $name }}" class="invalid-feedback">
    {{ $message }}
</div>
@enderror