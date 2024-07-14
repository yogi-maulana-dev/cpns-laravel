@props(['color' => 'primary', 'as' => 'span'])

@if ($as === 'span')
<span {{ $attributes->merge(['class' => "badge text-bg-$color"]) }} {{ $attributes }} >{{ $slot }}</span>
@elseif ($as === 'button')
<button {{ $attributes->merge(['class' => "badge text-bg-$color"]) }} {{ $attributes }} >{{ $slot }}</button>
@elseif($as ==="a")
<a {{ $attributes->merge(['class' => "badge text-bg-$color"]) }} {{ $attributes }} >{{ $slot }}</a>
@endif