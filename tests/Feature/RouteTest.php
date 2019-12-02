<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     */
    public function testHomePage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }


    /**
     *
     */
    public function testLoginPage()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }


    /**
     *
     */
    public function testMenuPage()
    {
        $response = $this->get('/menu');
        $response->assertStatus(200);
    }


    /**
     *
     */
    public function testOrderOnlinePage()
    {
        $response = $this->get('/order/online');
        $response->assertStatus(200);
    }

    /**
     *
     */
    public function testContactPage()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }


    /**
     *
     */
    public function testRegisterPage()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }


    /**
     *
     */
    public function testMenuAdminPage()
    {
        $response = $this->get('/menu-admin');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testDishCreatePage()
    {
        $response = $this->get('/dish/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testDishCategoryPage()
    {
        $response = $this->get('/dishCategory');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testWorkerIndexPage()
    {
        $response = $this->get('/worker/index');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testWorkerCreatePage()
    {
        $response = $this->get('/worker/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testTablePage()
    {
        $response = $this->get('/table-admin');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testTableWaiterPage()
    {
        $response = $this->get('/tables/waiter-index');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testWaiterCreateOrderPage()
    {
        $response = $this->get('/orders/waiter-create/1');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testWaiterReservationCreatePage()
    {
        $response = $this->get('/reservation/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testWaiterReservationPage()
    {
        $response = $this->get('/reservation/index');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     *
     */
    public function testLogin()
    {
        $this->assertTrue(true);
        $user = factory(User::class)->make();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/home');
    }
}
