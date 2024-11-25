@extends('layouts.app')

@section('aside')
<x-navbar.main :active="$template_url"></x-navbar.main>
@endsection

@php use App\Utils\Numbers; @endphp

@section('content')


<h2>
    <x-button.add href="{{ $template_url }}/create"></x-button.add>
    Liste des Réceptions de Test
</h2>

@include('includes.message')

<x-table>
    <thead>
        <tr>
            <th> Actions </th>
            <th> Titre </th>
            <th> Poste </th>
            <th> Objectif </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>
                <x-button.delete href="{{ $template_url }}/{{ $row->id }}"></x-button.delete>
                <a class="btn btn-secondary text-info" href="{{ $template_url }}/pdf/{{ $row->id }}"> <i class="fa fa-file-pdf"></i> </a>
            </td>

            <td> {{ $row->title }} </td>
            <td> {{ $row->need }} </td>
            <td> {{ $row->goal }} </td>
        </tr>
        @endforeach
    </tbody>
</x-table>
@endsection
