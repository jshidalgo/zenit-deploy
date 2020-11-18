<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RecordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test que permite comproblar el funcionamiento basico de la vista de servicios
     * @test
     * @return void
     */
    public function test_record_page_test()
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
     * Test que permite agregar un servicio
     */
    public function test_record_add_test(){
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

        //agregando datos de proveedor
        $dataProvider['dat'] = array(
            "nit" => "00-7371413",
            "name" => "Carlos Nissan",
            "mail" => "carlos@nissan.com",
            "phone" => "753817",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #3",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $dataProvider);

        //datos a agregar de la compra
        $dataPurchase['dat'] = array(
            'cod' => '1',
            'date' => '2020-11-17',
            'cost' => '120000',
            'concept' => 'Compra de repuestos para nissan 2010',
            'status' => 'Pago',
            'provider' => '1'
        );

        //datos a agregar de los productos
        $dataPurchase['product'] = array(
            'nameProduct1' => 'Bujias',
            'costProduct1' => '20000',
            'amountProduct1' => '14',
            'nameProduct2' => 'Aceite',
            'costProduct2' => '85300',
            'amountProduct2' => '30',
            'nameProduct3' => 'Puntura roja',
            'costProduct3' => '50000',
            'amountProduct3' => '10',
        );

        //agregando la compra
        $this->post(route('add_purchase', $dataPurchase));

        // Datos de un cliente
        $dataCustomer['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );
        //Acceso a la funcion que permite agregar un cliente
        $this->post(route('add_customer'), $dataCustomer);

        // Datos de un empleado
        $dataEmployee['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $dataEmployee);

        // Datos de un vehiculo
        $dataVehicle['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );
        //Acceso a la funcion que permite agregar un vehiculo
        $this->post(route('add_vehicle'), $dataVehicle);

        //datos del servicio
        $dataRecord['dat'] = array(
            'id_customer' => '1',
            'id_vehicle' => '1',
            'id_employee' => '1',
            'mileage' => '1000',
            'entry_date' => '2020-11-17',
            'out_date' => '2020-11-22',
            'name-service-add' => 'Cambio llantas',
            'description-service-add' => 'combio de llantas delanteras',
            'cost-service' => '500000',
        );

        $response = $this->get(route('add_record'),$dataRecord);



        //mensaje de respuesta
        $response->isRedirect('view_record');
    }

}
