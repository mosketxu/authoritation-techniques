<?php

use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(); //Al jecutar php artisan make auth se crean nuevas rutas como esta, que incluye
                // las rutas de inicio de sesion
                // de registro
                // de recuperacion de password
                // y tambien la ruta siguiente. La de /home
                // esta ruta nos llevara a una vista o a otra . Esto lo podemos ver
                // en el controlador HomeController que se ha creado.
                // veremos que hay el controlador usa un middleware que se llama auth
                // podemos explorar los middleware en Kernel.php

// Esta linea es así si uso el constructor en HomeController, pero si lo elimino de allí puedo usar aqui el middleware auth
// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

// comienzo a crear un panel de administracion
//Route::view('admin','/admin/dashboard');  /* Haciendo esto sin mas puedo acceder en el explorador a http://authoritation-techniques.test/admin aunque no esté logado
                                            // En un panel de administracion solo queremos dar acceso a los usuarios que tengan el role de admin
                                            // Asi que creo una nueva prueba php artisan make:test Admin/AdminDashboardTest   o     php artisan m:t Admin/AdminDashboardTest
                                            // Laravel creara la carpeta Admin por mi. Y me voy a la prueba.*/
// Route::view('admin','/admin/dashboard')->name('admin_dashboard'); //Es como me refiero a ella en por ejemplo las pruebas. He puesto un nombre.

// Aplico el middleware de autorizació<n:   ->middleware('auth');
// Route::view('admin','/admin/dashboard')->name('admin_dashboard')->middleware('auth'); //de manera que solo puedo acceder si soy admin.
                                        // pero aunque sirve para los usuarios guest no es suficiente para los usuarios que son user y no admin
                                        // asi que cambio el helper view por el get y una funcion anonima donde pongo logica

//================================
// Comento y repito esta ruta con el condicional mas abajo porque uso una Guard Clause,
// es decir en lugar de hacer el condicional con un else, hago un condicional que se si cumple ya salgo de la funcion
// y despues pongo el resto de las cosas que quiero
// Route::get('admin', function(){
    //return view('/admin/dashboard');  // aqui ejecuto las pruebas y veo que todo va igual, lo bueno y lo malo
                                        // como es así pongo logica y luego uso esa linea

    //la logica consiste en preguntar si el user es admin y para saberlo uso el helper auth()->user
//    if(auth()->user()->admin){
//        return view('/admin/dashboard');
//    } else {    //y si no lo es le mando un mensaje y devuelvo el codigo http que espera la prueba, es decir el 403

/*         return response('You shall NOT pass!', 403); //403: forbidden

        para el response puedo importarlo ponienso arriba: use Symfony\Component\HttpFpundation\Response
        y en lugar del codigo 403 poner su alias
        return response('You shall NOT pass!', Response::HTTP_FORBIDDEN);
            el tema de los codigos tiene telita. El usa otra instruccion y recomienda la pagina https://httpstatuses.com/

        Una opcion mas interesante que poner el texto es poner una imagen usando el helper response (hay lecciones en styde que hablan sobre esto)
        como obtengo el objeto response llamo al metodo view y paso como primer argumento la vista, luego los datos que quiero pasar a la vista
        en este caso ninguno, y como tercer argumento el estado de la peticion http, porque si no la pongo funcionaria, pero me devolveria el status 200 y entonces la prueba no pasaria

        creo la vista
        */

 //       return response()->view('forbidden',[],403);
 //   }

//})->name('admin_dashboard')->middleware('auth');

//=========================
//  lo pongo con la guard clause.
//  y lo vuelvo a comentar porque hay otra manera mejor aun que es usar un middleware. Sigo usando la guard clause pero mando lo demas de otra manera

// Route::get('/admin', function(){
//     if(! auth()->user()->admin){
//         return response()->view('forbidden',[],403);
//     }

//     //'events','news','recentUsers' no hacen nada aqui ahora. Es para entender el ejemplo del guard clause
//     $events=[];

//     $news=[];

//     $recentUsers= [];

//     return view('/admin/dashboard',compact('events','news','recentUsers'));


// })->name('admin_dashboard')->middleware('auth');

