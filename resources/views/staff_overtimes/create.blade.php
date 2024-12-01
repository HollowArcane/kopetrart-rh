@extends('layouts.app')

@section('content')
    <center>
        <h3>{{ isset($staffOvertime) ? 'Modifier' : 'Ajouter' }} un enregistrement d'heures supplémentaires par semaine</h3>
    </center>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($staffOvertime) ? route('staff_overtimes.update', $staffOvertime->id) : route('staff_overtimes.store') }}" method="POST">
        @csrf
        @if(isset($staffOvertime)) @method('PUT') @endif

        <div class="form-group">
            <label for="id_staff">Personnel</label>
            <select name="id_staff" id="id_staff" class="form-control" required>
                <option value="" disabled {{ old('id_staff', $staffOvertime->id_staff ?? '') == '' ? 'selected' : '' }}>Choisir un membre du personnel</option>
                @foreach($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ old('id_staff', $staffOvertime->id_staff ?? '') == $staff->id ? 'selected' : '' }}>
                        {{ $staff->first_name }} {{ $staff->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="id_overtime_type">Type d'heure supplémentaire</label>
            <select name="id_overtime_type" id="id_overtime_type" class="form-control" required>
                @foreach($overtimeTypes as $type)
                    <option value="{{ $type->id }}" {{ old('id_overtime_type', $staffOvertime->id_overtime_type ?? '') == $type->id ? 'selected' : '' }}>
                        {{ $type->label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="id_overtime_shift">Shift d'heure supplémentaire</label>
            <select name="id_overtime_shift" id="id_overtime_shift" class="form-control" required>
                @foreach($overtimeShifts as $shift)
                    <option value="{{ $shift->id }}" {{ old('id_overtime_shift', $staffOvertime->id_overtime_shift ?? '') == $shift->id ? 'selected' : '' }}>
                        {{ $shift->label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date_overtime">Date</label>
            <input type="date" name="date_overtime" id="date_overtime" class="form-control" value="{{ old('date_overtime', $staffOvertime->date_overtime ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="quantity_overtime">Quantité</label>
            <input type="number" name="quantity_overtime" id="quantity_overtime" class="form-control" value="{{ old('quantity_overtime', $staffOvertime->quantity_overtime ?? '') }}" step="0.1" min="0.1" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($staffOvertime) ? 'Mettre à jour' : 'Ajouter' }}</button>
    </form>
@endsection