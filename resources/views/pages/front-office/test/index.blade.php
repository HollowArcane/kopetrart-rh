@extends('templates.home')

@section('aside')
<x-navbar.main :active="$template_url"></x-navbar.main>
@endsection


@php
use App\Utils\Numbers;

$result_states = [
    1 => 'success',
    2 => 'danger',
    3 => 'info'
];

@endphp

@section('content')


<h1> Liste des Tests Disponibles </h1>

@include('includes.message')

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th> Actions </th>
            <th> Titre </th>
            <th> Poste </th>
            <th> Objectif </th>
            <th> Durée </th>
            <th> État </th>
            <th> Score </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>
                @if ($row->id_test == null)
                    <a class="btn btn-secondary text-primary" href="{{ $template_url }}/{{ $row->id }}/{{ $row->id_cv }}"> <i class="fa fa-pencil"></i> </a>
                @endif
            </td>

            <td> {{ $row->title }} </td>
            <td> {{ $row->need }} </td>
            <td> {{ $row->goal }} </td>
            <td align="right"> {{ $row->duration }} mn </td>
            <td align="right"> {{ Numbers::format($row->score, 2) }}/5 </td>
            <td align="center"> <span class="badge bg-{{ $result_states[$row->id_result] ?? 'secondary' }}">{{ $row->result ?? 'Non Passé' }}</span> </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
