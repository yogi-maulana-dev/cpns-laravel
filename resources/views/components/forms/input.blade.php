@props(['id', 'name', 'value' => '', 'type' => 'text', 'error' => null])

<input type="{{ $type }}" {{ $attributes->merge(['class' => 'form-control ' . ($errors->has($error ?? $name) ?
'is-invalid' : '')
]) }} id="{{ $id ?? $name }}"
name="{{ $name }}" value="{{ $value }}" {{ $attributes }} />

@error($error ?? $name)
<div id="{{ $id ?? $name }}" class="text-sm invalid-feedback">
    {{ $message }}
</div>
@enderror