<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class EmployeeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * test que permite comproblar el funcionamiento basico de la vista de empleados
     *
     * @return void
     */
    public function test_employee_page_test()
    {
        $response = $this->get('/empleados');

        $response->assertStatus(200);
    }
    /**
     * test que permite comproblar el la vista por parte del usuario
     *
     * @return void
     */
    public function test_employee_view_test()
    {
        $employees['employees'] = [];
        $response = $this->view('employee', $employees);

        $response->assertSeeInOrder(['Gestión de empleados','Agrega, actualiza o elimina registros de empleados',
            'ID','Cédula','Nombre','Apellidos','Télefono','Dirección','Correo']);
    }

    /**
     * test que permite agregar un empleado
     *
     * @return void
     */
    public function test_employee_add_test()
    {
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
}
