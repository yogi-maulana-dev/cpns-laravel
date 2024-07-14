@props(['color' => 'primary', 'as' => 'span'])

@if ($as === 'span')
<span {{ $attributes->merge(['class' => "badge text-$color-emphasis bg-$color-subtle border
    border-$color-subtle"]) }} {{ $attributes }} >{{ $slot }}</span>
@elseif ($as === 'button')
<button {{ $attributes->merge(['class' => "badge text-$color-emphasis bg-$color-subtle border
    border-$color-subtle"]) }} {{ $attributes }} >{{ $slot }}</button>
@elseif($as ==="a")
<a {{ $attributes->merge(['class' => "badge text-$color-emphasis bg-$color-subtle border
    border-$color-subtle"]) }} {{ $attributes }} >{{ $slot }}</a>
@endif