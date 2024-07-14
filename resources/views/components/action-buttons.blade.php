@props(['index', 'for' => 'create', 'edit' => ''])

@if ($for == 'create')
<div class="d-flex justify-content-end py-3">
    <div class="btn-group">
        <button type="submit" class="btn btn-dark px-3 py-2">
            <x-feather name="save" class="me-1" />
            Simpan
        </button>
        <button type="button" class="btn btn-outline-dark px-3 py-2 dropdown-toggle dropdown-toggle-split"
            data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-2 rounded-3 mx-0 shadow">
            <li>
                <button class="dropdown-item rounded-2" name="no-redirect" value="true">
                    <x-feather name="plus-circle" class="me-1" />
                    Simpan & Buat Baru
                </button>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <button type="reset" class="dropdown-item rounded-2 mb-1 w-100">
                    <x-feather name="refresh-cw" class="me-2" />
                    Reset
                </button>
            </li>
            <li>
                <a class="dropdown-item rounded-2 w-100 mb-1 text-bg-danger" href="{{ $index }}">
                    <x-feather name="arrow-left" class="me-2" />
                    Batal
                </a>
            </li>
        </ul>
    </div>

</div>
@endif

@if ($for == 'edit')
<div class="d-flex justify-content-end py-3">
    <div class="btn-group">
        <button type="submit" class="btn btn-dark px-3 py-2">
            <x-feather name="save" class="me-1" />
            Simpan
        </button>
        <button type="button" class="btn btn-outline-dark px-3 py-2 dropdown-toggle dropdown-toggle-split"
            data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-2 rounded-3 mx-0 shadow">
            <li>
                <a class="dropdown-item rounded-2 w-100 mb-1 text-bg-danger" href="{{ $index }}">
                    <x-feather name="arrow-left" class="me-2" />
                    Batal
                </a>
            </li>
        </ul>
    </div>
</div>
@endif

@if ($for == 'show')
<div class="d-flex justify-content-end py-3">
    <div class="dropdown">
        <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Aksi
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-2 rounded-3 mx-0 shadow">
            <li>
                <a class="dropdown-item rounded-2 w-100 mb-1" href="{{ $edit }}">
                    <x-feather name="edit-3" class="me-2" />
                    Edit
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item rounded-2 w-100 mb-1 text-bg-danger" href="{{ $index }}">
                    <x-feather name="arrow-left" class="me-2" />
                    Batal
                </a>
            </li>
        </ul>
    </div>
</div>
@endif

@if ($for == 'custom')
<div class="d-flex justify-content-end py-3">
    <div class="dropdown">
        <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Aksi
        </button>
        <ul class="dropdown-menu dropdown-menu-end p-2 rounded-3 mx-0 shadow">
            {{ $slot }}
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item rounded-2 w-100 mb-1 text-bg-danger" href="{{ $index }}">
                    <x-feather name="arrow-left" class="me-2" />
                    Batal
                </a>
            </li>
        </ul>
    </div>
</div>
@endif