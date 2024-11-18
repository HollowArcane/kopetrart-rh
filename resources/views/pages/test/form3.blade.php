@extends('layouts.app')

@section('aside')
<x-navbar.main :active="$template_url"></x-navbar.main>
@endsection

@php
    $point_labels = old('point-labels') ?? [''];
    $id_importances = old('id-importances') ?? [''];
    $criterion_labels = old('criterion-labels') ?? [''];
    $coefficients = old('coefficients') ?? [''];

    $count1 = min(count($point_labels), count($id_importances));
    $count2 = min(count($criterion_labels), count($coefficients));
@endphp

@section('content')

<x-form.main :action="$form_action" :method="$form_method">
    <h2> {{ $form_title }} </h2>

    <h5> Titre </h5>
    <p> {{ $item->title }} </p>

    <h5> Objectif </h5>
    <p> {{ $item->goal }} </p>

    <h3> <button class="btn btn-secondary" type="button" onclick="createTestPoint()"> <i class="fa fa-add"></i> </button> Points d'Attention Particulières </h3>
    <div id="points" class="bordered rounded p3 mb-3">
        @for ($i = 0; $i < $count1; $i++)
            <div id="test-point-{{ $i }}">
                <div class="row mb-3">
                    <div class="col-1">
                        <button class="btn btn-secondary text-danger" onclick="removePart('test-point-{{ $i }}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="col-7">
                        <input
                            id="point-label-{{ $i }}" name="point-labels[]" value="{{ $point_labels[$i] ?? '' }}"
                            class="form-control @error('point-labels.'.$i) is-invalid mb-3 @enderror"
                            type="text"
                        />
                    </div>
                    <div class="col-4">
                        <select
                            id="id-importance-{{ $i }}" name="id-importances[]"
                            class="form-control @error('id-importances.'.$i) is-invalid mb-3 @enderror"
                        >
                        @foreach ($test_point_importances as $k => $v)
                            <option value="{{ $k }}" {{ $k == $id_importances[$i] ? 'selected': '' }} > {{ $v }} </option>
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endfor
    </div>


    <h3> <button class="btn btn-secondary" type="button" onclick="createCriterion()"> <i class="fa fa-add"></i> </button> Critères d'Évaluation Globaux </h3>
    <div id="criteria" class="bordered rounded p3 mb-3">
        @for ($i = 0; $i < $count2; $i++)
            <div id="test-criterion-{{ $i }}">
                <div class="row mb-3">
                    <div class="col-1">
                        <button class="btn btn-secondary text-danger" onclick="removePart('test-criterion-{{ $i }}')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="col-7">
                        <input
                            id="criterion-label-{{ $i }}" name="criterion-labels[]" value="{{ $criterion_labels[$i] ?? '' }}"
                            class="form-control @error('criterion-labels.'.$i) is-invalid mb-3 @enderror"
                            type="text"
                        />
                    </div>
                    <div class="col-4">
                        <input
                            id="coefficient-{{ $i }}" name="coefficients[]" value="{{ $coefficients[$i] ?? '' }}"
                            class="form-control @error('coefficients.'.$i) is-invalid mb-3 @enderror"
                            type="number"
                        />
                    </div>
                </div>
            </div>
        @endfor
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ $template_url }}/form2" class="btn btn-lg btn-secondary"> Précédent </a>
        <x-button.primary> Terminer </x-button.primary>
    </div>
</x-form.main>

<script>
const pointsNode = document.getElementById('test-point-0').cloneNode(true);
const criteraNode = document.getElementById('test-criterion-0').cloneNode(true);

function createTestPoint()
{
    const parent = document.getElementById('points');
    const i = parent.childElementCount;

    const newNode = pointsNode.cloneNode(true);

    newNode.id = `test-point-${ i }`;
    newNode.querySelector('button').onclick = removePart(`point-${ i }`);

    const input = newNode.querySelector('input');
    input.id = `point-label-${ i }`;
    input.classList.remove('mb-3');
    input.classList.remove('mb-3');

    const select = newNode.querySelector('select');
    select.id = `id-importance-${ i }`;
    select.classList.remove('is-invalid');
    select.classList.remove('mb-3');

    parent.appendChild(newNode);
}

function createCriterion()
{
    const parent = document.getElementById('criteria');
    const i = parent.childElementCount;

    const newNode = criteraNode.cloneNode(true);
    newNode.id = `test-criterion-${ i }`;
    newNode.querySelector('button').onclick = removePart(`point-${ i }`);

    let input = newNode.querySelector('input[type="text"]');
    input.id = `criterion-label-${ i }`;
    input.classList.remove('mb-3');
    input.classList.remove('is-invalid');

    input = newNode.querySelector('input[type="number"]');
    input.id = `coefficient-${ i }`;
    input.classList.remove('mb-3');
    input.classList.remove('is-invalid');


    parent.appendChild(newNode);
}

// Remove part function
function removePart(partId)
{
    const element = document.getElementById(partId);
    if (element)
    { element.remove(); }
}

</script>
@endsection
