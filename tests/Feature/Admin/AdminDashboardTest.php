<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AdminDashboardTest extends TestCase
{

    use RefreshDatabase;

    // creo una prueba para que nuestros administradores lleguen al panel administrativo o dashboard
    // y otra para los que no lo son no puedan acceder, tanto usuarios como guest, asi que dos pruebas

    /** @test **/

    /*
        Primero creo una base de datos de prueba:
            mysql -uroot
            create database authoritation_techniques_tests;

            lo configuro en  phpunit.xml y añado una llave al final:         <env name="DB_DATABASE" value="authoritation_techniques_tests"/>

            Si ejecuto ahora las pruebas el error es peor:
            ERROR: Base table or view not found: 1146 Table 'authoritation_techniques_tests.users' doesn't exist
            SOLUCION: incluir el trait refreshdatabase al comienzo de la prueba. Aprovecho y lo incluyo dentro de la prueba DashboardTest
        Creo un usuario con model Factory indicando que es admin. Incluyo la clase User
        uso el metodo actingAs y verifico que acceda al Dashboard recibo el status 200 y encuentro el texto Admin Panel
        Lanzo las pruebas
            ERROR: No existe Admin      Column not found: 1054 Unknown column 'admin' in 'field list'
            SOLUCION: La creo en la migracion create users table    $table->boolean('admin')->default(false);  y luego php artisan migrate:refresh


            ERROR: No existe Admin      ErrorException: Use of undefined constant admin - assumed 'admin' (this will throw an Error in a future version of PHP)
            SOLUCION: se me habia olvidado poner $admin en actingAs. Habia puesto solo admin Es actingAs($admin) no actingAs(admin)

            ERROR: InvalidArgumentException: Route [admin_dashboard] not defined.
            SOLUCION: definir la ruta. Le doy un nombre a la ruta. tenia
                Route::view('admin','/admin/dashboard');
                y debo tener
                Route::view('admin','/admin/dashboard')->name('admin_dashboard');

            ERROR: No veo el texto Admin Panel en la vista
            SOLUCION: Lo pongo

            La prueba pasa, aunque no hemos puesto ninguna regla.
            Antes de verificar esto creare las otras dos pruebas a partir de esta
    */

    public function admins_can_visit_the_admin_dashboard()
    {
        $admin =factory(User::class)->create([
            'admin'=>true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin_dashboard'))
            ->assertStatus(200)
            ->assertSee('Admin Panel');
    }


    /*  Creo un usuario no admin y le digo que no es admin
        Si intento visualizar admin_dashboard debe haber un error 403, indica que la ruta esta prohibida
        es decir, la ruta puede existir, estoy conectado o logado pero no tengo permiso para verla

        ERROR:  Expected status code 403 but received 200.
                Failed asserting that false is true.
        SOLUCION: en las rutas añadimos el middleware de autentificacion.
                    No Es suficiente.
                    Modifico las rutas y en lugar del helper view uso get y una funcion anonima. Ver allí.
    */
    /** @test **/
    public function non_admins_users_cannot_visit_the_admin_dashboard()
    {
        $user =factory(User::class)->create([
            'admin'=>false,
        ]);

        $this->actingAs($user)
            ->get(route('admin_dashboard'))
            ->assertStatus(403);
    }

    /* En esta prueba no creo el usuario ni estaré conectado
        Intento acceder y deberia ver el estado 302, redireccion temporal
        en este caso a la pantalla de inicio de sesion

        ERROR:  Expected status code 403 but received 200.
                Failed asserting that false is true.
        SOLUCION: en las rutas añadimos el middleware de autentificacion. Aquí si es suficiente
    */
    /** @test **/
    public function  guests_cannot_visit_the_admin_dashboard()
    {
        $this->get(route('admin_dashboard'))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

}
