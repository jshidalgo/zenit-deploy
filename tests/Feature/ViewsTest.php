<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ViewsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test que permite comproblar el funcionamiento basico de la vista de proveedores
     *
     * @return void
     */
    public function test_provider_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->get('/proveedores');

        $response->assertStatus(200);

    }

    /**
     * test que permite comproblar el funcionamiento basico de la vista de compras
     *
     * @return void
     */
    public function test_purchase_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->get('/compras');

        $response->assertStatus(200);

    }
    /**
     * test que permite comproblar el funcionamiento basico de la vista de productos
     *
     * @return void
     */
    public function test_product_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->get('/productos');

        $response->assertStatus(200);

    }
    /**
     * test que permite comproblar el funcionamiento basico de la vista de vehiculos
     *
     * @return void
     */
    public function test_vehicle_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->get('/vehiculos');

        $response->assertStatus(200);

    }
    /**
     * test que permite comproblar el funcionamiento basico de la vista de clientes
     *
     * @return void
     */
    public function test_customer_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->get('/clientes');

        $response->assertStatus(200);

    }

    /**
     * test que permite comproblar el funcionamiento basico de la vista de servicios
     *
     * @return void
     */
    public function test_record_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->get('/servicios');

        $response->assertStatus(200);

    }
    /**
     * test que permite comproblar el funcionamiento basico de la vista de calendario
     *
     * @return void
     */
    public function test_appointment_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->get('/calendario');

        $response->assertStatus(200);

    }
    /**
     * test que permite comproblar el funcionamiento basico de la vista de home
     * @covers \App\Http\Controllers\Auth\ConfirmPasswordController
     * @covers \App\Http\Controllers\Auth\ForgotPasswordController
     * @covers \App\Http\Controllers\Auth\LoginController
     * @covers \App\Http\Controllers\Auth\RegisterController
     * @covers \App\Http\Controllers\Auth\ResetPasswordController
     * @covers \App\Http\Controllers\Auth\VerificationController
     * @return void
     */
    public function test_home_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->get('/home');

        $response->assertStatus(200);

    }
    /**
     * test que permite comproblar el funcionamiento basico de la manu lateral de selección
     *
     * @return void
     */
    public function test_lateral_view_test()
    {
        //crecion de usuario
        $user = new User();
        $user->name = 'amdin';
        $user->email = 'admin@mail.com';
        $user->password = 'admin312';
        $user->save();

        //autenticacion de usuario
        Auth::loginUsingId(1);

        //comprobacion de autenticacion
        $this->assertAuthenticated();

        $response = $this->view('home');

        $response->assertSeeInOrder(['Calendario','Servicios','Clientes','Vehículos','Productos','Compras','Proveedores','Empleados']);

    }
}
