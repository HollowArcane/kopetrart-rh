<div class="mb-3">
    <label class="form-label" for="{{ $name }}"> {{ $slot }} </label>
    <select
        class="form-outline form-select mb-2 @error($name) is-invalid @enderror"
        id="{{ $name }}"
        name="{{ $name }}"
    >
    @php $value = old($name, $value ?? ''); @endphp
    @foreach ($options ?? [] as $key => $option)
        <option {{ $key == $value ? 'selected': '' }} value="{{ $key }}"> {{ ucFirst($option) }} </option>
    @endforeach
    </select>
    <div class="invalid-feedback"> {{ $errors->first($name) }} </div>
</div>
