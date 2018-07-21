<?php

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
                // y tambien la ruta siguiente.
                // esta ruta nos llevara a una vista o a otra . Esto lo podemos ver
                // en el controlador HomeController que se ha creado.
                // veremos que hay el controlador usa un middleware que se llama auth
                // podemos explorar los middleware en Kernel.php

Route::get('/home', 'HomeController@index')->name('home');
