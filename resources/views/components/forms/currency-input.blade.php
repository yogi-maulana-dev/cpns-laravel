@props(['default' => 0, 'name', 'label', 'inputClasses' => ''])

<div class="mb-3" x-data="{value: {{ old($name, $default) }}, formatted: 'Rp. @money(old($name, $default))'}">
    <x-forms.label id="{{ $name }}">{{ $label }}</x-forms.label>
    <div class="input-group">
        <span class="input-group-text">Rp.</span>
        <x-forms.input name="{{ $name }}" type="number" x-model="value" class="{{ $inputClasses }}" x-on:input="formatted = currency(value, {
                    symbol: 'Rp. ',
                    separator: '.',
                    decimal: ','
                }).format()" />
        <span class="input-group-text">.00</span>
    </div>
    <x-badge as="span" color="info" x-text="formatted"></x-badge>
</div>