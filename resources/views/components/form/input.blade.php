<div class="mb-4">
    <label class="form-label" for="{{ $name }}"> {{ $slot }} </label>
    <input
        data-mdb-input-init
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ $value ?? '' }}"
        class="form-outline form-control mb-2 @error($name) is-invalid @enderror"
        type="{{ $type ?? 'text' }}"
        placeholder="{{ $placeholder ?? '' }}"
    />
    <div class="invalid-feedback"> {{ $errors->first($name) }} </div>
</div>
