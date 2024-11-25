<div class="mb-4">
    <label class="form-label" for="{{ $name }}"> {{ $slot }} </label>
    <input
        data-mdb-input-init
        class="form-outline form-control mb-2 @error($name) is-invalid @enderror"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value ?? '') }}"
        type="{{ $type ?? 'text' }}"
        placeholder="{{ $placeholder ?? '' }}"
    />
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
