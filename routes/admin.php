<?php

/* Route::get('/', function(){
            return view('/admin/dashboard');
        })->name('admin_dashboard');
 */

 /* una vez hemos creado el controlador Dashboard.php en /Admin puedo sustituir lo de arriba por
        Route::get('/','Admin\Dashboard@index')->name('admin_dashboard');
     y las pruebas pasan

     Pero si voy a estar asignando el nombre de espacio 'Admin\...' a todos los controladores
     casi merece la pena modificar el fichero RouteServiceProvider y añadir al namespace '\Admin' al metodo mapAdminRoutes
     y así lo quito de la linea de la ruta
        Route::get('/','\Dashboard@index')->name('admin_dashboard');
    */

    Route::get('/','Dashboard@index')->name('admin_dashboard');

    Route::get('/events', function(){
        return 'Admin Events';
    })->name('admin_events');
