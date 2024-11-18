<div class="mb-3">
    <label class="form-label" for="{{ $name }}"> {{ $slot }} </label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $value ?? 0 }}"
        class="form-range @error($name) is-invalid @enderror"
        type="range"
        min="{{ $min ?? 0 }}"
        max="{{ $max ?? 10 }}"
        step="{{ $step ?? 1 }}"
    />
    <div class="invalid-feedback"> {{ $errors->first($name) }} </div>
</div>