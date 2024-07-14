@props(['name'])

<span data-feather="{{ $name }}" {{ $attributes->merge(['class' => 'align-text-middle']) }} {{ $attributes }}></span>