@extends('templates.home')

@section('content')
<div class="container">
    <x-form.main :method="$form_method" :action="$form_action">
        <div class="bg-white p-5">
            <h1 class="mb-3"> {{ $form_title }} </h1>

            <x-form.select name="id-poste" :options="$postes"> Poste </x-form.select>
            <x-form.input name="cv" type="file"> CV </x-form.input>
            <x-form.input name="motivation-letter" type="file"> Lettre de Motivation </x-form.input>

            <x-button.primary> Déposer </x-button.primary>
        </div>
    </x-form.main>
</div>
@endsection
