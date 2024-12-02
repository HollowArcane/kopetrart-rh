<div class="mb-3">
    <label class="form-label" for="{{ $name }}"> {{ $slot }} </label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-control mb-2 @error($error ?? $name) is-invalid @enderror"
        placeholder="{{ $placeholder ?? '' }}"
    >{{ $value ?? '' }}</textarea>
    <div class="invalid-feedback"> {{ $errors->first($error ?? $name) }} </div>
</div>
