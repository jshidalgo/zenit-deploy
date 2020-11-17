<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Class VehicleTest
 * @package Tests\Feature
 */
class VehicleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test que permite comproblar el funcionamiento basico de la vista de vehiculos
     *
     * @return void
     */
    public function test_vehicle_page_test()
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
     * test que permite comprobar el la vista de vehiculos por parte del usuario
     * de un dato que se encuentra en la BD
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

        // Datos de un vehiculo
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );
        //Acceso a la funcion que permite agregar un vehiculo
        $this->post(route('add_vehicle'), $data);

        //llamado a la vista de vehiculo
        $response = $this->get(route('view_vehicle'));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de vehículos','Agrega, actualiza o elimina registros de vehículos',
            'ID','Matricula','Color','Cilindraje','Nombre','Modelo','Marca',
            "1",$data['dat']['plate'],$data['dat']['color'],$data['dat']['cylinder'],$data['dat']['name'],$data['dat']['model'],$data['dat']['brand']
        ]);

    }

    /**
     * test que permite comprobar el la vista por parte del usuario
     * de un dato que se encuentra en la BD
     * @return void
     */
    public function test_vehicle_find_view_test()
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

        // Datos de un vehiculo
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );
        //Acceso a la funcion que permite agregar un vehiculo
        $this->post(route('add_vehicle'), $data);

        $find['dat'] = array(
            'search' => 'CWU256'
        );

        //llamado a la vista de vehiculo
        $response = $this->get(route('view_vehicle', $find));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de vehículos','Agrega, actualiza o elimina registros de vehículos',
            'ID','Matricula','Color','Cilindraje','Nombre','Modelo','Marca',
            "1",$data['dat']['plate'],$data['dat']['color'],$data['dat']['cylinder'],$data['dat']['name'],$data['dat']['model'],$data['dat']['brand']
        ]);

    }

    /**
     * test que permite agregar un vehiculo que no existe
     *
     * @return void
     */
    public function test_vehicle_add_test()
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

        // Datos de un vehiculo
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );

        //Acceso a la funcion que permite agregar un vehiculo
        $response = $this->post(route('add_vehicle'), $data);

        //comprobacion de dato agregado a tabla de vehiculos
        $this->assertDatabaseHas('vehicles', [
            "license_plate"=>  $data['dat']['plate'],
            "color"=>  $data['dat']['color'],
            "cylinder_capacity"=>  $data['dat']['cylinder'],
            "name"=>  $data['dat']['name'],
            "model"=> $data['dat']['model'],
            "brand_id"=>  "1"
        ]);
        //comprobacion de dato agregado a la tabla marcas
        $this->assertDatabaseHas('brands', [
            "name" => $data['dat']['brand']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg', 'El vehículo se registro con éxito');
    }

    /**
     * test que permite verifiar que no se agrega un vehiculo con la placa repetida
     *
     * @return void
     */
    public function test_vehicle_add_licensePlate_repeat_test()
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

        // Datos de un vehiculo
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );
        //agregar vehiculo
        $this->post(route('add_vehicle'), $data);

        // Datos de un vehiculo
        $data['dat'] = array(
            "plate" => "CWU256", //repetido
            "color" => "Negro",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2020",
            "brand" => "NISSAN",
        );

        //Acceso a la funcion que permite agregar un vehiculo
        $response = $this->post(route('add_vehicle'), $data);

        //comprobacion de dato agregado a tabla de vehiculos
        $this->assertDatabaseMissing('vehicles', [
            "license_plate"=>  $data['dat']['plate'],
            "color"=>  $data['dat']['color'],
            "cylinder_capacity"=>  $data['dat']['cylinder'],
            "name"=>  $data['dat']['name'],
            "model"=> $data['dat']['model'],
            "brand_id"=>  "2"
        ]);
        //comprobacion de dato agregado a la tabla marcas
        $this->assertDatabaseMissing('brands', [
            "name" => $data['dat']['brand']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg', 'Este vehículo ya se encuentra registrado');
    }

    /**
     * test que permite editar los datos de un vehiculo
     *
     * @return void
     */
    public function test_vehicle_edit_test()
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

        // Datos de un vehiculo
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );
        //agregar vehiculo
        $this->post(route('add_vehicle'), $data);

        // Datos de un vehiculo
        $dataEdit['dat'] = array(
            "id" => "1",
            "plate" => "CWU256",
            "color" => "Negro",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2020",
            "brand" => "NISSAN",
        );

        //Acceso a la funcion que permite editar un vehiculo
        $response = $this->post(route('edit_vehicle'), $dataEdit);

        //comprobacion de dato agregado a tabla de vehiculos
        $this->assertDatabaseHas('vehicles', [
            "license_plate"=>  $dataEdit['dat']['plate'],
            "color"=>  $dataEdit['dat']['color'],
            "cylinder_capacity"=>  $dataEdit['dat']['cylinder'],
            "name"=>  $dataEdit['dat']['name'],
            "model"=> $dataEdit['dat']['model'],
            "brand_id"=>  "2"
        ]);
        //comprobacion de dato agregado a la tabla marcas
        $this->assertDatabaseHas('brands', [
            "name" => $data['dat']['brand']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','Se ha modificado  los datos del vehículo');
    }
    /**
     * test que permite verificar la no edicion de un vehiculo con placas repetidas
     *
     * @return void
     */
    public function test_vehicle_edit_licensePlate_repeat_test()
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

        // Datos de un vehiculo
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );
        // Datos de un vehiculo
        $data2['dat'] = array(
            "plate" => "HGI957",
            "color" => "Negro",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2020",
            "brand" => "NISSAN",
        );
        //agregar vehiculo
        $this->post(route('add_vehicle'), $data);
        //agregar vehiculo
        $this->post(route('add_vehicle'), $data2);

        // Datos de un vehiculo
        $dataEdit['dat'] = array(
            "id" => "2",
            "plate" => "CWU256", //repetido
            "color" => "Azul", //cambio
            "cylinder" => "1500", //cambio
            "name" => "GT",
            "model" => "2020",
            "brand" => "KIA",
        );

        //Acceso a la funcion que permite editar un vehiculo
        $response = $this->post(route('edit_vehicle'), $dataEdit);

        //comprobacion de dato agregado a tabla de vehiculos
        $this->assertDatabaseMissing('vehicles', [
            "license_plate"=>  $dataEdit['dat']['plate'],
            "color"=>  $dataEdit['dat']['color'],
            "cylinder_capacity"=>  $dataEdit['dat']['cylinder'],
            "name"=>  $dataEdit['dat']['name'],
            "model"=> $dataEdit['dat']['model'],
            "brand_id"=>  "3"
        ]);
        //comprobacion de dato agregado a la tabla marcas
        $this->assertDatabaseMissing('brands', [
            "name" => $dataEdit['dat']['brand']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Ya existe un vehiculo con esta placa');
    }

    /**
     * test que permite comprobar la la obtencion de un vehiculo a partir de su id
     */
    public function test_get_vehicle_test()
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

        // Datos de un proveedor
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );

        //agregar vehiculo
        $this->post(route('add_vehicle'), $data);

        //obtener un vehiculo con el id 1
        $response = $this->get('/vehiculos/1');

        //array esperado
        $expected = array(
            "brand" => $data['dat']['brand'],
            "brand_id" => "1",
            "color" =>  $data['dat']['color'],
            "cylinder_capacity" =>  $data['dat']['cylinder'],
            "id" => "1",
            "license_plate" =>  $data['dat']['plate'],
            "model" =>  $data['dat']['model'],
            "name" =>  $data['dat']['name'],
        );
        $response->assertSimilarJson(array($expected));
    }

    /**
     * test que permite eliminar un vehiculo
     *
     * @return void
     */
    public function test_vehicle_delete_test()
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

        // Datos de un proveedor
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );

        //agregar vehiculo
        $this->post(route('add_vehicle'), $data);

        //petecion de eliminacion de vehiculo
        $del=[
            "_token" => csrf_token(),
            "selected"=> array('1') //id del vehiculo
        ];

        //peticion de eliminacion
        $response = $this->delete('/vehiculos',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(1));

        //comprobacion de dato agregado a tabla de vehiculos
        $this->assertDatabaseMissing('vehicles', [
            "id" => "1",
            "license_plate"=>  $data['dat']['plate'],
            "color"=>  $data['dat']['color'],
            "cylinder_capacity"=>  $data['dat']['cylinder'],
            "name"=>  $data['dat']['name'],
            "model"=> $data['dat']['model'],
            "brand_id"=>  "1",
            "deleted_at" => null,
        ]);

    }
    /**
     * test que permite eliminar un vehiculo
     *
     * @return void
     */
    public function test_vehicle_delete_noData_test()
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

        // Datos de un proveedor
        $data['dat'] = array(
            "plate" => "CWU256",
            "color" => "Blanco",
            "cylinder" => "1000",
            "name" => "GT",
            "model" => "2015",
            "brand" => "BMW",
        );

        //agregar vehiculo
        $this->post(route('add_vehicle'), $data);

        //petecion de eliminacion de vehiculo
        $del=[
            "_token" => csrf_token(),
            "selected"=> array() //id del vehiculo
        ];

        //peticion de eliminacion
        $response = $this->delete('/vehiculos',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(0));

        //comprobacion de dato agregado a tabla de vehiculos
        $this->assertDatabaseHas('vehicles', [
            "id" => "1",
            "license_plate"=>  $data['dat']['plate'],
            "color"=>  $data['dat']['color'],
            "cylinder_capacity"=>  $data['dat']['cylinder'],
            "name"=>  $data['dat']['name'],
            "model"=> $data['dat']['model'],
            "brand_id"=>  "1",
            "deleted_at" => null,
        ]);

    }
}
