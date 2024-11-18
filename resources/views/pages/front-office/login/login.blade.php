@extends('templates.page')

@section('content')
    <section class="h-100 gradient-form" style="background-color: #eee; min-height: 100dvh">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
            <div class="card rounded-3 text-black  mt-5">
            <div class="row g-0">
                <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-4">


                    <x-form.main method="POST" :action="$url">
                    <h2 align="center"> Connexion </h2>

                    @include('includes.message')
                    <x-form.input name="username"> Nom d'Utilisateur </x-form.input>
                    <x-form.input name="password" type="password"> Mot de Passe </x-form.input>

                    <div class="text-center pt-1 mb-5 pb-1">
                        <button class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3"> Se connecter </button>
                    </div>

                    <div class="d-flex align-items-center justify-content-center pb-4">
                        <p class="mb-0 me-2">Vous n'avez pas encore de compte?</p>
                        <a href="/front/register" class="btn btn-outline-danger"> S'inscrire </a>
                    </div>

                    <p class="text-center"><a href="/"> Se connecter en tant qu'administrateur </a></p>

                    </x-form.main>

                </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                    <h4 class="mb-4"> Facilitez Votre Recrutement </h4>
                    <p class="small mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
    </section>
@endsection
