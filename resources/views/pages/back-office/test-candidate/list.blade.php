@extends('layouts.app')

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

$session = session();
@endphp

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
            <th> Candidat </th>
            <th> Poste Demandé </th>
            <th> Date de Réception </th>
            <th> Titre </th>
            <th> Score </th>
            <th> État </th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>
                @if ($session->get('role') == 2 && !isset($row->date_validated))
                    <x-button.edit href="{{ $template_url }}/{{ $row->id }}/edit"></x-button.edit>
                    <x-button.delete href="{{ $template_url }}/{{ $row->id }}"></x-button.delete>
                @endif
                @if ($session->get('role') == 4  && isset($row->date_validated))
                    @if ($row->is_communication_send)
                        <div class="badge badge-success">Envoyé</div>
                    @else
                        <a href="{{ route('test-candidate.show', $row->id) }}" class="btn-action">
                            Informer
                        </a>
                    @endif
                @endif
            </td>

            <td> {{ $row->candidate_first_name }} {{ $row->candidate_last_name }} </td>
            <td> {{ $row->need }} </td>
            <td> {{ $row->date_received }} </td>
            <td> {{ $row->test }} </td>
            <td align="right"> {{ Numbers::format($row->score, 2) }}/5 </td>
            <td align="center"> <span class="badge bg-{{ $result_states[$row->id_result] }}">{{ $row->result }}</span> </td>
        </tr>
        @endforeach
    </tbody>
</x-table>
@endsection
