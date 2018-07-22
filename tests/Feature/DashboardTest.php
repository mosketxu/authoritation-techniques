<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function it_shows_the_dashboard_page_to_authenticated_users()
    {
        /* Pasos:
            Creo un nuevo usuario con model factory
            Uso el metodo actingAs para indicarle a laravel que quiero actuar como el usuario creado
            y me dirijo a la pagina Home
            y compruebo que puedo ver el titulo Dashboard
            y compruebo que me devuelve el estatus 200, es decir que la página ha cargado con exito.
            La prueba pasa.
            Pero vamos a ver qué pasa si intento acceder a la pagina sin haberme conectado: quito $this->actingAs($user)
                Entonces falla porque Laravel intenta redirigirme a la pagina de inicio de sesion y ahí no está la plabra Dashboard
                Para verificar lo mismo puedo cambiar el orden y poner assertStatus antes de assertSee
                En este caso tendre un error porque tendre u estatus de redireccion temporal 302 en lugar del 200
            Lo dejo como estaba porque veo que va bien
            Pero voy a hacer una prueba para verificar que pasa cuando el usuario no esta autenticado
         */

        $user=factory(User::Class)->create();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertSee('Dashboard')
            ->assertStatus(200);
    }

    /** @test **/
    public function it_redirects_guest_users_to_the_login_page()
    {
        /* Pasos:
            y me dirijo a la pagina Home
            y compruebo que puedo ver el titulo Login
            y compruebo que me devuelve el estatus 302, es decir que la página se ha redirigido temporalmente.
            La prueba pasa.
            Pero vamos a ver qué pasa si intento acceder a la pagina sin haberme conectado: quito $this->actingAs($user)
                Entonces falla porque Laravel intenta redirigirme a la pagina de inicio de sesion y ahí no está la plabra Dashboard
                Para verificar lo mismo puedo cambiar el orden y poner assertStatus antes de assertSee
                En este caso tendre un error porque tendre u estatus de redireccion temporal 302 en lugar del 200
            Lo dejo como estaba porque veo que va bien
            Pero voy a hacer una prueba para verificar que pasa cuando el usuario no esta autenticado
         */

        $user=factory(User::Class)->create();

        $this->get(route('home'))
            ->assertSee('login')
            ->assertStatus(302);
    }

}
