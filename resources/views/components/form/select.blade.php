<div class="mb-3">
    <label class="form-label" for="{{ $name }}"> {{ $slot }} </label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-select @error($name) is-invalid @enderror"
    >
    @php $value = $value ?? ''; @endphp
    @foreach ($options ?? [] as $key => $option)
        <option {{ $key == $value ? 'selected': '' }} value="{{ $key }}"> {{ ucFirst($option) }} </option>
    @endforeach
    </select>
    <div class="invalid-feedback"> {{ $errors->first($name) }} </div>
</div>