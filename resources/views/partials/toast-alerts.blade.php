<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3">
        @if (session()->has('success'))
        <div x-data="{show: false}">
            <div class="toast align-items-center text-bg-success border-0 p-1 shadow-lg show" role="alert" x-show="show"
                aria-live="assertive" aria-atomic="true" x-init="
                setTimeout(() => show = true, 200); setTimeout(() => show = false, 7000)" x-transition:enter.scale.80
                x-transition:leave.scale.90>
                <div class=" d-flex">
                    <div class="toast-body w-100">
                        <div class="d-flex align-items-center mb-2 justify-content-between">
                            <div>
                                <span data-feather="check-circle" class="align-text-middle me-2 icon-size"></span>
                                <span class="fw-bold">Berhasil</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" x-on:click="show = false"></button>
                        </div>
                        <hr class="opacity-25" />
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (session()->has('failed'))
        <div x-data="{show: false}">
            <div class="toast align-items-center text-bg-danger border-0 p-1 shadow-lg show" role="alert"
                aria-live="assertive" aria-atomic="true" x-show="show" aria-live="assertive" aria-atomic="true"
                x-show="show" x-init="
                setTimeout(() => show = true, 200); setTimeout(() => show = false, 7000)" x-transition:enter.scale.80
                x-transition:leave.scale.90>
                <div class="d-flex">
                    <div class="toast-body w-100">
                        <div class="d-flex align-items-center mb-2 justify-content-between">
                            <div>
                                <span data-feather="x-circle" class="align-text-middle me-2 icon-size"></span>
                                <span class="fw-bold">Gagal</span>
                            </div>
                            <button type="button" class="btn-close btn-close-white" x-on:click="show = false"></button>
                        </div>
                        <hr class="opacity-25" />
                        {{ session('failed') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>