<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AdminEventsTest extends TestCase
{

    // En un inicio es una copia de la prueba AdminDashboardTest cambiando los nombres

    use RefreshDatabase;


    public function admins_can_visit_the_admin_events_page()
    {
        $admin =factory(User::class)->create([
            'admin'=>true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin_events'))
            ->assertStatus(200)
            ->assertSee('Admin Events');
    }


    /** @test **/
    public function non_admins_users_cannot_visit_the_admin_events_page()
    {
        $user =factory(User::class)->create([
            'admin'=>false,
        ]);

        $this->actingAs($user)
            ->get(route('admin_events'))
            ->assertStatus(403);
    }

    /** @test **/
    public function  guests_cannot_visit_the_admin_events_page()
    {
        $this->get(route('admin_events'))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

}
