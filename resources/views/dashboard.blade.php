@extends('layouts.app')

@section('content')

<div class="content-wrapper">
    <section class="content">
        <h1>Welcome to the GRH App</h1>

        <div id="details" class="basic-1 bg-gray">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-xl-4">
                        
                    </div> 
                    <div class="col-lg-4 col-xl-4">
                        <div class="image-container">
                            <img class="img-fluid" src="{{ asset('assets/img/details-1.svg') }}">
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </section>
</div>

@endsection