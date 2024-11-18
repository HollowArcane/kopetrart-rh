@extends('templates.test')

@section('content')
<div class="container">
    <x-form.main :method="$form_method" :action="$form_action">
        <div class="mb-4 bg-white p-5 shadow-3">
            <div class="d-flex justify-content-between">
                <div>
                    <h1 class= mb-3">{{ $test->title }}</h2>
                    <p class="text-muted"><strong>Objectif:</strong> {{ $test->goal }}</p>
                </div>
                <div class="bg-info bg-opacity-25 rounded px-3 d-flex align-items-center justify-content-center">
                    <p class="text-primary p-0 m-0"><strong>Durée Totale:</strong> {{ $test->duration }} mn</p>
                </div>
            </div>
            <hr>

            @if ($test->file != null)
                <a class="btn btn-secondary text-primary" href="/test-candidate-file/attachment/{{ $test->id }}"> Télécharger le Fichier Associé </a>
            @endif

        </div>

        {{-- Test Parts --}}
        <div class="bg-white shadow-3 p-3 mb-3">
            @foreach($test->parts as $i => $part)
            <div class="bg-white border rounded p-3 mb-3">
                <h4 class="m-0 p-1 fw-bold">Partie {{ $i + 1 }}</h4>
                <p class="m-0 p-0 mb-4 lead">{{ $part->content }}</p>
                @if ($test->is_qna)
                    <div class="px-5">
                        <textarea
                            data-mdb-input-init
                            name="response[{{ $i }}]"
                            id="response[{{ $i }}]"
                            class="form-outline form-control mb-2 @error('response.'.$i) is-invalid @enderror"
                        >{{ old('response.'.$i) }}</textarea>
                        <div class="invalid-feedback"> {{ $errors->first('response.'.$i) }} </div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        @if (!$test->is_qna)
            <div class="my-3 bg-white rounded p-4">
                <x-form.input type="file" name="file"> Déposez Votre Fichier Ici </x-form.input>
                <x-button.primary> Terminer </x-button.primary>
            </div>
        @else
            <x-button.primary> Terminer </x-button.primary>
        @endif

    </x-form.main>
</div>
@endsection
