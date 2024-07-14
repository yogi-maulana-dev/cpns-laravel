@props(['id', 'name', 'value' => '', 'placeholder' => 'Pilih...', 'withInitScript' => true, 'data' => null])

<select id="{{ $id ?? $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'form-control ' .
    ($errors->has(trim($name, '[]')) ? 'is-invalid' : '')
    ]) }}
    placeholder="{{ $placeholder }}" autocomplete="off" {{ $attributes }}>
    <option value="">{{ $placeholder }}</option>
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

@if($withInitScript)
@push('script')
@if ($data)
<script type="module">
    new TomSelect("#{{ $id ?? $name }}", <?= json_encode($data); ?>);
</script>
@else
<script type="module">
    new TomSelect("#{{ $id ?? $name }}");
</script>
@endif
@endpush
@endif