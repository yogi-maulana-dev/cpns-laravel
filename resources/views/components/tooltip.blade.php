@props(['title' => '', 'placement' => 'top', 'customClass' =>''])

<span {{ $attributes->merge(['class' => join(' ', ['text-info', 'text-decoration-none']) ]) }}
    data-bs-toggle="tooltip"
    data-bs-placement="{{ $placement }}"
    @if($customClass)data-bs-custom-class="{{ $customClass }}" @endif data-bs-title="{{ $title }}" {{ $attributes }}>
    {{ $slot }}
</span>