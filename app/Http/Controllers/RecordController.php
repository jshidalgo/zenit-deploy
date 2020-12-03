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
            $word = $dat['search'];
            $data['record'] = DB::table('records')
                    ->join('customers','customers.id','=','records.customer_id')
                    ->join('customer_phones','customer_phones.customer_id','=','customers.id')
                    ->join('vehicles','vehicles.id','=','records.vehicle_id')
                    ->join('employees','employees.id','=','records.employee_id')
                    ->where(function ($query){
                        $query->where('records.deleted_at','=',null);
                    })
                    ->where('customers.name','like','%'.$word.'%')
                    ->orWhere('customers.last_name','like','%'.$word.'%')
                    ->orWhere('customer_phones.number','like','%'.$word.'%')
                    ->orWhere('vehicles.license_plate','like','%'.$word.'%')
                    ->orWhere('employees.name','like','%'.$word.'%')
                    ->orWhere('employees.last_name','like','%'.$word.'%')
                    ->select(
                        'records.id as id','vehicles.license_plate as license_plate',
                        'customers.name as customer_name','customers.last_name as customer_last_name',
                        'customer_phones.number as customer_number', 'employees.name as employee_name',
                        'records.entry_date as entry_date','records.departure_date as departure_date',
                        'employees.last_name as employee_last_name')
                    ->get();


        } else {
            //marcar informacion de las entradas registradas
            $data['record'] = DB::table('records')
                ->join('employees', 'employees.id', '=', 'records.employee_id')
                ->join('customers', 'customers.id', '=', 'records.customer_id')
                ->join('vehicles', 'vehicles.id', '=', 'records.vehicle_id')
                ->join('customer_phones', 'customer_phones.customer_id', '=', 'customers.id')
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
        }
        //informacion de los clientes
        $data['customers'] = DB::table('customer_phones')
            ->join('customers', 'customers.id', '=', 'customer_phones.customer_id')
            ->select('customers.*', 'customer_phones.*')
            ->get();

        $arreglo_customer = array();
        foreach ($data['customers'] as $customer){
            $arreglo_customer[$customer->id] = $customer->identification_card ." - ". $customer->name ." ". $customer->last_name;
        }
        $data['misClientes']=$arreglo_customer;


        //informacion de los vehiculos
        $data['vehicles'] = DB::table('vehicles')->whereNull('deleted_at')->get();

        $arreglo_vehicle = array();
        foreach ($data['vehicles'] as $vehicle){
            $arreglo_vehicle[$vehicle->id] = $vehicle->license_plate ." - ". $vehicle->name;
        }
        $data['misVehiculos']=$arreglo_vehicle;

        //empleados
        $data['employees'] = DB::table('employees')->whereNull('deleted_at')->get();
        $arreglo_employee = array();
        foreach ($data['employees'] as $employee){
            $arreglo_employee[$employee->id] = $employee->identification_card ." - ". $employee->name ." ". $employee->last_name;
        }
        $data['misEmpleados']=$arreglo_employee;

        //productos
        $data['products'] = DB::table('products')->whereNull('deleted_at')->get();
        return view('record', $data);
    }

    /**
     * Funcion que crea un registro
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_record(Request $request){
        $dat = $request->get('dat');

        $customer = Customer::find($dat['id_customer']);
        $vehicle = Vehicle::find($dat['id_vehicle']);
        $employee = Employee::find($dat['id_employee']);

        $record = new Record();
        $record->entry_date = $dat['entry_date'];
        $record->mileage = $dat['mileage'];
        $record->departure_date = $dat['out_date'];
        $record->customer_id = $customer->id;
        $record->employee_id = $employee->id;
        $record->vehicle_id = $vehicle->id;
        $record->save();

        //Carga los productos utilizados y los descuenta del inventario
        $products_used = json_decode($dat['products_used'],true);
        foreach ($products_used as $aux){
            $product = Product::find($aux[0]);
            $product->units_available-=$aux[1];
            $product->save();

            $spare = new Spare();
            $spare->quantity = $aux[1];
            $spare->price_sale = $product->sale_price;
            $spare->product_id = $product->id;
            $spare->record_id = $record->id;
            $spare->save();
        }

        $services_used = json_decode($dat['services_finished'],true);
        foreach ($services_used as $aux){
            $service = new Service();
            $service->name = $aux[0];
            $service->description = $aux[1];
            $service->price = doubleval(substr($aux[2],1));
            $service->save();

            $labor = new Labor();
            $labor->record_id = $record->id;
            $labor->service_id = $service->id;
            $labor->save();
        }
        $request->session()->flash('check_msg','El registro se ha completado con éxito');
        return redirect()->route('view_record');
    }

    /**
     * Funcion que obtiene un cluente dado su ID
     * @param Request $request
     * @return mixed
     */
    public function get_customer_id(Request $request){
        $customer = Customer::find($request->id);

        return $customer;
    }

    /**
     * Funcion que obtiene un vehiculo dado su ID
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function get_vehicle_id(Request $request){
        $vehicle = DB::table('vehicles')
            ->join('brands','vehicles.brand_id','=','brands.id')
            ->where('vehicles.id','=',$request->id)
            ->select('vehicles.*','brands.*','vehicles.name as vehicle_reference','brands.name as brand')
            ->get();

        return $vehicle;
    }

    /**
     * Funcion que obtiene un empleado dado su ID
     * @param Request $request
     * @return mixed
     */
    public function get_empleado_id(Request $request){
        $employee = Employee::find($request->id);
        return $employee;
    }

    /**
     * Funcion que obtiene un producto dado su ID
     * @param Request $request
     * @return mixed
     */
    public function get_product_id(Request $request){
        $product = Product::find($request->id);
        return $product;
    }

    public function get_record($id){
        $result = array();

        $record = Record::find($id);
        array_push($result,$record);

        $customer = DB::table('records')->join('customers','customer_id','=','customers.id')->first();
        array_push($result,$customer);

        $vehicle = DB::table('records')->join('vehicles','vehicle_id','=','vehicles.id')->first();
        array_push($result,$vehicle);

        $employee = DB::table('records')->join('employees','employee_id','=','employees.id')->first();
        array_push($result,$employee);

        $products = DB::table('records')
            ->join('spares','records.id','=','spares.record_id')
            ->join('products','spares.product_id','=','products.id')
            ->where('records.id','=',$id)
            ->select('products.units_available as unit_available','spares.quantity as product_quantity','products.id as product_id','products.name as product_name','products.description as product_description', 'products.sale_price as product_price')
            ->get();
        array_push($result,$products);

        $services = DB::table('records')
                    ->join('labors','records.id','=','labors.record_id')
                    ->join('services','services.id','=','labors.service_id')
                    ->where('records.id','=',$id)
                    ->select('services.id as service_id','services.name as service_name','services.description as service_description','services.price as service_price')
                    ->get();

        array_push($result,$services);

        return $result;
    }

    /**
     * Funcion que obtiene todos los repuestos asociados a un registro
     * @param Request $request
     * @return array
     */
    public function get_spare_record_id(Request $request){
        $result = array();

        $resultA = DB::table('records')
                ->join('spares','records.id','=','spares.record_id')
                ->join('products','spares.product_id','=','products.id')
                ->where('records.id','=',$request->id_record)
                ->where('products.id','=',$request->id_product)
                ->select('products.id as product_id','spares.quantity as quantity_used','products.units_available as quantity_inventory')
                ->get();
        $resultB = Product::find($request->id_product);

        array_push($result,$resultA);
        array_push($result,$resultB);

        return $result;
    }

    public function edit_record(Request $request){
        $dat = $request->get('dat');

        $record = Record::find($dat['id_record']);
        $record->entry_date = $dat['entry_date_edit'];
        $record->mileage = $dat['mileage_edit'];
        $record->departure_date = $dat['out_date_edit'];
        $record->customer_id = $dat['id_customer'];
        $record->employee_id = $dat['id_employee'];
        $record->vehicle_id = $dat['id_vehicle'];
        $record->save();

        $products_used = json_decode($dat['products_used'],true);

        foreach ($products_used as $aux){
            //aux[1] cuantas veces se utilizo

            $product = Product::find($aux[0]);
            $spare_aux = DB::table('spares')
                ->join('products','spares.product_id','=','products.id')
                ->where('spares.record_id','=',$record->id)
                ->where('spares.product_id','=',$product->id)
                ->select('spares.id as spare_id', 'spares.quantity as spare_quantity')
                ->first();
            //Verificamos si existe el repuesto asociado al registro y el producto.
            //Si no existe nos toca crear un nuevo repuesto realizando la correspondiente asociacion
            if(!empty($spare_aux)){
                $spare = Spare::find($spare_aux->spare_id);
                $product->units_available = $product->units_available + $spare->quantity - intval($aux[1]);
                $product->save();
                $spare->quantity = $aux[1];
                $spare->save();
            }else{
                $spare = new Spare();
                $spare->quantity = $aux[1];
                $spare->price_sale = $product->sale_price;
                $spare->product_id = $product->id;
                $spare->record_id = $record->id;
                $spare->save();
                $product->units_available-= $aux[1];
                $product->save();
            }
        }
        $services_used = json_decode($dat['services_finished'],true);

        foreach ($services_used as $aux){
            //Si el ID del servicio empieza con p, quiere decir que toca crear un trabajo nuevo
            if($aux[0][0] != "p"){
                $service = Service::find($aux[0]);
                $service->name = $aux[1];
                $service->description = $aux[2];
                $service->price = doubleval(substr($aux[3],1));
                $service->save();
            }else{
                $service = new Service();
                $service->name = $aux[1];
                $service->description = $aux[2];
                $service->price = doubleval(substr($aux[3],1));
                $service->save();

                $labor = new Labor();
                $labor->record_id = $record->id;
                $labor->service_id = $service->id;
                $labor->save();
            }
        }
        return redirect()->route('view_record');
    }

    /**
     * Función que elimina un registro dado su ID
     * @param Request $request
     * @return int
     */
    function delete_record(Request $request){
        if(sizeof($request->selected)>0){
            foreach ($request->selected as $aux){
                $record = Record::find($aux);
                if(!empty($record)){
                    $labors = Labor::where('labors.record_id','=',$record->id)->get();
                    foreach ($labors as $labor){
                        $labor->delete();
                    }
                    $spares = Spare::where('spares.record_id','=',$record->id)->get();
                    foreach ($spares as $spare){
                        $spare->delete();
                    }
                    $record->delete();
                }
            }
            return 1;
        }
        return 0;
    }

}
