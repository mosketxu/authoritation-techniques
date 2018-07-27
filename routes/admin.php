<?php

use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /* Viene a cuenta de la prueba \Tests\Feature\Admin\HideAdminRoutesTest
    Creo una ruta para capturar todas las URL´s posibles, existan o no.
    Puedo hacerlo con el metodo fallback que existe desde Laravel 5.5 pero solo funciona para GET no para POST,
    así que lo haremos con any(), aunque con fallback quedaría como sigue. No pongo nada en la funcion.
        Recordar que al estar la ruta declarada dentro de admin.php hereda los middleware['web','auth','admin'] y el prefijo admin del RouteServiceProvider
        Route::fallback(function(){
        } );
        Pruebo y funciona, pero si voy al explorador e intento entrar estando logado a una url de admin  que no existe me aparece
        una pagina en blanco y debería ser una 404 o una pagina de error
        Para ello debo añadir lógica a la funcion anonima pero lo hago preparando la prueba en \Tests\Feature\Admin\HideAdminRoutesTest  Vuelvo allí

        Una vez creada la prueba it_displays_404_when_admins_visit_invalid_urls veo que falla porque devuelve un status 200 en lugar del 404 que espero
        Así que añado lógica a la funcion para conseguirlo.
        Varias opciones:
        Opcion 1: Uso helper response con una respuesta rapida y el error esperado
            Route::fallback(function(){
                return response('Pagina no encontrada',404);
            } );
            Pruebas y explorador pasan
        Opcion 2: Uso helper response con una vista. argumentos aunque sean vacios  y el error esperado.
                Debo crear la vista y la carpeta errors si no existiera
            Route::fallback(function(){
                return response()->view('errors/404',[],404);
            } );
            Pruebas y explorador pasan
        Opcion 3: arrojar un error 404 arrojando una excepcion \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
            Importo la clase para evitar que sea tan largo use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
                Route::fallback(function(){
                    throw new NotFoundHttpException('Página no encontrada');
                } );
            Pruebo y pasan
            Veo que como estoy personalizando la página de error 404.blade.php laravel la carga por mi.

        Opcion 4: El problema de fallback es que solo funciona para get no para post. Para arreglar uso any
            Hago una prueba con POST en \Tests\Feature\Admin\HideAdminRoutesTest
            La prueba es it_does_not_allow_guests_to_discover_admin_urls_using_post
            ERROR: no las pasa porque recibe el error 405 en lugar del 302
            SOLUCION: Creo una ruta con any que capture todas las peticiones sin importar del tipo que sea: get, post, delete etc
                     La url podra contener cualquier combinacion de caracteres incluyendo carteres especiales.
                     Lo hago con en el where indicando que any puede contener cualquier combinacion de caracteres incluyendo barras diagonales etc
                     y dentro de la ruta arrojo el error 404
                    Route::any('{any}',function(){
                        throw new NotFoundHttpException('Página no encontrada');
                    })->where('any','.*');
                La prueba pasa y si envio la peticion en el explorador recibo el mismo error con lo cual ya no se descubrir el motivo del error que es el objetivo
                y si intento enviar una peticion post de una url que no existe, entonces recibo el mismo error

                Luego DUilio se enrolla con un tema de macros del que paso. Es por si tengo muchas rutas de este tipo. Minuto 13.13
        */

        Route::any('{any}',function(){
            // throw new NotFoundHttpException('Página no encontrada'); // no sería necesario pasar el mensaje porque estoy pasando una vista personalizada
            throw new NotFoundHttpException;
        })->where('any','.*');
