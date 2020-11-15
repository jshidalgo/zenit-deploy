<?php

namespace Tests\Feature;

use App\Http\Controllers\ProviderController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ProviderTest
 * @package Tests\Feature
 */
class ProviderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test que permite comproblar el funcionamiento basico de la vista de proveedores
     *
     * @return void
     */
    public function test_provider_page_test()
    {
        $response = $this->get('/proveedores');

        $response->assertStatus(200);
    }
    /**
     * test que permite comprobar el la vista de proveedores por parte del usuario
     * de un dato que se encuentra en la BD
     * @return void
     */
    public function test_provider_view_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "07371413",
            "name" => "Carlos Nissan",
            "mail" => "carlos@nissan.com",
            "phone" => "753817",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #3",
        );
        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data);

        //llamado a la vista de proveedores
        $response = $this->get(route('view_provider'));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de proveedores','Agrega, actualiza o elimina registros de proveedores',
            'ID','Nit','Nombre','Correo','Télefono','País','Departamento','Ciudad','Dirección',
            "1",$data['dat']['nit'],$data['dat']['name'],$data['dat']['mail'],$data['dat']['phone'],$data['dat']['country'],$data['dat']['departament'],$data['dat']['city'],$data['dat']['address']
        ]);

    }

    /**
     * test que permite comprobar el la vista por parte del usuario
     * de un dato que se encuentra en la BD
     * @return void
     */
    public function test_provider_find_view_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "07371413",
            "name" => "Carlos Nissan",
            "mail" => "carlos@nissan.com",
            "phone" => "753817",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #3",
        );
        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data);

        $find['dat'] = array(
            'search' => '07371413'
        );
        //llamado a la vista de proveedores
        $response = $this->get(route('view_provider', $find));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de proveedores','Agrega, actualiza o elimina registros de proveedores',
            'ID','Nit','Nombre','Correo','Télefono','País','Departamento','Ciudad','Dirección',
            "1",$data['dat']['nit'],$data['dat']['name'],$data['dat']['mail'],$data['dat']['phone'],$data['dat']['country'],$data['dat']['departament'],$data['dat']['city'],$data['dat']['address']
        ]);

    }
    /**
     * test que permite agregar o verificar la existencia de una ubicacion
     *
     * @return void
     */
    public function test_location_find_test()
    {
        // Datos de un ubicacion
        $pais = "Colombia";
        $departamento = "Quindío";
        $ciudad = "Armenia";

        //instancia del controlador del proveedor
        $controladorProveedor = new ProviderController();
        //peticion
        $response = $controladorProveedor->buscarUbicacion($ciudad,$departamento,$pais);
        //comprobar que creeo la ubicacion
        $this->assertEquals("1",$response);

        //comprobacion a la base de datos
        $this->assertDatabaseHas('countries',[
            "name" => $pais
        ]);
        $this->assertDatabaseHas('departments',[
            "name" => $departamento
        ]);
        $this->assertDatabaseHas('cities',[
            "name" => $ciudad
        ]);
    }

    /**
     * test que permite agregar un proveedor que no existe
     *
     * @return void
     */
    public function test_provider_add_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
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
        $response = $this->post(route('add_provider'), $data);

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseHas('providers', [
            "nit"=>  $data['dat']['nit'],
            "name"=>  $data['dat']['name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseHas('provider_phones', [
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','El proveedor se registro con éxito');
    }

    /**
     * test que permite verificar que no se agrega un proveedor con nit repetido
     *
     * @return void
     */
    public function test_provider_add_nit_repeat_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
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
        $this->post(route('add_provider'), $data);

        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "00-7371413",
            "name" => "Cenda",
            "mail" => "cenda@mail.com",
            "phone" => "511561",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #4",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $response = $this->post(route('add_provider'), $data);

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseMissing('providers', [
            "nit"=>  $data['dat']['nit'],
            "name"=>  $data['dat']['name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseMissing('provider_phones', [
            "number" => $data['dat']['phone']
        ]);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg', 'Este proveedor ya se encuentra registrado');
    }

    /**
     * test que permite verificar que no se agrega un proveedor con telefono repetido
     *
     * @return void
     */
    public function test_provider_add_phone_repeat_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "00-7371413",
            "name" => "Carlos Nissan",
            "mail" => "carlos@nissan.com",
            "phone" => "753817", //repetido
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #3",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data);

        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "857175",
            "name" => "Cenda",
            "mail" => "cenda@mail.com",
            "phone" => "753817",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #4",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $response = $this->post(route('add_provider'), $data);

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseMissing('providers', [
            "nit"=>  $data['dat']['nit'],
            "name"=>  $data['dat']['name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseMissing('provider_phones', [
            "provider_id" => "2",
            "number" => $data['dat']['phone']
        ]);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg', 'Este número de télefono ya se encuentra registrado');
    }

    /**
     * test que permite verificar que no se agrega un proveedor con correo repetido
     *
     * @return void
     */
    public function test_provider_add_email_repeat_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
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
        $this->post(route('add_provider'), $data);

        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "857175",
            "name" => "Cenda",
            "mail" => "carlos@nissan.com", //repetido
            "phone" => "715973",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #4",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $response = $this->post(route('add_provider'), $data);

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseMissing('providers', [
            "nit"=>  $data['dat']['nit'],
            "name"=>  $data['dat']['name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseMissing('provider_phones', [
            "provider_id" => "2",
            "number" => $data['dat']['phone']
        ]);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg', 'Este correo ya se encuentra asociado a un proveedor');
    }

    /**
     * test que permite comprobar la la obtencion de un proveedor a partir de su id
     */
    public function test_get_provider_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "00-7371413",
            "name" => "Carlos Nissan",
            "mail" => "carlos@nissan.com",
            "phone" => "753817",
            "country" => "Colombia",
            "departament" => "Quindio",
            "city" => "Armenia",
            "address" => "calle #3",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data);

        //obtener un proveedor con el id 1
        $response = $this->get('/proveedores/1');

        //array esperado
        $expected = array(
            "address" => $data['dat']['address'],
            "city" => $data['dat']['city'],
            "city_id" => "1",
            "country" => $data['dat']['country'],
            "departament" => $data['dat']['departament'],
            "id" => "1",
            "mail" => $data['dat']['mail'],
            "name" => $data['dat']['name'],
            "nit" => $data['dat']['nit'],
            "number" => $data['dat']['phone'],
        );
        $response->assertSimilarJson(array($expected));
    }

    /**
     * test que permite verificar la edicion de un proveedor
     *
     * @return void
     */
    public function test_provider_edit_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
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
        $this->post(route('add_provider'), $data);

        // Datos a editar
        $dataEdit['dat'] = array(
            "id" => "1",
            "nit" => $data['dat']['nit'],
            "name" => "Antonio cc",
            "mail" => $data['dat']['mail'],
            "phone" => $data['dat']['phone'],
            "country" => $data['dat']['country'],
            "departament" => $data['dat']['departament'],
            "city" => "Calarca",
            "address" => "plaza cc",
        );

        //Acceso a la funcion que permite editar un proveedor
        $response = $this->post(route('edit_provider'), $dataEdit);

        //comprobacion de dato editado a tabla de proveedores
        $this->assertDatabaseHas('providers', [
            "nit"=>  $dataEdit['dat']['nit'],
            "name"=>  $dataEdit['dat']['name'],
            "address"=>  $dataEdit['dat']['address'],
            "mail"=>  $dataEdit['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseHas('provider_phones', [
            "provider_id" => "1",
            "number" => $dataEdit['dat']['phone']
        ]);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','Se actualizaron los datos del proveedor con éxito');
    }

    /**
     * test que permite verificar la edicion de un proveedor no se raliza al intentar editar su nit por uno existente
     *
     * @return void
     */
    public function test_provider_edit_nit_repeat_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "00-7371413",
            "name" => "Carlos Nissan",
            "mail" => "carlos@nissan.com",
            "phone" => "753817",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #3",
        );
        // Datos de un proveedor
        $data2['dat'] = array(
            "nit" => "84671",
            "name" => "Folson",
            "mail" => "folson@mail.com",
            "phone" => "672962",
            "country" => "Colombia",
            "departament" => "Cundinamarca",
            "city" => "Bogota",
            "address" => "Av1",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data);
        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data2);

        // Datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "nit" => $data['dat']['nit'], //repetido
            "name" => $data2['dat']['name'],
            "mail" => $data2['dat']['mail'],
            "phone" => $data2['dat']['phone'],
            "country" => $data2['dat']['country'],
            "departament" => $data2['dat']['departament'],
            "city" => $data2['dat']['city'],
            "address" => $data2['dat']['address'],
        );

        //Acceso a la funcion que permite editar un proveedor
        $response = $this->post(route('edit_provider'), $dataEdit);

        //comprobacion de dato editado a tabla de proveedores
        $this->assertDatabaseHas('providers', [
            "nit"=>  $data2['dat']['nit'],
            "name"=>  $data2['dat']['name'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseHas('provider_phones', [
            "provider_id" => "2",
            "number" => $data2['dat']['phone']
        ]);

        //comprobacion de mensaje de respuesta
        $response->assertSessionHas('fail_msg','Ya existe un proveedor con este nit');
    }

    /**
     * test que permite verificar la edicion de un proveedor no se raliza al intentar editar su telefono por uno existente
     *
     * @return void
     */
    public function test_provider_edit_phone_repeat_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "00-7371413",
            "name" => "Carlos Nissan",
            "mail" => "carlos@nissan.com",
            "phone" => "753817",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #3",
        );
        // Datos de un proveedor
        $data2['dat'] = array(
            "nit" => "84671",
            "name" => "Folson",
            "mail" => "folson@mail.com",
            "phone" => "672962",
            "country" => "Colombia",
            "departament" => "Cundinamarca",
            "city" => "Bogota",
            "address" => "Av1",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data);
        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data2);

        // Datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "nit" => $data2['dat']['nit'],
            "name" => $data2['dat']['name'],
            "mail" => $data2['dat']['mail'],
            "phone" => $data['dat']['phone'], //repetido
            "country" => $data2['dat']['country'],
            "departament" => $data2['dat']['departament'],
            "city" => $data2['dat']['city'],
            "address" => $data2['dat']['address'],
        );

        //Acceso a la funcion que permite editar un proveedor
        $response = $this->post(route('edit_provider'), $dataEdit);

        //comprobacion de dato editado a tabla de proveedores
        $this->assertDatabaseHas('providers', [
            "nit"=>  $data2['dat']['nit'],
            "name"=>  $data2['dat']['name'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseHas('provider_phones', [
            "provider_id" => "2",
            "number" => $data2['dat']['phone']
        ]);

        //comprobacion de mensaje de respuesta
        $response->assertSessionHas('fail_msg','Un proveedor ya tiene este número de teléfono');
    }

    /**
     * test que permite verificar la edicion de un proveedor no se raliza al intentar editar su correo por uno existente
     *
     * @return void
     */
    public function test_provider_edit_email_repeat_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
            "nit" => "00-7371413",
            "name" => "Carlos Nissan",
            "mail" => "carlos@nissan.com",
            "phone" => "753817",
            "country" => "Colombia",
            "departament" => "Quindío",
            "city" => "Armenia",
            "address" => "calle #3",
        );
        // Datos de un proveedor
        $data2['dat'] = array(
            "nit" => "84671",
            "name" => "Folson",
            "mail" => "folson@mail.com",
            "phone" => "672962",
            "country" => "Colombia",
            "departament" => "Cundinamarca",
            "city" => "Bogota",
            "address" => "Av1",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data);
        //Acceso a la funcion que permite agregar un proveedor
        $this->post(route('add_provider'), $data2);

        // Datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "nit" => $data2['dat']['nit'],
            "name" => $data2['dat']['name'],
            "mail" => $data['dat']['mail'], //repetido
            "phone" => $data2['dat']['phone'],
            "country" => $data2['dat']['country'],
            "departament" => $data2['dat']['departament'],
            "city" => $data2['dat']['city'],
            "address" => $data2['dat']['address'],
        );

        //Acceso a la funcion que permite editar un proveedor
        $response = $this->post(route('edit_provider'), $dataEdit);

        //comprobacion de dato editado a tabla de proveedores
        $this->assertDatabaseHas('providers', [
            "nit"=>  $data2['dat']['nit'],
            "name"=>  $data2['dat']['name'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseHas('provider_phones', [
            "provider_id" => "2",
            "number" => $data2['dat']['phone']
        ]);

        //comprobacion de mensaje de respuesta
        $response->assertSessionHas('fail_msg','Un proveedor ya tiene este correo electrónico');
    }

    /**
     * test que permite eliminar un proveedor
     *
     * @return void
     */
    public function test_provider_delete_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
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
        $this->post(route('add_provider'), $data);

        //petecion de eliminacion de proveedor
        $del=[
            "_token" => csrf_token(),
            "selected"=> array('1') //id del proveedor
        ];

        //peticion de eliminacion
        $response = $this->delete('/proveedores',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(1));

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseMissing('providers', [
            "nit"=>  $data['dat']['nit'],
            "name"=>  $data['dat']['name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseMissing('provider_phones', [
            "number" => $data['dat']['phone'],
            "deleted_at"=> null
        ]);
    }

    /**
     * test que permite validar la eliminacion de un proveedor
     *
     * @return void
     */
    public function test_provider_delete_noData_test()
    {
        // Datos de un proveedor
        $data['dat'] = array(
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
        $this->post(route('add_provider'), $data);

        //petecion de eliminacion de proveedor
        $del=[
            "_token" => csrf_token(),
            "selected"=> array() //id del proveedor
        ];

        //peticion de eliminacion
        $response = $this->delete('/proveedores',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(0));

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseHas('providers', [
            "nit"=>  $data['dat']['nit'],
            "name"=>  $data['dat']['name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos proveedores
        $this->assertDatabaseHas('provider_phones', [
            "number" => $data['dat']['phone'],
            "deleted_at"=> null
        ]);
    }
}
