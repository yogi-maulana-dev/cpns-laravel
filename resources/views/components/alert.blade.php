@props(['color' => 'danger', 'feather' => 'info', 'title' => null])

@props(['color' => 'primary', 'as' => 'span'])

@if ($as === 'span')
<div {{ $attributes->merge(['class' => "alert alert-$color"]) }} {{ $attributes }}>
    @if ($title)
    <div class="d-flex align-items-center gap-2">
        <x-feather :name="$feather" stroke-width="3" />
        <h6 class="fw-bold m-0 d-block">{{ $title }}</h6>
    </div>
    <hr />
    @endif
    {{ $slot }}
</div>
@endif