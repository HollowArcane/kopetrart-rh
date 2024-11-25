@extends('layouts.app')

@section('aside')
<x-navbar.main :active="$template_url"></x-navbar.main>
@endsection

@php
    $contents = old('contents') ?? $parts?->map(fn($part) => $part->content) ?? [''];
    $durations = old('durations') ?? $parts?->map(fn($part) => $part->duration) ?? [''];

    $count = min(count($contents), count($durations));
@endphp

@section('content')

<x-form.main :action="$form_action" :method="$form_method">
    <h2> {{ $form_title }} </h2>

    <h5> Titre </h5>
    <p> {{ $item->title }} </p>

    <h5> Objectif </h5>
    <p> {{ $item->goal }} </p>

    <h3> <button class="btn btn-secondary" type="button" onclick="createPart()"> <i class="fa fa-add"></i> </button> Parties </h3>
    <div id="parts" class="bordered rounded p3 mb-3">
        @for ($i = 0; $i < $count; $i++)

            <div id="part-{{ $i }}">
                <h5>
                    <button class="btn btn-secondary text-danger" onclick="removePart('part-{{ $i }}')">
                        <i class="fa fa-trash"></i>
                    </button>
                    <span>Partie {{ $i + 1 }}</span>
                </h5>
                <div class="mb-3">
                    <label for="contents-{{ $i }}"> Contenu </label>
                    <textarea
                        id="contents-{{ $i }}" name="contents[]"
                        class="form-control @error('contents.'.$i) mb-3 is-invalid @enderror"
                    > {{ $contents[$i] ?? '' }} </textarea>
                    <div class="invalid-feedback"> {{ $errors->first('contents.'.$i) }} </div>
                </div>
                <div class="mb-3">
                    <label for="durations-{{ $i }}"> Durée </label>
                    <input
                        id="durations-{{ $i }}" name="durations[]" value="{{ $durations[$i] ?? '' }}"
                        class="form-control @error('durations.'.$i) mb-3 is-invalid @enderror"
                        type="number"
                    />
                    <div class="invalid-feedback"> {{ $errors->first('durations.'.$i) }} </div>
                </div>
            </div>
        @endfor
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ $template_url }}/form1" class="btn btn-lg btn-secondary"> Précédent </a>
        <x-button.primary> Suivant </x-button.primary>
    </div>
</x-form.main>

<script>
function createPart()
{
    const i = document.getElementById('parts').childElementCount;
    // Create main container
    const partDiv = document.createElement('div');
    partDiv.id = `part-${i}`;

    // Create header (h5) with button and text
    const header = document.createElement('h5');

    const button = document.createElement('button');
    button.className = 'btn btn-secondary text-danger';
    button.onclick = () => removePart(`part-${i}`);

    const icon = document.createElement('i');
    icon.className = 'fa fa-trash';
    button.appendChild(icon);

    const span = document.createElement('span');
    span.textContent = `Partie ${i + 1}`;

    header.appendChild(button);
    header.appendChild(span);

    // Create content textarea section
    const contentDiv = document.createElement('div');
    contentDiv.className = 'mb3';

    const contentLabel = document.createElement('label');
    contentLabel.textContent = 'Contenu';
    contentLabel.htmlFor = `contents-${i}`;

    const textarea = document.createElement('textarea');
    textarea.id = `contents-${i}`;
    textarea.name = 'contents[]';
    textarea.className = 'form-control';

    const contentFeedback = document.createElement('div');
    contentFeedback.className = 'invalid-feedback';

    contentDiv.appendChild(contentLabel);
    contentDiv.appendChild(textarea);
    contentDiv.appendChild(contentFeedback);

    // Create duration input section
    const durationDiv = document.createElement('div');
    durationDiv.className = 'mb3';

    const durationLabel = document.createElement('label');
    durationLabel.textContent = 'Durée'
    durationLabel.htmlFor = `durations-${i}`;

    const input = document.createElement('input');
    input.id = `durations-${i}`;
    input.name = 'durations[]';
    input.className = 'form-control';
    input.type = 'number';

    const durationFeedback = document.createElement('div');
    durationFeedback.className = 'invalid-feedback';

    durationDiv.appendChild(durationLabel);
    durationDiv.appendChild(input);
    durationDiv.appendChild(durationFeedback);

    // Assemble all parts
    partDiv.appendChild(header);
    partDiv.appendChild(contentDiv);
    partDiv.appendChild(durationDiv);

    document.getElementById('parts').append(partDiv);
}

// Usage example:
function addNewPart()
{
    const container = document.getElementById('parts-container'); // Assuming you have a container
    const partCount = container.children.length;
    const newPart = createPart(partCount);
    container.appendChild(newPart);
}

// Remove part function
function removePart(partId)
{
    const element = document.getElementById(partId);
    if (element) {
        element.remove();
        // Optionally reorder the remaining parts
        reorderParts();
    }
}

// Optional: Reorder remaining parts after removal
function reorderParts()
{
    const container = document.getElementById('parts-container');
    const parts = container.children;
    Array.from(parts).forEach((part, index) => {
        part.id = `part-${index}`;
        const span = part.querySelector('span');
        span.textContent = `Partie ${index + 1}`;
    });
}
</script>
@endsection
