@php $active = $active ?? ''; @endphp

<div class="pf__navbar shadow d-md-flex d-none flex-column justify-content-between">
    <div class="pf__navbar-brand"> <h1> <a href="/"> LOGO </a></h1> </div>

    <div class="pf__navbar-section">
        <ul class="pf__navbar-list">
            <x-navbar.item href="/front/home" :active="$active"> <i class="fa-solid fa-house"></i> Acceuil </x-navbar.item>
            <x-navbar.item href="/front/test" :active="$active"> <i class="fa-solid fa-book"></i> Tests  </x-navbar.item>
        </ul>
    </div>

    <div class="pf__navbar-section mt-auto">
        <ul class="pf__navbar-list">
            <x-navbar.item href="/front" :active="$active"> <i class="fa-solid fa-right-from-bracket"></i> Se Déconnecter </x-navbar.item>
        </ul>
    </div>

</div>
