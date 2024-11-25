@extends('layouts.app')

@section('aside')
<x-navbar.main :active="$template_url"></x-navbar.main>
@endsection

@php
    $qna_options = [
        'true' => 'Question - Réponse',
        'false' => 'Simulation de Projet'
    ];

    $need = old('need') ?? $item?->need ?? '';
    $title = old('title') ?? $item?->title ?? '';
    $goal = old('goal') ?? $item?->goal ?? '';
    $qna = old('qna') ?? $item?->qna ?? '';
    $requirements = old('requirements') ?? explode(';', $item?->requirements ?? '');
@endphp

@section('content')

<x-form.main :action="$form_action" :method="$form_method">
    <h2> {{ $form_title }} </h2>

    <x-form.select name="id-need" :options="$needs" :value="$need"> Besoin </x-form.select>
    <x-form.input name="title" type="text" :value="$title"> Titre </x-form.input>
    <x-form.textarea name="goal" :value="$goal"> Objectif </x-form.textarea>
    <x-form.select name="qna" :options="$qna_options" :value="$qna"> Type de Test </x-form.select>
    <x-form.input name="file" type="file"> Fichier Associé </x-form.input>

    <h5> <button class="btn btn-secondary" type="button" onclick="appendInput()"> <i class="fa fa-add"></i> </button> Requirements </h5>
    <div id="requirements" class="bordered rounded p3 mb3">
        @foreach ($requirements as $i => $requirement)
            <div class="row" id="requirement-row-{{ $i }}">
                <div class="col-1">
                    <button class="btn btn-secondary text-danger" onclick="removePart('requirement-row-{{ $i }}')">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="col-11">
                    <input
                        id="requirements-{{ $i }}" name="requirements[]" value="{{ $requirement ?? '' }}"
                        class="form-control mb-2 @error('requirements.'.$i) is-invalid @enderror"
                        type="text"
                    />
                    <div class="invalid-feedback"> {{ $errors->first('requirements.'.$i) }} </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-end">
        <x-button.primary> Suivant </x-button.primary>
    </div>
</x-form.main>

<script>
function appendInput()
{
    const i = document.getElementById('requirements').childElementCount;

    const row = document.createElement('div');
    row.className = 'row';
    row.id = `requirement-row-${ i }`;

    const col1 = document.createElement('div');
    col1.className = 'col-1';

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn btn-secondary text-danger';
    btn.onclick = function() { removePart(`requirement-row-${ i }`); };
    btn.innerHTML = '<i class="fa fa-trash"></i>';

    col1.appendChild(btn);
    row.appendChild(col1);

    const col11 = document.createElement('div');
    col11.className = 'col-11';

    const input = document.createElement('input');
    input.id ='requirements-' + i;
    input.name ='requirements[]';
    input.type = 'text';
    input.className = 'form-control mb-2';

    col11.append(input);
    row.append(col11);

    document.getElementById('requirements').appendChild(row);
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
