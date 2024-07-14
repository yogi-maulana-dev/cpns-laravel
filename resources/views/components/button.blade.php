@props(['color' => 'primary', 'as' => 'button'])

@if ($as === 'button')
<button {{ $attributes->merge(['class' => "btn btn-$color"]) }} {{ $attributes }} >{{ $slot }}</button>
@else
<a {{ $attributes->merge(['class' => "btn btn-$color"]) }} {{ $attributes }} >{{ $slot }}</a>
@endif