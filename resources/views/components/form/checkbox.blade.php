<div class="form-check col-md-4">
    <label class="form-check-label" for="{{ $name.'-'.$value }}"> {{ ucFirst($slot) }} </label>
    <input
        id="{{ $name.'-'.$value }}"
        name="{{ $name }}"
        value="{{ $value }}"
        class="form-check-input"
        type="checkbox"
        {{ $selected ?? '' }}
    />
</div>