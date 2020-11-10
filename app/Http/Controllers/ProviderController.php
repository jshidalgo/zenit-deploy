<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Provider;
use App\Models\Provider_phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProviderController extends Controller
{
    /**
     * Funcion que se encarga de registrar un nuevo proveedor
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_provider(Request $request)
    {
        //informacion recibida
        $dat = $request->get('dat');
        //proveedor
        $exist_provider = Provider::where('nit','=',$dat['nit'])->get();
        $exist_phone = Provider_phone::where('number','=',$dat['phone'])->get();
        //coorreo en miniculas
        $mail =mb_strtolower($dat['mail']);
        $exist_mail = Provider::where('mail','=',$mail)->get();

        //Verifica que el proveedor ya este registrado
        if(isset($exist_provider) &&  $exist_provider->count() == 0){

            //Verifica si el telefono ingresado ya esta registrado
            if(isset($exist_phone) &&  $exist_phone->count() == 0){

                //Verifica si el correo ya esta registrado
                if(isset($exist_mail) && $exist_mail->count() == 0){
                    //almacena al proveedor
                    $provider = new Provider();
                    $provider->nit = $dat['nit'];
                    $provider->name = $dat['name'];
                    $provider->mail = $mail;
                    $provider->address = $dat['address'];
                    $provider->city_id = $this->buscarUbicacion($dat['city'],$dat['departament'],$dat['country']);
                    $provider->save();
                    //almacena el telefono
                    $phone = new Provider_phone();
                    $phone->number = $dat['phone'];
                    $phone->provider_id = $provider->id;
                    $phone->save();
                    $request->session()->flash('check_msg','El proveedor se registro con éxito');
                }
                else {
                    $request->session()->flash('fail_msg', 'Este correo ya se encuentra asociado a un proveedor');
                }
            }
            else {
                $request->session()->flash('fail_msg', 'Este número de télefono ya se encuentra registrado');
            }
        }else {
            $request->session()->flash('fail_msg', 'Este proveedor ya se encuentra registrado');
        }

        return redirect()->route('view_provider');
    }
    /**
     * funcion que permite buscar y crear de ser necesario una ubiacion
     * @param - nombre de los lugares
     */
    public function buscarUbicacion($ciudad,$departamento,$pais){
        //país
        $queryCountry = DB::table('countries')->where("code", "=", $pais)->get();
        if (isset($queryCountry) &&  $queryCountry->count() == 0) {
            echo "no existe el pais";
            //no existe el pais, se crea
            $country = new Country();
            $country->code = $pais;
            $country->name = $pais;
            $country->save();
            $queryCountry = DB::table('countries')->where("code", "=", $pais)->get();
        }
        //departamento
        $queryDepartament = DB::table('departments')->where("code", "=", $departamento)->get();
        if (isset($queryDepartament) &&  $queryDepartament->count() == 0) {
            $departmet = new Department();
            $departmet->code = $departamento;
            $departmet->name = $departamento;
            $departmet->country_id = $queryCountry[0]->id;
            $departmet->save();
            $queryDepartament = DB::table('departments')->where("code", "=", $departamento)->get();
        }

        //ciudad
        $queryCity = DB::table('cities')->where("code", "=", $ciudad)->get();
        if (isset($queryCity) &&  $queryCity->count() == 0) {
            $city = new City();
            $city->name = $ciudad;
            $city->code = $ciudad;
            $city->department_id = $queryDepartament[0]->id;
            $city->save();
            $queryCity = DB::table('cities')->where("code", "=", $ciudad)->get();
        }
        return $queryCity[0]->id;
    }

    /**
     * Función que busca un provedor a partir de una palabra
     * @param Request $request
     * @param $word
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_view_provider(Request $request)
    {
        $dat = $request->get('dat');
        $provider['fail_msg'] = Session::get('fail_msg');
        $provider['check_msg'] = Session::get('check_msg');
        if (isset($dat) && !empty($dat)) {
            $word = $dat['search'];
            $provider['provider'] = DB::table('countries')
                ->join('departments', 'departments.country_id', '=', 'countries.id')
                ->join('cities', 'cities.department_id', '=', 'departments.id')
                ->join('providers','providers.city_id','=','cities.id')
                ->join('provider_phones', 'provider_phones.provider_id', '=', 'providers.id')
                ->select('cities.name as city', 'cities.id as city_id', 'departments.name as departament', 'countries.name as country', 'providers.*','provider_phones.number')
                ->where('providers.nit','like','%'.$word.'%')
                ->orWhere('providers.name','like','%'.$word.'%')
                ->orWhere('providers.mail','like','%'.$word.'%')
                ->orWhere('providers.address','like','%'.$word.'%')
                ->orWhere('cities.name','like','%'.$word.'%')
                ->orWhere('countries.name','like','%'.$word.'%')
                ->orWhere('departments.name','like','%'.$word.'%')
                ->get();

        } else {
            //datos de los proveedores con su respectivo numero y ubicacion
            $provider['provider'] = DB::table('countries')
                ->join('departments', 'departments.country_id', '=', 'countries.id')
                ->join('cities', 'cities.department_id', '=', 'departments.id')
                ->join('providers','providers.city_id','=','cities.id')
                ->join('provider_phones', 'provider_phones.provider_id', '=', 'providers.id')
                ->select('cities.name as city', 'cities.id as city_id', 'departments.name as departament', 'countries.name as country', 'providers.*','provider_phones.number')
                ->where('providers.deleted_at','=',null)
                ->get();
            //var_dump($provider);
            // foreach ($provider as $key => $value) {
            //     echo $key;
            //     echo '=';
            //     echo $value;
            // }
        }
        return view("provider", $provider);
    }

    /**
     * Función que obtiene un proveedor mediante su id
     * @param $id
     * @return array
     */
    public function get_provider($id){

        $provider = DB::table('countries')
            ->join('departments', 'departments.country_id', '=', 'countries.id')
            ->join('cities', 'cities.department_id', '=', 'departments.id')
            ->join('providers','providers.city_id','=','cities.id')
            ->join('provider_phones', 'provider_phones.provider_id', '=', 'providers.id')
            ->select('cities.name as city', 'cities.id as city_id', 'departments.name as departament', 'countries.name as country', 'providers.*','provider_phones.number')
            ->where('providers.deleted_at','=',null)
            ->where('providers.id','=',$id)
            ->get();

        return $provider;
    }

    /**
     * Función que edita un proveedor
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit_provider(Request $request){
        $dat = $request->get('dat');

        $provider = DB::table('providers')
            ->join('provider_phones','providers.id','=','provider_phones.provider_id')
            ->where('providers.id','=',$dat['id'])
            ->get()->first();

        $exist_provider = Provider::where('nit','=',$dat['nit'])->get();
        $exist_phone = Provider_phone::where('number','=',$dat['phone'])->get();
        $exist_mail = Provider::where('mail','=',$dat['mail'])->get();

        $flag_nit = true;

        //Verifica si la el nit se encuentra registrado
        if($exist_provider->count() == 1) {
            if($exist_provider[0]->id != $provider->id){
                $flag_nit=false;
                $request->session()->flash('fail_msg','Ya existe un proveedor con este nit');
            }
        }

        $flag_phone = true;
        // Verifica si el telefono ingresado ya se encuentra registrado
        if($exist_provider->count() >= 1){
            foreach ($exist_phone as $aux){
                if($aux->provider_id != $provider->id){
                    $flag_phone=false;
                    $request->session()->flash('fail_msg','Un proveedor ya tiene este número de teléfono');
                }
            }

        }

        $flag_mail=true;
        //Verifica si el mail ingresado ya se encuentra registrado
        if($exist_mail->count() == 1){
            if($exist_mail[0]->id != $provider->id){
                $flag_mail=false;
                $request->session()->flash('fail_msg','Un proveedor ya tiene este correo electrónico');

            }
        }

        if($flag_nit && $flag_phone && $flag_mail){
            $exist_provider = Provider::find($dat['id']);
            $exist_provider->nit = $dat['nit'];
            $exist_provider->name = $dat['name'];
            $exist_provider->mail = $dat['mail'];
            $exist_provider->address = $dat['address'];
            //se busca la ciudad, de no existir se crea
            $exist_provider->city_id = $this->buscarUbicacion($dat['city'],$dat['departament'],$dat['country']);
            $exist_provider->save();

            var_dump($dat['id']);
            $exist_phone = Provider_phone::where('provider_id','=',$dat['id'])->first();
            var_dump($exist_phone);
            $exist_phone->number = $dat['phone'];
            $exist_phone->save();
            $request->session()->flash('check_msg','Se actualizaron los datos del proveedor con éxito');
        }
        return redirect()->route('view_provider');

    }

    /**
     * Función que elimina los clientes seleccionados
     * @param Request $request
     * @return int
     */
    public function delete_provider(Request $request){

        if(sizeof($request->selected) > 0){
            foreach ($request->selected as $aux){
                $provider = Provider::find($aux);
                if(!empty($provider)){
                    $phones = Provider_phone::where('provider_id','=',$provider->id)->get();
                    foreach ($phones as $phone){
                        $phone->delete();
                    }
                    $provider->delete();
                }
            }
            return 1;
        }
        return 0;
    }
}
