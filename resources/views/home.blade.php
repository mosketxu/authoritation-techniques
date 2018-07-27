@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

                    {{--  Voy a colocar un enlace para que el usuarios que sea admin pueda ir al admin_dashboars,
                    pero que el que no lo sea no pueda
                    Para ello debo aplicar lógica al modelo User, aunque primero hago una prueba.
                    Creo una prueba unitaria php artisan make:test UserTest --unit  Ver allí detalles
                    Para usar el método isAdmin() lo he creado antes en el modelo User
                    Duilio explica en https://styde.net/separando-la-logica-de-autorizacion-de-nuestras-vistas-y-base-de-datos/
                    en el minuto 08:08 cómo crear el condicional mediante directivas sustituyendolo por @admin y definiendolo en el ServiceProvider
                    Pero paso
                    --}}
                    @if (auth()->user()->isAdmin())
                        <a href="{{route('admin_dashboard')}}">
                            Ir al admin
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
