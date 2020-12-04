<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Class ProductTest
 * @package Tests\Feature
 */
class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test que permite comproblar el funcionamiento basico de la vista de productos
     *
     * @return void
     */
    public function test_product_page_test()
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
     * test que permite comprobar la vista de productos por parte del usuario
     * de un dato que se encuentra en la BD
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

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "70000",
            "amount" => "20",
            "description" => "Aceite para motor",
        );
        //Acceso a la funcion que permite agregar un producto
        $this->post(route('add_product'), $data);

        //llamado a la vista de productos
        $response = $this->get(route('view_product'));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de productos','Agrega, actualiza o elimina registros de productos',
            'ID','Código','Nombre','Valor Unidad','Cantidad','Descripción','Proveedor',
            "1",$data['dat']['cod'],$data['dat']['name'],$data['dat']['price'],$data['dat']['amount'],$data['dat']['description']
        ]);

    }

    /**
     * test que permite comprobar el la vista de productos por parte del usuario
     * de un dato que se encuentra en la BD
     * @return void
     */
//    public function test_product_find_view_test()
//    {
//        //crecion de usuario
//        $user = new User();
//        $user->name = 'amdin';
//        $user->email = 'admin@mail.com';
//        $user->password = 'admin312';
//        $user->save();
//
//        //autenticacion de usuario
//        Auth::loginUsingId(1);
//
//        //comprobacion de autenticacion
//        $this->assertAuthenticated();
//
//        // Datos de un producto
//        $data['dat'] = array(
//            "cod" => "2791",
//            "name" => "Aceite 2000",
//            "price" => "70000",
//            "amount" => "20",
//            "description" => "Aceite para motor",
//        );
//        //Acceso a la funcion que permite agregar un producto
//        $this->post(route('add_product'), $data);
//
//        $find['dat'] = array(
//            'search' => '2791'
//        );
//
//        //llamado a la vista de productos
//        $response = $this->get(route('view_product',$find));
//
//        //comprobacion de visualizacion correcta por parte de el usuario
//        $response->assertSee(['Gestión de productos','Agrega, actualiza o elimina registros de productos',
//            'ID','Código','Nombre','Valor Unidad','Cantidad','Descripción','Proveedor',
//            "1",$data['dat']['cod'],$data['dat']['name'],$data['dat']['price'],$data['dat']['amount'],$data['dat']['description']
//        ]);
//
//    }

    /**
     * test que permite agregar un producto que no existe
     *
     * @return void
     */
    public function test_product_add_test()
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

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "70000",
            "amount" => "20",
            "description" => "Aceite para motor",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $response = $this->post(route('add_product'), $data);

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseHas('products', [
            "code"=>  $data['dat']['cod'],
            "name"=>  $data['dat']['name'],
            "sale_price"=>  $data['dat']['price'],
            "description"=>  $data['dat']['description'],
            "units_available"=> $data['dat']['amount']
        ]);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg', 'El registro del producto se realizo con éxito');
    }

    /**
     * test que permite verificar que no se agrega un producto con codigo repetido
     *
     * @return void
     */
    public function test_product_add_code_repeat_test()
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

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "70000",
            "amount" => "20",
            "description" => "Aceite para motor",
        );
        //agrar el producto
        $this->post(route('add_product'), $data);

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791", //repetido
            "name" => "Arbol de levas",
            "price" => "120000",
            "amount" => "60",
            "description" => "Arbol de levas para carros nissan 2020",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $response = $this->post(route('add_product'), $data);

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseMissing('products', [
            "code"=>  $data['dat']['cod'],
            "name"=>  $data['dat']['name'],
            "sale_price"=>  $data['dat']['price'],
            "description"=>  $data['dat']['description'],
            "units_available"=> $data['dat']['amount']
        ]);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg', 'El código de producto ingresado ya existe');
    }

    /**
     * test que permite comprobar la la obtencion de un producto a partir de su id
     */
    public function test_get_provider_test()
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

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "70000.0",
            "amount" => "20",
            "description" => "Aceite para motor",
        );

        //agrar el producto
        $this->post(route('add_product'), $data);

        //obtener un produto con el id 1
        $response = $this->get('/productos/1');

        //array esperado
        $expected = array(
            "code" => $data['dat']['cod'],
            "description" => $data['dat']['description'],
            "id" => "1",
            "name" => $data['dat']['name'],
            "product_id" => null,
            "provider_name" => null,
            "purchase_id" => null,
            "sale_price" => $data['dat']['price'],
            "units_available" => $data['dat']['amount'],
        );
        $response->assertSimilarJson(array($expected));
    }

    /**
     * test que permite editar un producto existe
     *
     * @return void
     */
    public function test_product_edit_test()
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

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "70000",
            "amount" => "20",
            "description" => "Aceite para motor",
        );
        //agregar producto
        $this->post(route('add_product'), $data);

        // Datos a editar de un producto
        $dataEdit['dat'] = array(
            "id" => "1",
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "50000", //cambio
            "amount" => "10", //cambio
            "description" => "Aceite para motor de carros nissan 2010", //cambio
        );

        //Acceso a la funcion que permite agregar un proveedor
        $response = $this->post(route('edit_product'), $dataEdit);

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseHas('products', [
            "code"=>  $dataEdit['dat']['cod'],
            "name"=>  $dataEdit['dat']['name'],
            "sale_price"=>  $dataEdit['dat']['price'],
            "description"=>  $dataEdit['dat']['description'],
            "units_available"=> $dataEdit['dat']['amount']
        ]);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','Se actualizaron los datos del producto con éxito');
    }

    /**
     * test que permite verificar la no edicion del capo codigo repetido
     *
     * @return void
     */
    public function test_product_edit_code_repeat_test()
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

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "70000",
            "amount" => "20",
            "description" => "Aceite para motor",
        );
        // Datos de un producto
        $data2['dat'] = array(
            "cod" => "7491",
            "name" => "Arbol de levas",
            "price" => "120000",
            "amount" => "60",
            "description" => "Arbol de levas para carros nissan 2020",
        );
        //agregar producto
        $this->post(route('add_product'), $data);
        //agregar producto
        $this->post(route('add_product'), $data2);

        // Datos a editar de un producto
        $dataEdit['dat'] = array(
            "id" => "2",
            "cod" => "2791", //repetido
            "name" => "Arbol de levas",
            "price" => "120000",
            "amount" => "50", //cambio
            "description" => "Arbol de levas para carros nissan 2010",
        );

        //Acceso a la funcion que permite agregar un proveedor
        $response = $this->post(route('edit_product'), $dataEdit);

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseHas('products', [
            "code"=>  $data2['dat']['cod'],
            "name"=>  $data2['dat']['name'],
            "sale_price"=>  $data2['dat']['price'],
            "description"=>  $data2['dat']['description'],
            "units_available"=> $data2['dat']['amount']
        ]);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Ya existe un producto con este código');
    }

    /**
     * test que permite eliminar un producto
     *
     * @return void
     */
    public function test_product_delete_test()
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

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "70000",
            "amount" => "20",
            "description" => "Aceite para motor",
        );

        //agregar producto
        $this->post(route('add_product'), $data);

        //petecion de eliminacion del producto
        $del=[
            "_token" => csrf_token(),
            "selected"=> array('1') //id del producto
        ];

        //peticion de eliminacion
        $response = $this->delete('/productos',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(1));

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseMissing('products', [
            "code"=>  $data['dat']['cod'],
            "name"=>  $data['dat']['name'],
            "sale_price"=>  $data['dat']['price'],
            "description"=>  $data['dat']['description'],
            "units_available"=> $data['dat']['amount'],
            "deleted_at"=> null
        ]);

    }

    /**
     * test que permite eliminar un producto
     *
     * @return void
     */
    public function test_product_delete_noData_test()
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

        // Datos de un producto
        $data['dat'] = array(
            "cod" => "2791",
            "name" => "Aceite 2000",
            "price" => "70000",
            "amount" => "20",
            "description" => "Aceite para motor",
        );

        //agregar producto
        $this->post(route('add_product'), $data);

        //petecion de eliminacion del producto
        $del=[
            "_token" => csrf_token(),
            "selected"=> array() //id del producto
        ];

        //peticion de eliminacion
        $response = $this->delete('/productos',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(0));

        //comprobacion de dato agregado a tabla de proveedores
        $this->assertDatabaseHas('products', [
            "code"=>  $data['dat']['cod'],
            "name"=>  $data['dat']['name'],
            "sale_price"=>  $data['dat']['price'],
            "description"=>  $data['dat']['description'],
            "units_available"=> $data['dat']['amount'],
            "deleted_at"=> null
        ]);

    }
}
