<?php
Route::get('/', function(){
            return view('/admin/dashboard');
        })->name('admin_dashboard');

Route::get('/events', function(){
    return 'Admin Events';
})->name('admin_events');