/* ======================
    Uso la guard clause y un middleware.
    Ejecuto php artisan make:middleware Admin        Se guarda en http/middleware
    llevo la guard clause al middleware en el Handle
    Ejecuto las pruebas y
    ERROR:   non_admins_users_cannot_visit_the_admin_dashboard
            Expected status code 403 but received 200.
            Failed asserting that false is true.
    SOLUCION: Esto es porque he creado un middleware pero no se lo he asignado a la ruta.
              Debo hacer esto manualmente. Para esto hay dos opciones.
              OPCION 1: En lugar de pasar solo el middlewre 'auth' paso un array de middleware: 'auth' y 'Admin' ->middleware(['auth',\App\Http\Middleware\Admin::class]);
                        La prueba pasa
                        Pero para no poner todo el churro creamos un alias. A ese alias le llamamos admin
                        y debo asignar ese alias a una clase. Lo hago en Kernel.php que esta en Http
                        Lo pongo al final en $routeMiddleware               'admin'=> \App\Http\Middleware\Admin::class,
                        Las pruebas pasan
              OPCION 2: En el middleware en lugar de mandar una vista mando una excepcion de autentificacion
                        Es el framework el que maneja esta excepcion
                        Las pruebas pasan, pero en el explorador da un error: AcceddDeniedHttpException  ya no sale el gif
                        y da informacion peligrosa, pero es porque tengo el modo depuracion activado.
                        Si voy a .env y pongo APP_DEBUG=false sale una pantalla mas chula. Lo vuelvo a poner a true
                        para corregir esto creo una carpeta errors en views y muevo el fichero forbidden.blade.php dentro y le cambio el nombre al numero del error,
                        es decir 403.blade.php
                        Las pruebas pasan y en el explorador tambien

*/
// sin alias
/*Route::get('/admin', function(){
    return view('/admin/dashboard');
})->name('admin_dashboard')->middleware(['auth',\App\Http\Middleware\Admin::class]); */

//OPCION 1 con alias
/*Route::get('/admin', function(){
    return view('/admin/dashboard');
})->name('admin_dashboard')->middleware(['auth','admin']);*/

// ====================
// Comento porque vamos a usar rutas agrupadas para no repetir codigo
//OPCION 2: con excepcion de autentificacion en el middleware
// Route::get('/admin', function(){
//     return view('/admin/dashboard');
// })->name('admin_dashboard')->middleware(['auth','admin']);


// añado otra ruta. Comienzo de la segunda leccion anidada
// Route::get('/admin/events', function(){
//     return 'Admin Events';
// })->name('admin_events')->middleware(['auth','admin']);
// ====================

/* hago las rutas agrupadas con el metodo Route::group()
    Este metodo recibe dos argumentos. El primero lo comun de las rutas y despues una funcion anonima donde pongo todas las rutas que quiero que formen parte de este grupo
    Muestro la estructura solo con el group y poniendo lo vble dentro de la funcion y pruebo. Veré que funciona
            Route::group([],function(){
                Route::get('/admin', function(){
                    return view('/admin/dashboard');
                })->name('admin_dashboard')->middleware(['auth','admin']);

                Route::get('/admin/events', function(){
                    return 'Admin Events';
                })->name('admin_events')->middleware(['auth','admin']);
            });
    Incluyo ahora lo comun. En primera instancia la caracteristicas del middleware. De los dos. Lo añado al array de group y lo elimino del final de cada ruta
            Route::group(['middleware'=>['auth','admin']],function(){
                Route::get('/admin', function(){
                    return view('/admin/dashboard');
                })->name('admin_dashboard');

                Route::get('/admin/events', function(){
                    return 'Admin Events';
                })->name('admin_events');
            });
            Pruebo y funciona
    Pongo tb el prefijo admin como parte del grupo de rutas y lo quito de cada route
        Route::group(['prefix'=>'admin','middleware'=>['auth','admin']], function(){
            Route::get('/', function(){
                return view('/admin/dashboard');
            })->name('admin_dashboard');

            Route::get('/events', function(){
                return 'Admin Events';
            })->name('admin_events');
        } );

    Qué pasaría si tengo un admin con muchas rutas. Lo mejor que puedo hacer es sacar todas esas rutas a un archivo externo.
    Crearé un archivo en el mismo directorio que se llame admin.php. Antes de probar debo crear el archivo. Lo creo, copio lo que había en la función y pruebo. Y pasas
        Route::group(['prefix'=>'admin','middleware'=>['auth','admin']], function(){
            require __DIR__ . '/admin.php';
        } );

    También podría crear una carpeta de rutas y ponerlo ahí.
        Creo la carpeta web y muebo ahí admin.php y redirijo a esa carpeta en el require
        Pruebo y funciona
        Route::group(['prefix'=>'admin','middleware'=>['auth','admin']], function(){
            require __DIR__ . '/web/admin.php';
        } );

    una forma más limpia puede ser pasarlo como métodos encadenados en lugar de como un array que mola mas
        Route::middleware(['admin','auth'])
                ->prefix('/admin')
                ->group(function(){
                    require __DIR__ . '/web/admin.php';
                });

    Otra opcion es en lugar de hacer esto aquí es cambiar la configuracion del archivo RouteServiceProvider.php que está en App/providers el provider
        En este archivo vemos que hay ciertos tipos de rutas en la function map. Hay alguna que sobra y Duilio la elimina, pero yo la dejo.
        Modifico el RouteServiceProvider.php (ver allí. Duilio deja de usar la subcarpeta web. Yo la dejo de ejemplo, pero ya no busco admin.php ahí) y

        Es decir he puesto las referencias a las rutas de Admin completamente en RouteServiceProvider

    */
//    Route::middleware(['admin','auth'])->prefix('/admin')->group(function(){require __DIR__ . '/web/admin.php';});
    // Route::group(['prefix'=>'admin','middleware'=>['auth','admin']], function(){
    //     require __DIR__ . '/web/admin.php';
    // } );
