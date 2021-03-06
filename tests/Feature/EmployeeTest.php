<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * Class EmployeeTest
 * @package Tests\Feature
 */
class EmployeeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * test que permite comproblar el funcionamiento basico de la vista de empleados
     */
    public function test_employee_page_test()
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

        $response = $this->get('/empleados');

        $response->assertStatus(200);
    }
    /**
     * test que permite comprobar el la vista por parte del usuario
     * de un dato que se encuentra en la BD
     * @return void
     */
    public function test_employee_view_test()
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

        // Datos de un empleado
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        //llamado a la vista de empleados
        $response = $this->get(route('view_employee'));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSeeInOrder(['Gestión de empleados','Agrega, actualiza o elimina registros de empleados',
            'ID','Cédula','Nombre','Apellidos','Télefono','Dirección','Correo',
            $data['dat']['cc'],$data['dat']['name'],$data['dat']['last_name'],$data['dat']['phone'],$data['dat']['address'],$data['dat']['mail']
        ]);

    }

    /**
     * test que permite comprobar el la vista la busqueda de un empleado
     * de un dato que se encuentra en la BD
     * @return void
     */
    public function test_employee_find_view_test()
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

        // Datos de un empleado
        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        $find['dat'] = array(
            "search" => "1004"
        );
        //llamado a la vista de empleados
        $response = $this->get(route('view_employee',$find));

        //comprobacion de visualizacion correcta por parte de el usuario
        $response->assertSeeInOrder(['Gestión de empleados','Agrega, actualiza o elimina registros de empleados',
            'ID','Cédula','Nombre','Apellidos','Télefono','Dirección','Correo',
            $data['dat']['cc'],$data['dat']['name'],$data['dat']['last_name'],$data['dat']['phone'],$data['dat']['address'],$data['dat']['mail']
        ]);

    }
    /**
     * test que permite agregar un empleado que no existe
     */
    public function test_employee_add_test()
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

        //datos de entrada

        $data['dat'] = array(
        "cc" => "1004",
        "name" => "Alfredo",
        "last_name" => "Jimenez Murcia",
        "phone" => "31235",
        "address" => "calle #3",
        "mail" => "afc@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $response = $this->post(route('add_employee'), $data);

        //comprobacion de dato agregado a tabla de empleados
        $this->assertDatabaseHas('employees', [
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['last_name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('employee_phones', [
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','El empleado se registro con éxito');
    }

    /**
     * test que permite comprobar la no repetición de cedulas
     */
    public function test_employee_add_identificationCard_repeat_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Antonio",
            "last_name" => "Murcia",
            "phone" => "71851",
            "address" => "calle #3",
            "mail" => "Amurcia@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $response = $this->post(route('add_employee'), $data);

        //comprobacion de dato agregado a tabla de empleados
        $this->assertDatabaseMissing('employees', [
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['last_name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseMissing('employee_phones', [
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Este empleado ya se encuentra registrado');
    }

    /**
     * test que permite comprobar la no repetición de un correo electronico
     */
    public function test_employee_add_mail_repeat_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        //datos de entrada

        $data['dat'] = array(
            "cc" => "95715",
            "name" => "Antonio",
            "last_name" => "Murcia",
            "phone" => "71851",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $response = $this->post(route('add_employee'), $data);

        //comprobacion de dato agregado a tabla de empleados
        $this->assertDatabaseMissing('employees', [
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['last_name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseMissing('employee_phones', [
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Este correo ya se encuentra asociado a un cliente');
    }

    /**
     * test que permite comprobar la no repetición de un telefono
     */
    public function test_employee_add_phone_repeat_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        //datos de entrada

        $data['dat'] = array(
            "cc" => "95715",
            "name" => "Antonio",
            "last_name" => "Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "amurcia@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $response = $this->post(route('add_employee'), $data);

        //comprobacion de dato agregado a tabla de empleados
        $this->assertDatabaseMissing('employees', [
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['last_name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);

        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseMissing('employee_phones', [
            "employee_id" => '2',
            "number" => $data['dat']['phone']
        ]);
        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Este teléfono ya se encuentra asociado a un cliente');
    }

    /**
     * test que permite comprobar la la obtencion de un empleado a partir de su cc
     */
    public function test_get_employee_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );

        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        $response = $this->get('/empleados/1004');

        //array esperado
        $expected = array(
            'id' => '1',
            'identification_card' => $data['dat']['cc'],
            'name' => $data['dat']['name'],
            'last_name' => $data['dat']['last_name'],
            'address' => $data['dat']['address'],
            'mail' => $data['dat']['mail'],
            'number' => $data['dat']['phone'],
        );
        $response->assertSimilarJson(array($expected));
    }

    /**
     * test que permite comprobar la actualizacion de los datos de un empleado adecuadamente
     */
    public function test_employee_edit_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );

        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        //datos a editar
        $dataEdit['dat'] = array(
            "id" => "1",
            "cc" => "1004",
            "name" => "Carlos Antionio", //cambio
            "last_name" => "Jimenez Murcia",
            "phone" => "300571675", //cambio
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );

        //petecion de edicion
        $response = $this->post(route('edit_employee',$dataEdit));

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('check_msg','Se actualizaron los datos del empleado con éxito');

        //comprobacion de dato agregado a tabla de empleados
        $this->assertDatabaseHas('employees', [
            "identification_card"=>  $dataEdit['dat']['cc'],
            "name"=>  $dataEdit['dat']['name'],
            "last_name"=>  $dataEdit['dat']['last_name'],
            "address"=>  $dataEdit['dat']['address'],
            "mail"=>  $dataEdit['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('employee_phones', [
            "number" => $dataEdit['dat']['phone']
        ]);

    }

    /**
     * test que permite comprobar que no se actualiza la cedula de un empleado al ingersar una cedula de un empleado existente
     */
    public function test_employee_edit_identificationCard_repeat_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );

        $data2['dat'] = array(
            "cc" => "1005",
            "name" => "Antinio",
            "last_name" => "Osorio",
            "phone" => "30158",
            "address" => "calle #4",
            "mail" => "aos@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);
        $this->post(route('add_employee'), $data2);

        //datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "cc" => "1004", //cambio
            "name" => $data2['dat']['name'],
            "last_name" => $data2['dat']['last_name'],
            "phone" => "315756783", //cambio
            "address" => $data2['dat']['address'],
            "mail" => $data2['dat']['mail'],
        );

        //petecion de edicion
        $response = $this->post(route('edit_employee',$dataEdit));

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Ya existe un empleado con esta cédula');

        //comprobacion de dato agregado a tabla de empleados
        $this->assertDatabaseHas('employees', [
            "identification_card"=>  $data2['dat']['cc'],
            "name"=>  $data2['dat']['name'],
            "last_name"=>  $data2['dat']['last_name'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('employee_phones', [
            "number" => $data2['dat']['phone']
        ]);

    }

    /**
     * test que permite comprobar que no se actualiza en telefono de un empleado al ingresar un numero de telefono existente para otra empleado
     */
    public function test_employee_edit_phone_repeat_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );

        $data2['dat'] = array(
            "cc" => "1005",
            "name" => "Antinio",
            "last_name" => "Osorio",
            "phone" => "30158",
            "address" => "calle #4",
            "mail" => "aos@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);
        $this->post(route('add_employee'), $data2);

        //datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "cc" => $data2['dat']['cc'],
            "name" => $data2['dat']['name'],
            "last_name" => $data2['dat']['last_name'],
            "phone" => "31235", //cambio
            "address" => $data2['dat']['address'],
            "mail" => $data2['dat']['mail'],
        );

        //petecion de edicion
        $response = $this->post(route('edit_employee',$dataEdit));

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Un empleado ya tiene este número de teléfono');

        //comprobacion de dato agregado a tabla de empleados
        $this->assertDatabaseHas('employees', [
            "identification_card"=>  $data2['dat']['cc'],
            "name"=>  $data2['dat']['name'],
            "last_name"=>  $data2['dat']['last_name'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('employee_phones', [
            "number" => $data2['dat']['phone']
        ]);

    }

    /**
     * test que permite comprobar que no se actualiza en correo electronico de un empleado al ingresar un correo existente para otra empleado
    */
    public function test_employee_edit_email_repeat_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );

        $data2['dat'] = array(
            "cc" => "1005",
            "name" => "Antinio",
            "last_name" => "Osorio",
            "phone" => "30158",
            "address" => "calle #4",
            "mail" => "aos@mail.com",
        );
        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);
        $this->post(route('add_employee'), $data2);

        //datos a editar
        $dataEdit['dat'] = array(
            "id" => "2",
            "cc" => $data2['dat']['cc'],
            "name" => $data2['dat']['name'],
            "last_name" => $data2['dat']['last_name'],
            "phone" => $data2['dat']['last_name'],
            "address" => $data2['dat']['address'],
            "mail" => "afc@mail.com", //cambio
        );

        //petecion de edicion
        $response = $this->post(route('edit_employee',$dataEdit));

        //comprobacion de mensaje de respuesta de exito
        $response->assertSessionHas('fail_msg','Un empleado ya tiene este correo electrónico');

        //comprobacion de dato agregado a tabla de empleados
        $this->assertDatabaseHas('employees', [
            "identification_card"=>  $data2['dat']['cc'],
            "name"=>  $data2['dat']['name'],
            "last_name"=>  $data2['dat']['last_name'],
            "address"=>  $data2['dat']['address'],
            "mail"=>  $data2['dat']['mail'],
            "deleted_at"=> null
        ]);
        //comprobacion de dato agregado a la table telefonos empleados
        $this->assertDatabaseHas('employee_phones', [
            "number" => $data2['dat']['phone']
        ]);

    }

    /**
     * test que permite comprobar el cambio del campo delete_at de la tabla de empleados y telefonos de empleados
     */
    public function test_employee_delete_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );

        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        //petecion de eliminacion de los empleado
        $del=[
            "_token" => csrf_token(),
            "selected"=> array($data['dat']['cc'])
        ];

        $response = $this->delete('/empleados',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(1));

        //comprobacion parematro delete_at no es null
        $this->assertDatabaseMissing('employees', [
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['last_name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);

        //comprobacion parematro delete_at no es null
        $this->assertDatabaseMissing('employee_phones', [
            "number" => $data['dat']['phone'],
            "deleted_at"=> null
        ]);
    }

    /**
     * test que permite comprobar el cambio del campo delete_at de la tabla de empleados y telefonos de empleados
     */
    public function test_employee_delete_noData_test()
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

        //datos de entrada

        $data['dat'] = array(
            "cc" => "1004",
            "name" => "Alfredo",
            "last_name" => "Jimenez Murcia",
            "phone" => "31235",
            "address" => "calle #3",
            "mail" => "afc@mail.com",
        );

        //Acceso a la funcion que permite agregar un empleado
        $this->post(route('add_employee'), $data);

        //petecion de eliminacion de los empleado
        $del=[
            "_token" => csrf_token(),
            "selected"=> array()
        ];

        $response = $this->delete('/empleados',$del);

        //retorno exitoso de eliminacion
        $response->assertExactJson(array(0));

        //comprobacion parematro delete_at no es null
        $this->assertDatabaseHas('employees', [
            "identification_card"=>  $data['dat']['cc'],
            "name"=>  $data['dat']['name'],
            "last_name"=>  $data['dat']['last_name'],
            "address"=>  $data['dat']['address'],
            "mail"=>  $data['dat']['mail'],
            "deleted_at"=> null
        ]);

        //comprobacion parematro delete_at no es null
        $this->assertDatabaseHas('employee_phones', [
            "number" => $data['dat']['phone'],
            "deleted_at"=> null
        ]);
    }
}
