<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Labor;
use App\Models\Product;
use App\Models\Record;
use App\Models\Service;
use App\Models\Spare;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RecordController extends Controller
{
    /**
     * Funcion que se encarga de registrar una nueva entrada
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_record(Request $request)
    {

        //datos recibidos
        $dat = $request->get('dat');
        $products = $request->get('product');
        $services = $request->get('service');

        $exist_epmloyee = Employee::where('id', '=', $dat['id_employee'])->get();
        $exist_customer = Customer::where('id', '=', $dat['id_customer'])->get();
        $exist_vehicle = Vehicle::where('id', '=', $dat['id_vehicle'])->get();
        //verificando que el empleado exista
        if (!(isset($exist_epmloyee) &&  $exist_epmloyee->count() == 0)) {
            //verificando que el cliente exista
            if (!(isset($exist_customer) &&  $exist_customer->count() == 0)) {
                //verificando la existencia del vehiculo
                if (!(isset($exist_vehicle) &&  $exist_vehicle->count() == 0)) {
                    //verifica la existencia de los productos
                    if ($this->verificarProductos($products)) {

                        //crea el seguimiento
                        $record = new Record();
                        $record->entry_date = $dat['entry_date'];
                        $record->mileage = $dat['mileage'];
                        $record->departure_date = $dat['departure_date'];
                        $record->customer_id = $exist_customer[0]->id;
                        $record->employee_id = $exist_epmloyee[0]->id;
                        $record->vehicle_id = $exist_vehicle[0]->id;
                        //almacena
                        $record->save();

                        //buscar productos y almacena los repuestos
                        //variable que permite conocer que dato del producto
                        //1= id del producto
                        //3= cantidad de producto usado
                        $auxProductos = 1;
                        //instancia de servicio
                        $units_available = 0;
                        $id_product = 0;
                        $spare = new Spare();
                        foreach ($products as $key => $value) {
                            if ($auxProductos == 1) {
                                //se busca el producto
                                $id_product = $value;
                                $product = DB::table('products')->where('id', '=', $id_product)->get();

                                $units_available = $product[0]->units_available;

                                $auxProductos += 1;
                            } else if ($auxProductos == 3) {
                                $units_available -= $value;
                                //actualiza el valor de las unidades disponibles
                                $product = DB::table('products')->where('id', $id_product)->update(['units_available' => $units_available]);
                                var_dump($product);
                                //crea repuesto
                                $spare = new Spare();
                                $spare->quantity = $value;

                                $auxProductos += 1;
                            }else if ($auxProductos == 4) {

                                $spare->price_sale = $value;
                                $spare->product_id = $id_product;
                                $spare->record_id = $record->id;
                                //almacena
                                $spare->save();

                                $auxProductos = 1;
                            }
                            else {
                                $auxProductos += 1;
                            }
                        }

                        //alamcena los servicios y los trabajos
                        $servicios = $this->obtenerServicios($services);
                        foreach ($servicios as $key => $service) {
                            //almacena
                            $service->save();
                            //crea los trabajos
                            $labor = new Labor();
                            $labor->record_id = $record->id;
                            $labor->service_id = $service->id;
                            $labor->save();
                        }
                        $request->session()->flash('check_msg','El servicio se registro con éxito');
                    }else{
                        $request->session()->flash('fail_msg', 'Error, intente nuevamente, producto no registrado');
                    }
                } else {
                    $request->session()->flash('fail_msg', 'Error, intente nuevamente, vehículo no registrado');
                }
            } else {
                $request->session()->flash('fail_msg', 'Error, intente nuevamente, cliente no registrado');
            }
        } else {
            $request->session()->flash('fail_msg', 'Error, intente nuevamente, empleado no registrado');
        }
        return redirect()->route('view_record');
    }

    /**
     * Función que permite verificar que los productos seleccionados se encuentre registrados
     * @param array $products
     */
    public function verificarProductos(array $products)
    {
        //buscar productos
        //variable que permite conocer que dato del producto
        //1= id del producto
        //3= cantidad de producto usado
        $auxProductos = 1;
        //contador de produtos existentes
        $count = 0;
        foreach ($products as $key => $value) {

            if ($auxProductos == 1) {

                //se busca el producto
                $product = DB::table('products')->where('id', '=', $value)->get();
                if (!(isset($product) &&  $product->count() == 0)) {
                    $count += 1;
                }
                $auxProductos+=1;
            } else if ($auxProductos == 4) {
                $auxProductos = 1;
            } else {
                $auxProductos += 1;
            }
        }

        if ($count == (count($products)/4)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Función que permite extraer los servicios enviados
     * @param array $services
     */
    public function obtenerServicios(array $services)
    {
        $servicios = array();
        //variable que permite conocer que dato del servicio
        //1= nombre del servicio
        //2= descripcion del servicio
        //3= precio del servicio
        $auxServicios = 1;
        //instancia de servicio
        $service = new Service();
        foreach ($services as $key => $value) {
            if ($auxServicios == 1) {
                //creando la instacia del servicio
                $service = new Service();
                $service->name = $value;
                //incremento de aux
                $auxServicios += 1;
            } else if ($auxServicios == 2) {
                //descripcion
                $service->description = $value;
                //incremento de aux
                $auxServicios += 1;
            } else if ($auxServicios == 3) {
                //precio
                $service->price = $value;
                $servicios[] = $service;
                $auxServicios = 1;
            }
        }
        return $servicios;
    }
    /**
     * Función que busca servicios a partir de una palabra
     *
     * @param Request $request
     * @param $word
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_view_record(Request $request)
    {
        $dat = $request->get('dat');
        $data['fail_msg'] = Session::get('fail_msg');
        $data['check_msg'] = Session::get('check_msg');
        if (isset($dat) && !empty($dat)) {
            // $word = $dat['search'];
            // $record['record'] = DB::table('record')
            //     ->where('identification_card','like','%'.$word.'%')
            //     ->orWhere('name','like','%'.$word.'%')
            //     ->orWhere('last_name','like','%'.$word.'%')
            //     ->get();

        } else {
            //marcar informacion de las entradas registradas
            $data['record'] = DB::table('records')
                ->join('employees', 'employees.id', '=', 'records.employee_id')
                ->join('customers', 'customers.id', '=', 'records.customer_id')
                ->join('vehicles', 'vehicles.id', '=', 'records.vehicle_id')
                ->join('customer_phones', 'customer_phones.customer_id', '=', 'customers.id')
                // ->join('labors', 'labors.record_id', '=','records.id')
                // ->join('services','services.id','=','labors.service_id')
                ->select(
                    "customers.id as customer_id",
                    'customers.identification_card as customer_card',
                    'customers.name as customer_name',
                    'customers.last_name as customer_last_name',
                    'customer_phones.number as customer_number',
                    'vehicles.license_plate',
                    'vehicles.id as vehicle_id',
                    'employees.name as employee_name','employees.last_name as employee_last_name',
                    //'services.name as service_name',
                    'records.*'
                )
                ->where('records.deleted_at','=',null)
                ->get();
            //var_dump($customer);

            //informacion de los clientes
            $data['customer'] = DB::table('customer_phones')
                ->join('customers', 'customers.id', '=', 'customer_phones.customer_id')
                ->select('customers.*', 'customer_phones.*')
                ->get();
            //informacion de los vehiculos
            $data['vehicle'] = DB::table('vehicles')->whereNull('deleted_at')->get();
            //empleados
            $data['employee'] = DB::table('employees')->whereNull('deleted_at')->get();
            //productos
            $data['product'] = DB::table('products')->whereNull('deleted_at')->get();

            // foreach ($data as $key => $value) {
            //     echo $key;
            //     echo '=';
            //     echo $value;
            // }
        }
        return view('record', $data);
    }

    /**
     * Función que obtiene un record mediante su id
     * @param $cc
     * @return array
     */
    public function get_record($cc){
        echo "get";
        // $employee = Employee::where('identification_card','=',$cc)->first();
        // $phone = Employee_phone::where('employee_id','=',$employee->id)->first();
        // $result = [$employee, $phone];

        // return $result;
    }

    /**
     * Función que se encarga de modificar un record
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit_record(Request $request){
        echo "edit";
        // $dat = $request->get('dat');

        // $employee = DB::table('employees')
        //     ->join('employee_phones','employees.id','=','employee_phones.employee_id')
        //     ->where('employees.id','=',$dat['id'])
        //     ->get()->first();

        // $exist_employee = Employee::where('identification_card','=',$dat['cc'])->get();
        // $exist_phone = Employee_phone::where('number','=',$dat['phone'])->get();
        // $exist_mail = Employee::where('mail','=',$dat['mail'])->get();

        // $flag_cc = true;

        // //Verifica si la cédula ya se encuentra registrada
        // if($exist_employee->count() == 1) {
        //     if($exist_employee[0]->id != $employee->id){
        //         $flag_cc=false;
        //         $request->session()->flash('fail_msg','Ya existe un empleado con esta cédula');
        //     }
        // }

        // $flag_phone = true;
        // // Verifica si el telefono ingresado ya se encuentra registrado
        // if($exist_employee->count() >= 1){
        //     foreach ($exist_phone as $aux){
        //         if($aux->employee_id != $employee->id){
        //             $flag_phone=false;
        //             $request->session()->flash('fail_msg','Un empleado ya tiene este número de teléfono');
        //         }
        //     }

        // }

        // $flag_mail=true;
        // //Verifica si el mail ingresado ya se encuentra registrado
        // if($exist_mail->count() == 1){
        //     if($exist_mail[0]->id != $employee->id){
        //         $flag_mail=false;
        //         $request->session()->flash('fail_msg','Un empleado ya tiene este correo electrónico');

        //     }
        // }

        // if($flag_cc && $flag_phone && $flag_mail){
        //     $exist_employee = Employee::find($dat['id']);
        //     $exist_employee->identification_card = $dat['cc'];
        //     $exist_employee->name = $dat['name'];
        //     $exist_employee->last_name = $dat['last_name'];
        //     $exist_employee->address = $dat['address'];
        //     $exist_employee->mail = $dat['mail'];
        //     $exist_employee->save();

        //     $exist_phone = Employee_phone::where('employee_id','=',$dat['id'])->first();
        //     $exist_phone->number = $dat['phone'];
        //     $exist_phone->save();
        //     $request->session()->flash('check_msg','Se actualizaron los datos del empleado con éxito');
        // }
        // return redirect()->route('view_employee');
    }

    /**
     * Función que elimina empleados
     * @param Request $request
     * @return int
     */
    public function delete_record(Request $request){

        if(sizeof($request->selected) > 0){
            foreach ($request->selected as $aux){
                $record = Record::find($aux);
                if(!empty($record)){
                    $labors = Labor::where('record_id','=',$record->id)->get();
                    foreach ($labors as $labor){
                        $labor->delete();
                    }
                    $record->delete();
                }
            }
            return 1;
        }
        return 0;
    }
}
