@extends('layouts.app')

@section('content')
    <h1>Etat de Paie</h1>

    <form action="{{ route('payroll.generate') }}" method="post">
        @csrf
        
        <div class="form-group">
          <label for="date-reference">Date reference</label>
          <input type="date" name="date-reference" id="date-reference" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    @if(!empty($payrollData))
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Date</th>
                <th>Matricule</th>
                <th>Num CNAPS</th>
                <th>Nom et Prénom</th>
                <th>Date d'embauche</th>
                <th>Absence du mois</th>
                <th>Categorie</th>
                <th>Fonction</th>
                <th>Salaire de base</th>
                <th>Retenue sur absence</th>
                <th>Salaire de base du mois</th>
                <th>Indemnité</th>
                <th>Rappel</th>
                <th>Autres</th>
                <th>Heure Sup Majorée</th>
                <th>Salaire brut</th>
                <th>CNAPS 1%</th>
                <th>CNAPS 8%</th>
                <th>OSTIE 1%</th>
                <th>OSTIE 8%</th>
                <th>Revenu imposable</th>
                <th>Impot Du</th>
                <th>Enfant</th>
                <th>Montant</th>
                <th>IGR Net</th>
                <th>Autres retenues</th>
                <th>Total retenues</th>
                <th>Salaire net</th>
                <th>Avance</th>
                <th>Net a payer</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrollData as $data)
                <tr>
                    <td>{{ $referenceDate }}</td>
                    <td>{{ $data['staff']->id }}</td>
                    <td>700.410.33{{ $data['staff']->id }}</td>
                    <td>{{ $data['staff']->first_name }} {{ $data['staff']->last_name }}</td>
                    <td>{{ $data['staff']->d_date_contract_start }}</td>
                    <td>{{ $data['absence_details']->total_absence_days ?? 0 }}</td>
                    <td>{{ $data['staff']->department}}</td>
                    <td>{{ $data['staff']->staff_position}}</td>
                    <td>{{ $data['staff']->d_salary }}</td>
                    <td>{{ $data['absence_details']->detention_amount ?? 0 }}</td>
                    <td>{{ $data['salary_brut']->res_base_salary }}</td>
                    <td>{{ $data['salary_brut']->res_total_compensation }}</td>
                    <td>{{ $data['salary_brut']->res_rappel_salary }}</td>
                    <td>0</td>
                    <td>{{ $data['total_heure_sup']->monthly_total_overtime_amount }}</td>
                    <td>{{ $data['salary_brut']->res_monthly_gross_salary }}</td>
                    <td>{{ $data['cnaps_ostie']->res_cnaps_amount }}</td>
                    <td>0</td>
                    <td>{{ $data['cnaps_ostie']->res_ostie_amount }}</td>
                    <td>0</td>
                    <td>{{ $data['revenue_imposable'] }}</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>{{ $data['total_retenue'] }}</td>
                    <td>{{ $data['salary_net'] }}</td>
                    <td>0</td>
                    <td>{{ $data['net_a_payer'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endsection