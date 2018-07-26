<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapAdminRoutes(); /* Creo este metodo como ejemplo en la leccion de autentificacion de refactoracion de rutas. Ver explicacion en web.php
                                     Una vez creo esta linea debo crear el metodo
                                    Para ello copio la logica del metodo mapWebRoutes() y lo pego debajo creando el metodo mapAdminRoutes
                                    modificando el nombre a mapWebRoutes
                                    ademas indico que quiero tambien los middleware admin y auth
                                    y modificando la ruta de /web a /web/admin
                                    el prefijo admin
                                    */

        $this->mapApiRoutes();

        $this->mapWebRoutes();


        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection,
     * REQUIERE AUTENTIFICACION Y QUE EL USUARIO SEA UN ADMIN etc.
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::middleware(['web','admin','auth'])    // el grupo de rutas web está declarado en http/Kernel.php y además indico que quiero el middleware admin y auth. De esta
            // ->namespace($this->namespace) modifico esta linea añadiendo al namespace la ruta del controlador Admin una vez lo he creado
            ->namespace($this->namespace.'\Admin')
            ->prefix('/admin')
            // ->group(base_path('routes/web/admin.php'));  //modifico la ruta si he creado la carpeta web
            ->group(base_path('routes/admin.php'));  //modifico la ruta si NO he creado la carpeta web, como duilio la quita yo la dejo pero subo admin.php tb al directorio anterior
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
