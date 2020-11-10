<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VehicleController extends Controller
{
    /**
     * Función que crea un nuevo vehiculo
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_vehicle(Request $request)
    {
        //informacion recibida
        $dat = $request->get('dat');
        $plate = strtoupper($dat['plate']);
        $brandName = strtoupper($dat['brand']);
        $exist_brand = Brand::where('name', '=', $brandName)->get();
        $exist_vehicle = Vehicle::where('license_plate', '=', $plate)->get();

        //verificar que el vehiculo no exista
        if (isset($exist_vehicle) &&  $exist_vehicle->count() == 0) {
            //determinar la existencia de una marca
            //si no existe la crea
            if (isset($exist_brand) &&  $exist_brand->count() == 0) {
                $brand = new Brand();
                $brand->name = $brandName;
                if ($brand->save()) {
                    $exist_brand = Brand::where('name', '=', $brandName)->get();
                } else {
                    $request->session()->flash('fail_msg', 'Error en el registro de la marca');
                }
            }
            //creando vehiculo
            $vehicle = new Vehicle();
            $vehicle->license_plate = $plate;
            $vehicle->color = $dat['color'];
            $vehicle->cylinder_capacity = $dat['cylinder'];
            $vehicle->name = $dat['name'];
            $vehicle->model = $dat['model'];
            $vehicle->brand_id = $exist_brand[0]->id;
            if ($vehicle->save()) {
                $request->session()->flash('check_msg', 'El vehículo se registro con éxito');
            } else {
                $request->session()->flash('fail_msg', 'Erro en el registro del vehículo');
            }
        } else {
            $request->session()->flash('fail_msg', 'Este vehículo ya se encuentra registrado');
        }
        return redirect()->route('view_vehicle');
    }
    /**
     * Función que busca vehiculos a partir de una palabra
     *
     * @param Request $request
     * @param $word
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_view_vehicle(Request $request)
    {
        $dat = $request->get('dat');
        $vehicles['fail_msg'] = Session::get('fail_msg');
        $vehicles['check_msg'] = Session::get('check_msg');
        if (isset($dat) && !empty($dat)) {
            $word = $dat['search'];
            $vehicles['vehicles'] = DB::table('vehicles')
                ->join('brands','vehicles.brand_id','=','brands.id')
                ->where('license_plate','like','%'.$word.'%')
                ->select('vehicles.*', 'brands.name as brand')
                ->get();
        } else {
            //Vehiculos con sus correspondientes marcas
            $vehicles['vehicles'] = DB::table('vehicles')
                ->join('brands', 'brands.id', '=', 'vehicles.brand_id')
                ->where('vehicles.deleted_at','=',null)
                ->select('vehicles.*', 'brands.name as brand')
                ->get();
        }
        return view('vehicle',$vehicles);
    }
    /**
     * Función que obtiene un vehiculo mediante su ID
     * @param $cc
     * @return array
     */
    public function get_vehicle($id){

        $result['result'] = DB::table('vehicles')
            ->join('brands','vehicles.brand_id','=','brands.id')
            ->where('vehicles.id','=',$id)
            ->select('vehicles.*', 'brands.name as brand','vehicles.id as vehicle_id')
            ->first();

        return $result;
    }

    /**
     * Función que edita un vehiculo
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit_vehicle(Request $request){
        $dat = $request->get('dat');

        $vehicle = DB::table('vehicles')
            ->join('brands','vehicles.brand_id','=','brands.id')
            ->where('vehicles.id','=',$dat['id'])
            ->select('vehicles.*', 'brands.name as brand','vehicles.id as vehicle_id')
            ->first();

        $exist_vehicle = Vehicle::where('license_plate','=',$dat['plate'])->get();
        $exist_brand = Brand::where('name','=',$dat['brand'])->get();

        $flag_vehicle = true;
        if($exist_vehicle->count() == 1){
            if($exist_vehicle[0]->id != $vehicle->vehicle_id ){
                $flag_vehicle=false;
                $request->session()->flash('fail_msg','Ya existe un vehiculo con esta placa');
            }
        }

        $flag_brand = false;
        if($exist_brand->count() == 0){
            $flag_brand = true;
        }

        if($flag_vehicle){
            $exist_vehicle = Vehicle::find($dat['id']);
            $exist_vehicle->license_plate = $dat['plate'];
            $exist_vehicle->color = $dat['color'];
            $exist_vehicle->cylinder_capacity = $dat['cylinder'];
            $exist_vehicle->model = $dat['model'];
            $exist_vehicle->name = $dat['name'];
            if($flag_brand){
                $new_brand = new Brand();
                $new_brand->name = $dat['brand'];
                $new_brand->save();
                $exist_vehicle->brand_id = $new_brand->id;
            }
            $exist_vehicle->save();

            $request->session()->flash('check_msg','Se ha modificado  los datos del vehículo');
        }
        return redirect()->route('view_vehicle');
    }

    /**
     * Función que elimina los vehiculos seleccionados
     * @param Request $request
     * @return int
     */
    public function delete_vehicle(Request $request){
        if(sizeof($request->selected) > 0){
            foreach ($request->selected as $aux){
                $customer = Vehicle::find($aux);
                if(!empty($customer)){
                    $customer->delete();
                }
            }
            return 1;
        }
        return 0;
    }
}
