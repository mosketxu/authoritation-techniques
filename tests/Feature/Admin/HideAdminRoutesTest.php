<?php

namespace Tests\Feature\Admin;

use App\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HideAdminRoutesTest extends TestCase
{
    /*  it_does_not_allow_guests_to_discover_admin_urls()
        Permite prepararlo todo para que no se sepa qué rutas de admin existen si no es admin
        Llamo a una url que no sea válida.
        Pruebo que pasa en el explorador si intento acceder a una url valida sin ser admin
            En este caso me devuelve un error 302 y redirigir al login
        Pruebo que pasa en el explorador si intento acceder a una url NO valida sin ser admin
            En este caso me devuelve un error 404 y me manda a la url de pagina no encontrada
        Debo montármelo para que devuelva lo mismo así no sé por qué falla haciendo consistente el error
        Podría hacer que en todos los casos me devuelva el error 404 pero no mola porque pierdo una funcionalidad de Laravel que consiste en
        que si intento acceder a una pagina de administrador sin estar logado, cuando me logo al ser redirigido al login se abre la página que pedia anteriormente.
        Entonces lo que hago es que siempre sea redirigido a la pantalla de inicio de sesion: status 302 y redireccion a login
            $this->get('admin/invalid-url')
                ->assertStatus(302)
                ->assertRedirect('login');

            ERROR: me devuelve el estatus 404 cuando espera el 302
            SOLUCION: Voy a admin.php y declaro una ruta que capturará todas las URL´s disponibles. Ver detalla allí
    */

  /** @test **/
  public function it_does_not_allow_guests_to_discover_admin_urls()
  {
        $this->get('admin/invalid-url')
        ->assertStatus(302)
        ->assertRedirect('login');
    }

    /*  it_does_not_allow_guests_to_discover_admin_urls_using_post
        Como la de arriba pero con POST
        ERROR: devuelve un 405 cuando espero un 302.
        SOLUCION: Es porque en admin tengo fallback y no funciona para POST. Así que modifico la logica de la ruta de admin.php
        y creo una ruta con el metodo any(). Ver detalles en admin.php
    */

    /** @test **/
  public function it_does_not_allow_guests_to_discover_admin_urls_using_post()
  {
        $this->post('admin/invalid-url')
        ->assertStatus(302)
        ->assertRedirect('login');
    }


    /*it_displays_404_when_admins_visit_invalid_urls
        primero creo un admin con model factory
        $admin=factory(User::class)->create([
            'admin'=>true;
        ]);
        repito la prueba anterior usando el metodo actingAs
        Espero un error 404 y no redirijo
        ERROR: recibo un estatus 200 en lugar del 404 que estoy esperando
        SOLUCION: voy a admin.php y añado logica al fallback que despues será un any()
    */

  /** @test **/
  public function it_displays_404_when_admins_visit_invalid_urls()
  {
        $admin=factory(User::class)->create([
            'admin'=>true,
        ]);
        $this->actingAs($admin)
            ->get('admin/invalid-url')
            ->assertStatus(404);
    }
}
