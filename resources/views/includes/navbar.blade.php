<ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-heading">GRH Application</li>

    <!-- Session Info -->
    @php
        $session = session();
    @endphp
    <div class="session-info d-flex align-items-center">
        <i class="mr-2 fas fa-user-circle fa-2x"></i>
        <p class="mb-0 role-name">
            <span>
                <i class="fas fa-crown text-warning"></i> {{ session('role_name') }}
            </span>
        </p>
    </div>


    <!-- Besoin en Talent Section -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#besoin_poste-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-person-plus"></i><span>Besoin en Talent</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="besoin_poste-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ url('besoin_poste') }}">
                    <i class="bi bi-circle"></i><span>Besoin Poste</span>
                </a>
            </li>
            @if ($session->get('role') == 2)
            <li>
                <a href="{{ url('besoin_poste/create') }}">
                    <i class="bi bi-circle"></i><span>Form Besoins Poste</span>
                </a>
            </li>
            @endif
        </ul>
    </li>

    <!-- Employe Section -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#employe-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people"></i><span> Personnel </span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="employe-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="/staff">
                    <i class="bi bi-circle"></i><span>Employe</span>
                </a>
            </li>
            <li>
                <a href="/candidate">
                    <i class="bi bi-circle"></i><span>Candidat</span>
                </a>
            </li>

            <!-- STAFF -->
            <li>
                <a href="{{ route('absences.index') }}">
                    <i class="bi bi-circle"></i><span>Absence</span>
                </a>
            </li>

            <li>
                <a href="{{ route('staff_overtimes.index')}}">
                    <i class="bi bi-circle"></i><span>Heure supplémentaire</span>
                </a>
            </li>

            <li>
                <a href="{{ route('staff_compensations.index')}}">
                    <i class="bi bi-circle"></i><span>Indémnité</span>
                </a>
            </li>

            <li>
                <a href="{{ route('performance_bonuses.index')}}">
                    <i class="bi bi-circle"></i><span>Bonus de performance</span>
                </a>
            </li>

            <li>
                <a href="{{ route('impot_dues.index')}}">
                    <i class="bi bi-circle"></i><span>Impot Du</span>
                </a>
            </li>

            <li>
                <a href="{{ route('salary_advances.index')}}">
                    <i class="bi bi-circle"></i><span>Avance salarial</span>
                </a>
            </li>
        </ul>
    </li>
    <!-- Vacation Section -->
    @if (in_array(session('role'), [3 /* RE */, 1 /* PDG */]))
    <li class="nav-item">
        <a class="nav-link collapsed" href="/staff-vacation">
            <i class="bi bi-people"></i><span> Congé </span></i>
        </a>
    </li>
    @endif
    <!-- Contract Section -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#contract-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people"></i><span> Contrat </span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="contract-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="/contract-breach">
                    <i class="bi bi-circle"></i><span> Rupture de Contrat </span>
                </a>
            </li>
        </ul>
    </li>

    <!-- Promotion Section -->
    @if ($session->get('role') == 3 || $session->get('role') == 1)
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#promotion-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-arrow-up-right-circle"></i><span>Promotion</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="promotion-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ url('promotion') }}">
                    <i class="bi bi-circle"></i><span>Liste Promotion</span>
                </a>
            </li>
        </ul>
    </li>
    @endif

    <!-- Dossiers Section -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#dossiers-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-folder"></i><span>Dossiers</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="dossiers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ url('dossiers') }}">
                    <i class="bi bi-circle"></i><span>Dossiers</span>
                </a>
            </li>
            @if ($session->get('role') == 5)
            <li>
                <a href="{{ url('dossiers/create') }}">
                    <i class="bi bi-circle"></i><span>Ajout</span>
                </a>
            </li>
            @endif
        </ul>
    </li>

    <!-- Cv Section -->
    @if ($session->get('role') == 5 || $session->get('role') == 2)
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#cv-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-earmark-person"></i><span>Curriculum Vitae</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="cv-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ url('cv') }}">
                    <i class="bi bi-circle"></i><span>Liste</span>
                </a>
            </li>
        </ul>
    </li>
    @endif

    <!-- Entretien Section -->
    @if ($session->get('role') == 4 || $session->get('role') == 2)
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#entretien-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-chat-dots"></i><span>Entretien</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="entretien-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ url('entretien') }}">
                    <i class="bi bi-circle"></i><span>Liste</span>
                </a>
            </li>
        </ul>
    </li>
    @endif

    <!-- Test Section -->
    @if ($session->get('role') == 2 || $session->get('role') == 5 || $session->get('role') == 3 || $session->get('role') == 4)
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#test-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-earmark-text"></i><span>Test</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="test-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            @if ($session->get('role') == 3)
            <li>
                <a href="{{ url('test/create') }}">
                    <i class="bi bi-circle"></i><span>Questionnaire</span>
                </a>
            </li>
            @endif
            @if ($session->get('role') == 2 || $session->get('role') == 4)
            <li>
                <a href="{{ url('test-candidate') }}">
                    <i class="bi bi-circle"></i><span>Liste</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    <!-- Annonce Section -->
    @if ($session->get('role') == 4)
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#annonce-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-broadcast"></i><span>Annonce</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="annonce-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ url('annonce') }}">
                    <i class="bi bi-circle"></i><span>Annonce</span>
                </a>
            </li>
            <li>
                <a href="{{ url('publicite') }}">
                    <i class="bi bi-circle"></i><span>Publicite</span>
                </a>
            </li>
        </ul>
    </li>
    @endif

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#annonce-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-earmark-bar-graph"></i><span>Prediction CV</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="annonce-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('classification.create') }}">
                    <i class="bi bi-plus-circle"></i><span>Create</span>
                </a>
            </li>
            <li>
                <a href="{{ route('classification.index') }}">
                    <i class="bi bi-plus-circle"></i><span>Liste</span>
                </a>
            </li>
        </ul>
    </li>
    
    <!-- PAYROLL -->
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#staff-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-wallet"></i><span>Paiement Employé</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="staff-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('payroll.index') }}">
                    <i class="bi bi-plus-circle"></i><span>Etat de paie</span>
                </a>
            </li>
        </ul>
    </li>    

    <!-- Logout -->
    <div class="dropdown">
        <li>
            <a style="display: block; padding: 8px; color:red" href="{{ url('logout') }}">
                <i class="fa fa-sign-out" style="margin-right: 3.6px; color:red"></i>
                <span>Logout</span>
            </a>
        </li>
    </div>
</ul>
