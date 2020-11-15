<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class CustomerTest
 * @package Tests\Feature
 */
class CustomerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * test que permite comproblar el funcionamiento basica de la vista de clientes
     */
    public function test_customer_page_test()
    {
        $response = $this->get('/clientes');

        $response->assertStatus(200);
    }
    /**
     * test que permite comprobar el la vista de clientes por parte del usuario
     * de un dato que se encuentra en la BD
     * @return void
     */
    public function test_customer_view_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );
        //Acceso a la funcion que permite agregar un cliente
        $this->post(route('add_customer'), $data);

        //llamado a la vista de cliente
        $response = $this->get(route('view_customer'));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de clientes','Agrega, actualiza o elimina registros de clientes',
            'ID','Cédula','Nombre','Apellidos','Teléfono','Correo','Dirección',
            '1',$data['dat']['cc'],$data['dat']['name'],$data['dat']['lastName'],$data['dat']['phone'],$data['dat']['mail'],$data['dat']['address']

        ]);

    }

    /**
     * test que permite comprobar el la vista la busqueda de un cliente
     * de un dato que se encuentra en la BD
     * @return void
     */
    public function test_customer_find_view_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );
        //Acceso a la funcion que permite agregar un cliente
        $this->post(route('add_customer'), $data);

        //datos de busqueda
        $find['dat'] = array(
            "search" => "1004"
        );
        //llamado a la vista de clientes
        $response = $this->get(route('view_customer',$find));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de clientes','Agrega, actualiza o elimina registros de clientes',
            'ID','Cédula','Nombre','Apellidos','Télefono','Correo','Dirección',
            '1',$data['dat']['cc'],$data['dat']['name'],$data['dat']['lastName'],$data['dat']['phone'],$data['dat']['mail'],$data['dat']['address']
        ]);

    }

    /**
     * test que permite agregar un cliente que no existe
     */
    public function test_customer_add_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );
        //Acceso a la funcion que permite agregar un cliente
        $response = $this->post(route('add_customer'), $data);

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseHas('customers', [
            "id" => "1",
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['lastName'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('customer_phones', [
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','El cliente se registro con éxito');
    }

    /**
     * test que permite verificar que no se agrega un cliente con cc existente
     */
    public function test_customer_add_identificationCard_repeat_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );

        //agregando el cliente
        $this->post(route('add_customer'), $data);

        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004", // repetido
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31168475",
            "address" => "calle #4",
            "mail" => "caso@mail.com"
        );
        //Acceso a la funcion que permite agregar un cliente
        $response = $this->post(route('add_customer'), $data);

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseMissing('customers', [
            "id" => "2",
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['lastName'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseMissing('customer_phones', [
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Este cliente ya se encuentra registrado');
    }

    /**
     * test que permite verificar que no se agrega un cliente con telefono existente
     */
    public function test_customer_add_phone_repeat_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );

        //agregando el cliente
        $this->post(route('add_customer'), $data);

        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "10065",
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31235", //repetido
            "address" => "calle #4",
            "mail" => "caso@mail.com"
        );
        //Acceso a la funcion que permite agregar un cliente
        $response = $this->post(route('add_customer'), $data);

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseMissing('customers', [
            "id" => "2",
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['lastName'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseMissing('customer_phones', [
            "customer_id" => "2",
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Este teléfono ya se encuentra asociado a un cliente');
    }

    /**
     * test que permite verificar que no se agrega un cliente con correo electronico existente
     */
    public function test_customer_add_email_repeat_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );

        //agregando el cliente
        $this->post(route('add_customer'), $data);

        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "10065",
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31166788",
            "address" => "calle #4",
            "mail" => "afc@mail.com" //repetido
        );
        //Acceso a la funcion que permite agregar un cliente
        $response = $this->post(route('add_customer'), $data);

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseMissing('customers', [
            "id" => "2",
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['lastName'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseMissing('customer_phones', [
            "customer_id" => "2",
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Este correo ya se encuentra asociado a un cliente');
    }

    /**
     * test que permite comprobar la la obtencion de un cliente a partir de su cc
     */
    public function test_get_employee_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );

        //agregando el cliente
        $this->post(route('add_customer'), $data);

        $response = $this->get('/clientes/1004');

        //array esperado
        $expected = array(
            'id' => '1',
            'identification_card' => $data['dat']['cc'],
            'name' => $data['dat']['name'],
            'last_name' => $data['dat']['lastName'],
            'address' => $data['dat']['address'],
            'mail' => $data['dat']['mail'],
            'number' => $data['dat']['phone'],
        );
        $response->assertSimilarJson(array($expected));
    }

    /**
     * test que permite verificar la correcta edicion de un cliente existente
     */
    public function test_customer_edit_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );
        // Datos de un cliente
        $data2['dat'] = array(
            "cc" => "10065",
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31166788",
            "address" => "calle #4",
            "mail" => "caros@mail.com"
        );
        //agregando el cliente
        $this->post(route('add_customer'), $data);
        $this->post(route('add_customer'), $data2);
        // Datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "cc" => "10065",
            "name" => "Fonso", //cambio
            "last_name" => "Gutierrez", //cambio
            "phone" => "3571656", //cambio
            "address" => "calle #64", //cambio
            "mail" => "caros@gmail.com" //cambio
        );
        //Acceso a la funcion que permite agregar un cliente
        $response = $this->post(route('edit_customer'), $dataEdit);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','Se actualizaron los datos del cliente con éxito');

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseHas('customers', [
            "id" => "2",
            "identification_card"=>  $dataEdit['dat']['cc'],
            "name"=>  $dataEdit['dat']['name'],
            "last_name"=>  $dataEdit['dat']['last_name'],
            "address"=>  $dataEdit['dat']['address'],
            "mail"=>  $dataEdit['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('customer_phones', [
            "customer_id" => "2",
            "number" => $dataEdit['dat']['phone']
        ]);

    }

    /**
     * test que permite verificar que no se editan los datos de un cliente con cc repetida
     */
    public function test_customer_edit_identificationCard_repeat_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );
        // Datos de un cliente
        $data2['dat'] = array(
            "cc" => "10065",
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31166788",
            "address" => "calle #4",
            "mail" => "caros@mail.com"
        );
        //agregando el cliente
        $this->post(route('add_customer'), $data);
        $this->post(route('add_customer'), $data2);

        // Datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "cc" => "1004", //repetido
            "name" => "Fonso", //cambio
            "last_name" => "Gutierrez", //cambio
            "phone" => "3571656", //cambio
            "address" => "calle #64", //cambio
            "mail" => "caros@gmail.com" //cambio
        );
        //Acceso a la funcion que permite agregar un cliente
        $response = $this->post(route('edit_customer'), $dataEdit);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Ya existe un cliente con esta cédula');

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseHas('customers', [
            "id" => "2",
            "identification_card"=>  $data2['dat']['cc'],
            "name"=>  $data2['dat']['name'],
            "last_name"=>  $data2['dat']['lastName'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('customer_phones', [
            "customer_id" => "2",
            "number" => $data2['dat']['phone']
        ]);

    }

    /**
     * test que permite verificar que no se editan los datos de un cliente con telefono repetido
     */
    public function test_customer_edit_phone_repeat_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );
        // Datos de un cliente
        $data2['dat'] = array(
            "cc" => "10065",
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31166788",
            "address" => "calle #4",
            "mail" => "caros@mail.com"
        );
        //agregando el cliente
        $this->post(route('add_customer'), $data);
        $this->post(route('add_customer'), $data2);

        // Datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "cc" => "10065",
            "name" => "Fonso", //cambio
            "last_name" => "Gutierrez", //cambio
            "phone" => "31235", //repetido
            "address" => "calle #64", //cambio
            "mail" => "caros@gmail.com" //cambio
        );
        //Acceso a la funcion que permite agregar un cliente
        $response = $this->post(route('edit_customer'), $dataEdit);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Un cliente ya tiene este número de teléfono');

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseHas('customers', [
            "id" => "2",
            "identification_card"=>  $data2['dat']['cc'],
            "name"=>  $data2['dat']['name'],
            "last_name"=>  $data2['dat']['lastName'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('customer_phones', [
            "customer_id" => "2",
            "number" => $data2['dat']['phone']
        ]);

    }

    /**
     * test que permite verificar que no se editan los datos de un cliente con correo repetido
     */
    public function test_customer_edit_email_repeat_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "lastName" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com"
        );
        // Datos de un cliente
        $data2['dat'] = array(
            "cc" => "10065",
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31166788",
            "address" => "calle #4",
            "mail" => "caros@mail.com"
        );
        //agregando el cliente
        $this->post(route('add_customer'), $data);
        $this->post(route('add_customer'), $data2);

        // Datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "cc" => "10065",
            "name" => "Fonso", //cambio
            "last_name" => "Gutierrez", //cambio
            "phone" => "31166788",
            "address" => "calle #64", //cambio
            "mail" => "afc@mail.com" //repetido
        );
        //Acceso a la funcion que permite agregar un cliente
        $response = $this->post(route('edit_customer'), $dataEdit);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Un cliente ya tiene este correo electrónico');

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseHas('customers', [
            "id" => "2",
            "identification_card"=>  $data2['dat']['cc'],
            "name"=>  $data2['dat']['name'],
            "last_name"=>  $data2['dat']['lastName'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('customer_phones', [
            "customer_id" => "2",
            "number" => $data2['dat']['phone']
        ]);

    }

    /**
     * test que permite comprobar el cambio del campo delete_at de la tabla de clientes y telefonos de clientes
     */
    public function test_employee_delete_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "10065",
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31166788",
            "address" => "calle #4",
            "mail" => "caros@mail.com"
        );
        //agregando el cliente
        $this->post(route('add_customer'), $data);

        //petecion de eliminacion de los cliente
        $del=[
            "_token" => csrf_token(),
            "selected"=> array("1")
        ];

        $response = $this->delete('/clientes',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(1));

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseMissing('customers', [
            "id" => "1",
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['lastName'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseMissing('customer_phones', [
            "customer_id" => "1",
            "number" => $data['dat']['phone'],
            "deleted_at"=> null
        ]);
    }

    /**
     * test que permite comprobar el cambio del campo delete_at de la tabla de clientes y telefonos de clientes
     */
    public function test_employee_delete_noData_test()
    {
        // Datos de un cliente
        $data['dat'] = array(
            "cc" => "10065",
            "name" => "Carlos",
            "lastName" => "Osorio",
            "phone" => "31166788",
            "address" => "calle #4",
            "mail" => "caros@mail.com"
        );
        //agregando el cliente
        $this->post(route('add_customer'), $data);

        //petecion de eliminacion de los cliente
        $del=[
            "_token" => csrf_token(),
            "selected"=> array()
        ];

        $response = $this->delete('/clientes',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(0));

        //comprobacion de dato agregado a tabla de clientes
        $this->assertDatabaseHas('customers', [
            "id" => "1",
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['lastName'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('customer_phones', [
            "customer_id" => "1",
            "number" => $data['dat']['phone'],
            "deleted_at"=> null
        ]);
    }
}
