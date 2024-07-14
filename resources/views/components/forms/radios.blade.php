@props(['id', 'name', 'items' => [], 'valueDefaultChecked' => ''])

<div class="d-flex align-items-center flex-wrap" style="row-gap: 10px; column-gap: 20px;">
    @foreach ($items as $key => $value)
    <div class="form-check">
        <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name . $loop->iteration}}"
            @checked($valueDefaultChecked===$key) value="{{ $key }}" />
        <label class="form-check-label" for="{{ $name . $loop->iteration}}">
            {{ $value }}
        </label>
    </div>
    @endforeach
</div>

@error($name)
<small id="{{ $id ?? $name }}" class="text-danger">
    {{ $message }}
</small>
@enderror