<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test que permite comproblar el funcionamiento basico de la vista de compras
     *
     * @return void
     */
    public function test_purchase_page_test()
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
     * Test que permite agregar una compra
     */
    public function test_purchase_add_test(){
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
        $response = $this->post(route('add_purchase', $dataPurchase));

        //validando tabla compras
        $this->assertDatabaseHas('purchases', [
            'id' => '1',
            'cod' => $dataPurchase['dat']['cod'],
            'provider_id' => $dataPurchase['dat']['provider'],
            'cost' => $dataPurchase['dat']['cost'],
            'status' => $dataPurchase['dat']['status'],
            'concept' => $dataPurchase['dat']['concept'],
            'date' => $dataPurchase['dat']['date'],
            'deleted_at' => null
        ]);

        //validando tabla productos
        $this->assertDatabaseCount('products', 3);

        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-1',
            "name"=>  $dataPurchase['product']['nameProduct1'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct1']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-2',
            "name"=>  $dataPurchase['product']['nameProduct2'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct2']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-3',
            "name"=>  $dataPurchase['product']['nameProduct3'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct3']
        ]);

        //validando la tabla ordenes de productos

        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct1'],
            "product_id"=>  '1',
            "cost"=>   $dataPurchase['product']['amountProduct1'] * $dataPurchase['product']['costProduct1'],
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct2'],
            "product_id"=>  '2',
            "cost"=>   $dataPurchase['product']['amountProduct2'] * $dataPurchase['product']['costProduct2'],
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct3'],
            "product_id"=>  '3',
            "cost"=>   $dataPurchase['product']['amountProduct3'] * $dataPurchase['product']['costProduct3'],
        ]);

        //mensaje de respuesta
        $response->assertSessionHas('check_msg', 'La compra se registro con éxito');
    }

    /**
     * test que permite comprobar el la vista de compras por parte del usuario
     * de un dato que se encuentra en la BD
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


        //llamado a la vista de productos
        $response = $this->get(route('view_purchase'));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de compras','Agrega, actualiza o elimina registros de compras',
            'ID','Código','Fecha compra','Valor','Descripción','Estado','Proveedor',
            "1",$dataPurchase['dat']['cod'],'17/11/2020',$dataPurchase['dat']['cost'],$dataPurchase['dat']['concept'],'Pago',$dataProvider['dat']['name']
        ]);

    }

    /**
     * test que permite comprobar el la vista de compras por parte del usuario
     * de un dato que se encuentra en la BD
     * @return void
     */
    public function test_purchase_find_view_test()
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

        $find['dat'] = array(
            'search' => '1'
        );

        //llamado a la vista de productos
        $response = $this->get(route('view_purchase',$find));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSee(['Gestión de compras','Agrega, actualiza o elimina registros de compras',
            'ID','Código','Fecha compra','Valor','Descripción','Estado','Proveedor',
            "1",$dataPurchase['dat']['cod'],'17/11/2020',$dataPurchase['dat']['cost'],$dataPurchase['dat']['concept'],'Pago',$dataProvider['dat']['name']
        ]);

    }

    /**
     * Test que permite verificar que no se agrega una compra con codigo repetido
     */
    public function test_purchase_add_cod_repeat_test(){
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

        //datos de la nueva compra
        $dataPurchase['dat'] = array(
            'cod' => '1', //repetido
            'date' => '2020-11-11',
            'cost' => '100000',
            'concept' => 'Insumos de reparacion',
            'status' => 'Pago',
            'provider' => '1'
        );

        //datos a agregar de los productos
        $dataPurchase['product'] = array(
            'nameProduct1' => 'Llaves 15',
            'costProduct1' => '50000',
            'amountProduct1' => '14',
            'nameProduct2' => 'Juego de tuercas y tornillos 12mm',
            'costProduct2' => '58254',
            'amountProduct2' => '30',
        );

        //agregando la compra
        $response = $this->post(route('add_purchase', $dataPurchase));

        //validando tabla compras
        $this->assertDatabaseMissing('purchases', [
            'id' => '2',
            'cod' => $dataPurchase['dat']['cod'],
            'provider_id' => $dataPurchase['dat']['provider'],
            'cost' => $dataPurchase['dat']['cost'],
            'status' => $dataPurchase['dat']['status'],
            'concept' => $dataPurchase['dat']['concept'],
            'date' => $dataPurchase['dat']['date'],
            'deleted_at' => null
        ]);

        //validando tabla productos
        $this->assertDatabaseCount('products', 3);

        $this->assertDatabaseMissing('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-1',
            "name"=>  $dataPurchase['product']['nameProduct1'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct1']
        ]);
        $this->assertDatabaseMissing('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-2',
            "name"=>  $dataPurchase['product']['nameProduct2'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct2']
        ]);


        //validando la tabla ordenes de productos

        $this->assertDatabaseMissing('product_orders', [
            "purchase_id"=> '2',
            "quantity"=>  $dataPurchase['product']['amountProduct1'],
            "product_id"=>  '1',
            "cost"=>   $dataPurchase['product']['amountProduct1'] * $dataPurchase['product']['costProduct1'],
        ]);
        $this->assertDatabaseMissing('product_orders', [
            "purchase_id"=> '2',
            "quantity"=>  $dataPurchase['product']['amountProduct2'],
            "product_id"=>  '2',
            "cost"=>   $dataPurchase['product']['amountProduct2'] * $dataPurchase['product']['costProduct2'],
        ]);

        //mensaje de respuesta
        $response->assertSessionHas('fail_msg', 'La compra con el código '.$dataPurchase['dat']['cod'].' ingresado ya existe');
    }

    /**
     * Test que permite verificar que una compra no se agrega si no existe un proveedor seleccionado
     */
    public function test_purchase_add_provider_undefined_test(){
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
        $response = $this->post(route('add_purchase', $dataPurchase));

        //validando tabla compras
        $this->assertDatabaseMissing('purchases', [
            'id' => '1',
            'cod' => $dataPurchase['dat']['cod'],
            'provider_id' => $dataPurchase['dat']['provider'],
            'cost' => $dataPurchase['dat']['cost'],
            'status' => $dataPurchase['dat']['status'],
            'concept' => $dataPurchase['dat']['concept'],
            'date' => $dataPurchase['dat']['date'],
            'deleted_at' => null
        ]);

        //validando tabla productos
        $this->assertDatabaseCount('products', 0);

        $this->assertDatabaseMissing('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-1',
            "name"=>  $dataPurchase['product']['nameProduct1'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct1']
        ]);
        $this->assertDatabaseMissing('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-2',
            "name"=>  $dataPurchase['product']['nameProduct2'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct2']
        ]);
        $this->assertDatabaseMissing('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-3',
            "name"=>  $dataPurchase['product']['nameProduct3'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct3']
        ]);

        //validando la tabla ordenes de productos

        $this->assertDatabaseMissing('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct1'],
            "product_id"=>  '1',
            "cost"=>   $dataPurchase['product']['amountProduct1'] * $dataPurchase['product']['costProduct1'],
        ]);
        $this->assertDatabaseMissing('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct2'],
            "product_id"=>  '2',
            "cost"=>   $dataPurchase['product']['amountProduct2'] * $dataPurchase['product']['costProduct2'],
        ]);
        $this->assertDatabaseMissing('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct3'],
            "product_id"=>  '3',
            "cost"=>   $dataPurchase['product']['amountProduct3'] * $dataPurchase['product']['costProduct3'],
        ]);

        //mensaje de respuesta
        $response->assertSessionHas('fail_msg', 'El proveedor seleccionado no existe');
    }

    /**
     * test que permite comprobar la la obtencion de una compra a partir de su id
     */
    public function test_get_purchase_test()
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
            'cost' => '120000.0',
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

        //obtener la compra con el id 1
        $response = $this->get('/compras/1');

        //array esperado
        $expected = array(
            array(
                'all_product_cost' => '280000.0',
                'cod' => '1',
                'concept' => $dataPurchase['dat']['concept'],
                'cost' => $dataPurchase['dat']['cost'],
                'date' => $dataPurchase['dat']['date'],
                'id' => '1',
                'product_id' => '1',
                'product_name' => $dataPurchase['product']['nameProduct1'],
                'provider_id' => '1',
                'provider_name' => 'Carlos Nissan',
                'quantity' => $dataPurchase['product']['amountProduct1'],
                'status' => 'Pago',
            ),
            array(
                'all_product_cost' => '500000.0',
                'cod' => '1',
                'concept' => $dataPurchase['dat']['concept'],
                'cost' => $dataPurchase['dat']['cost'],
                'date' => $dataPurchase['dat']['date'],
                'id' => '1',
                'product_id' => '3',
                'product_name' => $dataPurchase['product']['nameProduct3'],
                'provider_id' => '1',
                'provider_name' => 'Carlos Nissan',
                'quantity' => $dataPurchase['product']['amountProduct3'],
                'status' => 'Pago'
            ),
            array(
                'all_product_cost' => '2559000.0',
                'cod' => '1',
                'concept' => $dataPurchase['dat']['concept'],
                'cost' => $dataPurchase['dat']['cost'],
                'date' => $dataPurchase['dat']['date'],
                'id' => '1',
                'product_id' => '2',
                'product_name' => $dataPurchase['product']['nameProduct2'],
                'provider_id' => '1',
                'provider_name' => 'Carlos Nissan',
                'quantity' => $dataPurchase['product']['amountProduct2'],
                'status' => 'Pago',
            )
        );
        //respuesta
        $response->assertSimilarJson($expected);
    }

    /**
     * Test que permite eliminar una compra
     */
    public function test_purchase_delete_test(){
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

        //petecion de eliminacion de compra
        $del=[
            "_token" => csrf_token(),
            "selected"=> array('1') //id de la compra
        ];

        $response = $this->delete('/compras',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(1));

        //validando tabla compras
        $this->assertDatabaseMissing('purchases', [
            'id' => '1',
            'cod' => $dataPurchase['dat']['cod'],
            'provider_id' => $dataPurchase['dat']['provider'],
            'cost' => $dataPurchase['dat']['cost'],
            'status' => $dataPurchase['dat']['status'],
            'concept' => $dataPurchase['dat']['concept'],
            'date' => $dataPurchase['dat']['date'],
            'deleted_at' => null
        ]);

        //validando tabla productos
        $this->assertDatabaseCount('products', 3);

        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-1',
            "name"=>  $dataPurchase['product']['nameProduct1'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct1']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-2',
            "name"=>  $dataPurchase['product']['nameProduct2'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct2']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-3',
            "name"=>  $dataPurchase['product']['nameProduct3'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct3']
        ]);

        //validando la tabla ordenes de productos

        $this->assertDatabaseMissing('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct1'],
            "product_id"=>  '1',
            "cost"=>   $dataPurchase['product']['amountProduct1'] * $dataPurchase['product']['costProduct1'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseMissing('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct2'],
            "product_id"=>  '2',
            "cost"=>   $dataPurchase['product']['amountProduct2'] * $dataPurchase['product']['costProduct2'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseMissing('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct3'],
            "product_id"=>  '3',
            "cost"=>   $dataPurchase['product']['amountProduct3'] * $dataPurchase['product']['costProduct3'],
            'deleted_at' => null
        ]);

    }
    /**
     * Test que permite eliminar una compra
     */
    public function test_purchase_delete_noData_test(){
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

        //petecion de eliminacion de compra
        $del=[
            "_token" => csrf_token(),
            "selected"=> array() //id de la compra
        ];

        $response = $this->delete('/compras',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(0));

        //validando tabla compras
        $this->assertDatabaseHas('purchases', [
            'id' => '1',
            'cod' => $dataPurchase['dat']['cod'],
            'provider_id' => $dataPurchase['dat']['provider'],
            'cost' => $dataPurchase['dat']['cost'],
            'status' => $dataPurchase['dat']['status'],
            'concept' => $dataPurchase['dat']['concept'],
            'date' => $dataPurchase['dat']['date'],
            'deleted_at' => null
        ]);

        //validando tabla productos
        $this->assertDatabaseCount('products', 3);

        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-1',
            "name"=>  $dataPurchase['product']['nameProduct1'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct1']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-2',
            "name"=>  $dataPurchase['product']['nameProduct2'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct2']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-3',
            "name"=>  $dataPurchase['product']['nameProduct3'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct3']
        ]);

        //validando la tabla ordenes de productos

        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct1'],
            "product_id"=>  '1',
            "cost"=>   $dataPurchase['product']['amountProduct1'] * $dataPurchase['product']['costProduct1'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct2'],
            "product_id"=>  '2',
            "cost"=>   $dataPurchase['product']['amountProduct2'] * $dataPurchase['product']['costProduct2'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct3'],
            "product_id"=>  '3',
            "cost"=>   $dataPurchase['product']['amountProduct3'] * $dataPurchase['product']['costProduct3'],
            'deleted_at' => null
        ]);

    }

    /**
     * Test que permite verificar la correcta modificacion de una compra
     */
    public function test_purchase_edit_test(){
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
        // Datos de un proveedor
        $dataProvider2['dat'] = array(
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
        $this->post(route('add_provider'), $dataProvider);
        $this->post(route('add_provider'), $dataProvider2);

        //datos a agregar de la compra
        $dataPurchase['dat'] = array(
            'cod' => '1',
            'date' => '2020-11-17',
            'cost' => '120000',
            'concept' => 'Compra de repuestos para nissan 2010',
            'status' => 'NoPago',
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

        //datos a editar de la compra
        $dataPurchaseEdit['dat'] = array(
            'id' => '1',
            'cod' => '1',
            'date' => '2020-11-20', //cambio
            'cost' => '567111', //cambio
            'concept' => 'Compra de repuestos para nissan 2011', //cambio
            'status' => 'Pago', //cambio
            'provider' => '2' //cambio
        );

        $response = $this->post(route('edit_purchase'),$dataPurchaseEdit);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','Se actualizaron los datos de la compra con éxito');

        //validando tabla compras
        $this->assertDatabaseHas('purchases', [
            'id' => '1',
            'cod' => $dataPurchaseEdit['dat']['cod'],
            'provider_id' => $dataPurchaseEdit['dat']['provider'],
            'cost' => $dataPurchaseEdit['dat']['cost'],
            'status' => $dataPurchaseEdit['dat']['status'],
            'concept' => $dataPurchaseEdit['dat']['concept'],
            'date' => $dataPurchaseEdit['dat']['date'],
            'deleted_at' => null
        ]);

        //validando tabla productos
        $this->assertDatabaseCount('products', 3);

        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-1',
            "name"=>  $dataPurchase['product']['nameProduct1'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct1']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-2',
            "name"=>  $dataPurchase['product']['nameProduct2'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct2']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-3',
            "name"=>  $dataPurchase['product']['nameProduct3'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct3']
        ]);

        //validando la tabla ordenes de productos

        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct1'],
            "product_id"=>  '1',
            "cost"=>   $dataPurchase['product']['amountProduct1'] * $dataPurchase['product']['costProduct1'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct2'],
            "product_id"=>  '2',
            "cost"=>   $dataPurchase['product']['amountProduct2'] * $dataPurchase['product']['costProduct2'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct3'],
            "product_id"=>  '3',
            "cost"=>   $dataPurchase['product']['amountProduct3'] * $dataPurchase['product']['costProduct3'],
            'deleted_at' => null
        ]);


    }

    /**
     * Test que permite verificar que una compra no se edita al intentar editar el codigo de la compra con uno existente
     */
    public function test_purchase_edit_code_repeat_test(){
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
            'status' => 'NoPago',
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

        //datos de la nueva compra
        $dataPurchase2['dat'] = array(
            'cod' => '2',
            'date' => '2020-11-11',
            'cost' => '100000',
            'concept' => 'Insumos de reparacion',
            'status' => 'Pago',
            'provider' => '1'
        );

        //datos a agregar de los productos
        $dataPurchase2['product'] = array(
            'nameProduct1' => 'Llaves 15',
            'costProduct1' => '50000',
            'amountProduct1' => '14',
            'nameProduct2' => 'Juego de tuercas y tornillos 12mm',
            'costProduct2' => '58254',
            'amountProduct2' => '30',
        );
        //agregando la compra
        $this->post(route('add_purchase', $dataPurchase2));

        //datos a editar de la compra
        $dataPurchaseEdit['dat'] = array(
            'id' => '2',
            'cod' => '1', //repetido
            'date' => '2020-11-20', //cambio
            'cost' => '567111', //cambio
            'concept' => 'Compra de repuestos para nissan 2011', //cambio
            'status' => 'Pago', //cambio
            'provider' => '1' //cambio
        );

        $response = $this->post(route('edit_purchase'),$dataPurchaseEdit);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Ya existe una compra con este código');

        //validando tabla compras
        $this->assertDatabaseMissing('purchases', [
            'id' => '2',
            'cod' => $dataPurchaseEdit['dat']['cod'],
            'provider_id' => $dataPurchaseEdit['dat']['provider'],
            'cost' => $dataPurchaseEdit['dat']['cost'],
            'status' => $dataPurchaseEdit['dat']['status'],
            'concept' => $dataPurchaseEdit['dat']['concept'],
            'date' => $dataPurchaseEdit['dat']['date'],
            'deleted_at' => null
        ]);

        //validando tabla productos
        $this->assertDatabaseCount('products', 5);

        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-1',
            "name"=>  $dataPurchase['product']['nameProduct1'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct1']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-2',
            "name"=>  $dataPurchase['product']['nameProduct2'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct2']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-3',
            "name"=>  $dataPurchase['product']['nameProduct3'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct3']
        ]);

        //validando la tabla ordenes de productos

        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct1'],
            "product_id"=>  '1',
            "cost"=>   $dataPurchase['product']['amountProduct1'] * $dataPurchase['product']['costProduct1'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct2'],
            "product_id"=>  '2',
            "cost"=>   $dataPurchase['product']['amountProduct2'] * $dataPurchase['product']['costProduct2'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct3'],
            "product_id"=>  '3',
            "cost"=>   $dataPurchase['product']['amountProduct3'] * $dataPurchase['product']['costProduct3'],
            'deleted_at' => null
        ]);


    }

    /**
     * Test que permite verificar que una compra no se edita al intentar editar el con un proveedor inexistente
     */
    public function test_purchase_edit_provider_undefined_test(){
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
            'status' => 'NoPago',
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

        //datos de la nueva compra
        $dataPurchase2['dat'] = array(
            'cod' => '2',
            'date' => '2020-11-11',
            'cost' => '100000',
            'concept' => 'Insumos de reparacion',
            'status' => 'Pago',
            'provider' => '1'
        );

        //datos a agregar de los productos
        $dataPurchase2['product'] = array(
            'nameProduct1' => 'Llaves 15',
            'costProduct1' => '50000',
            'amountProduct1' => '14',
            'nameProduct2' => 'Juego de tuercas y tornillos 12mm',
            'costProduct2' => '58254',
            'amountProduct2' => '30',
        );
        //agregando la compra
        $this->post(route('add_purchase', $dataPurchase2));

        //datos a editar de la compra
        $dataPurchaseEdit['dat'] = array(
            'id' => '2',
            'cod' => '1', //repetido
            'date' => '2020-11-20', //cambio
            'cost' => '567111', //cambio
            'concept' => 'Compra de repuestos para nissan 2011', //cambio
            'status' => 'Pago', //cambio
            'provider' => '99' //no existe este proveedor
        );

        $response = $this->post(route('edit_purchase'),$dataPurchaseEdit);

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg', 'El proveedor al cual está intentado asignar la compra no existe');

        //validando tabla compras
        $this->assertDatabaseMissing('purchases', [
            'id' => '2',
            'cod' => $dataPurchaseEdit['dat']['cod'],
            'provider_id' => $dataPurchaseEdit['dat']['provider'],
            'cost' => $dataPurchaseEdit['dat']['cost'],
            'status' => $dataPurchaseEdit['dat']['status'],
            'concept' => $dataPurchaseEdit['dat']['concept'],
            'date' => $dataPurchaseEdit['dat']['date'],
            'deleted_at' => null
        ]);

        //validando tabla productos
        $this->assertDatabaseCount('products', 5);

        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-1',
            "name"=>  $dataPurchase['product']['nameProduct1'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct1']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-2',
            "name"=>  $dataPurchase['product']['nameProduct2'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct2']
        ]);
        $this->assertDatabaseHas('products', [
            "code"=> $dataPurchase['dat']['cod'] . '-3',
            "name"=>  $dataPurchase['product']['nameProduct3'],
            "sale_price"=>  0,
            "description"=>  null,
            "units_available"=> $dataPurchase['product']['amountProduct3']
        ]);

        //validando la tabla ordenes de productos

        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct1'],
            "product_id"=>  '1',
            "cost"=>   $dataPurchase['product']['amountProduct1'] * $dataPurchase['product']['costProduct1'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct2'],
            "product_id"=>  '2',
            "cost"=>   $dataPurchase['product']['amountProduct2'] * $dataPurchase['product']['costProduct2'],
            'deleted_at' => null
        ]);
        $this->assertDatabaseHas('product_orders', [
            "purchase_id"=> '1',
            "quantity"=>  $dataPurchase['product']['amountProduct3'],
            "product_id"=>  '3',
            "cost"=>   $dataPurchase['product']['amountProduct3'] * $dataPurchase['product']['costProduct3'],
            'deleted_at' => null
        ]);


    }
}
