@if (session()->has('success'))
<div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
    <span data-feather="check-circle" class="align-text-middle me-2"></span>
    <div>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

@if (session()->has('failed'))
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
    <span data-feather="x-octagon" class="align-text-middle me-2"></span>
    <div>
        {{ session('failed') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif