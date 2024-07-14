@props(['id', 'required' => true])
<label for="{{ $id }}" class="form-label fw-semibold text-sm">{{ $slot }} @if ($required)
    <sup class="text-danger">*</sup>
    @endif</label>