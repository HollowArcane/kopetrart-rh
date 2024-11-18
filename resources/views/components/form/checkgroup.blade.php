<div class="mb-3">
    <label class="form-label"> {{ $slot }} </label>
    
    @error($name.'.*')
    <div class="alert alert-danger">
        Some checked values are invalid
    </div>
    @enderror
    
    <div class="row g-3">
    @php $values = $values ?? collect([]); @endphp

    @foreach ($options ?? [] as $key => $option)
        <x-checkbox
            :name="$name.'[]'"
            :value="$key"
            :selected="$values->contains($key) ? 'checked': ''"
        >
            {{ $option }}
        </x-checkbox>
    @endforeach
    </div>
</div>