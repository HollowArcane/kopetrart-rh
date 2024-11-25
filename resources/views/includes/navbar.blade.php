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

    <!-- Contrat Section -->
    @if ($session->get('role') == 2)
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#contrat-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-file-earmark"></i><span>Contrat</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        {{-- <ul id="contrat-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ url('contrat') }}">
                    <i class="bi bi-circle"></i><span>Listes des contrats</span>
                </a>
            </li>
            <li>
                <a href="{{ url('contrat/showEssaiForm') }}">
                    <i class="bi bi-circle"></i><span>Essai</span>
                </a>
            </li>
        </ul> --}}
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
            <i class="bi bi-broadcast"></i><span>Prediction CV</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="annonce-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('classification.create') }}">
                    <i class="bi bi-circle"></i><span>Create</span>
                </a>
            </li>
            <li>
                <a href="{{ route('classification.index') }}">
                    <i class="bi bi-circle"></i><span>Liste</span>
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
