@props(['modalId' => '', 'size' => '', 'simple' => false])
@php
$size = $size ? 'modal-'.$size : '';
@endphp

@if(!$simple)
<!-- Modal -->
<div {{ $attributes->merge(['class' => join(' ', ['modal', 'fade', $size]) ]) }} id="{{ $modalId }}" tabindex="-1"
    aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $modalId }}Label">
                    {{ $title }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light fw-bold border" data-bs-dismiss="modal">
                    <span data-feather="x-square" class="me-1"></span>
                    Tutup</button>
                {{ $footer }}
            </div>
        </div>
    </div>
</div>
@endif

@if($simple)
<div {{ $attributes->merge(['class' => join(' ', ['modal', 'fade', 'modal-sheet', $size]) ]) }} tabindex="-1"
    role="dialog" id="{{ $modalId }}" tabindex="-1"
    aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5" id="{{ $modalId }}Label">{{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-0">
                {{ $slot }}
            </div>
            <div class="modal-footer flex-column border-top-0">
                {{ $footer }}
                <button type="button" class="btn btn-lg btn-outline-danger w-100 mx-0"
                    data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif