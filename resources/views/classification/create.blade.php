@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header text-black">
            <h2>Additionnal CV Data</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('classification.store') }}" method="POST">
                @csrf

                <!-- CV Selection -->
                <div class="form-group mb-3">
                    <label for="id_cv" class="form-label">Select CV</label>
                    <select name="id_cv" id="id_cv" class="form-select" required>
                        <option value="">-- Select CV --</option>
                        @foreach($cvs as $cv)
                            <option value="{{ $cv->id }}" data-name="{{ $cv->dossier->candidat }}" data-poste="{{ $cv->dossier->besoinPoste->poste->libelle }}" data-date="{{ $cv->dossier->date_reception }}">
                                {{ $cv->id }} - {{ $cv->dossier->candidat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Read-Only Fields -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Candidat Name</label>
                        <input type="text" id="candidat_name" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Poste</label>
                        <input type="text" id="poste" class="form-control" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date Depot Dossier</label>
                        <input type="text" id="date_depot_dossier" class="form-control" readonly>
                    </div>
                </div>

                <!-- Checkboxes for Interests, Qualities, and Educations -->
                <div class="mb-3">
                    <label class="form-label">Interests</label>
                    <div class="form-check">
                        @foreach($interests as $interest)
                            <div class="form-check mb-1">
                                <input type="checkbox" name="interests[]" value="{{ $interest->id }}" class="form-check-input">
                                <label class="form-check-label">{{ $interest->label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Qualities</label>
                    <div class="form-check">
                        @foreach($qualities as $quality)
                            <div class="form-check mb-1">
                                <input type="checkbox" name="qualities[]" value="{{ $quality->id }}" class="form-check-input">
                                <label class="form-check-label">{{ $quality->label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Education</label>
                    <div class="form-check">
                        @foreach($educations as $education)
                            <div class="form-check mb-1">
                                <input type="checkbox" name="educations[]" value="{{ $education->id }}" class="form-check-input">
                                <label class="form-check-label">{{ $education->label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Experiences Section -->
                <div class="mb-3">
                    <label class="form-label">Experiences</label>
                    <div id="experience-container">
                        <div class="row g-2 experience-entry mb-2">
                            <div class="col-md-6">
                                <input type="text" name="experiences[0][label]" class="form-control" placeholder="Experience Label">
                            </div>
                            <div class="col-md-6">
                                <input type="number" name="experiences[0][month_duration]" class="form-control" placeholder="Duration (months)">
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-experience" class="btn btn-outline-secondary mt-2">Add Another Experience</button>
                </div>

                <button type="submit" class="btn btn-success mt-4">Save</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('id_cv').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('candidat_name').value = selectedOption.dataset.name || '';
        document.getElementById('poste').value = selectedOption.dataset.poste || '';
        document.getElementById('date_depot_dossier').value = selectedOption.dataset.date || '';
    });

    let experienceCount = 1;
    document.getElementById('add-experience').addEventListener('click', function () {
        const container = document.getElementById('experience-container');
        const newEntry = document.createElement('div');
        newEntry.classList.add('row', 'g-2', 'experience-entry', 'mb-2');
        newEntry.innerHTML = `
            <div class="col-md-6">
                <input type="text" name="experiences[${experienceCount}][label]" class="form-control" placeholder="Experience Label">
            </div>
            <div class="col-md-6">
                <input type="number" name="experiences[${experienceCount}][month_duration]" class="form-control" placeholder="Duration (months)">
            </div>
        `;
        container.appendChild(newEntry);
        experienceCount++;
    });
</script>
@endsection