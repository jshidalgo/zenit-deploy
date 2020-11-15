<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Provider;
use App\Models\Provider_phone;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Class ProviderController
 * @package App\Http\Controllers
 */
class ProviderController extends Controller
{
    //constantes de bd
    const DEPARTMENTS_COUNTRY_ID = 'departments.country_id';
    const COUNTRIES_ID = 'countries.id';
    const CITIES_DEPARTMENT_ID = 'cities.department_id';
    const DEPARTMENTS_ID = 'departments.id';
    const PROVIDERS_CITY_ID = 'providers.city_id';
    const CITIES_ID = 'cities.id';
    const PROVIDER_PHONES_PROVIDER_ID = 'provider_phones.provider_id';
    const PROVIDERS_ID = 'providers.id';
    const CITIES_NAME_AS_CITY = 'cities.name as city';
    const CITIES_ID_AS_CITY_ID = 'cities.id as city_id';
    const DEPARTMENTS_NAME_AS_DEPARTMENT = 'departments.name as departament';
    const COUNTRIES_NAME_AS_COUNTRY = 'countries.name as country';
    const PROVIDER_PHONE_NUMBER = 'provider_phones.number';

    /**
     * Funcion que se encarga de registrar un nuevo proveedor
     * @param Request $request
     * @return RedirectResponse
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
     * @param $ciudad
     * @param $departamento
     * @param $pais
     * @return mixed - identificador de la ciudad
     */
    public function buscarUbicacion($ciudad,$departamento,$pais){
        //país
        $queryCountry = DB::table('countries')->where("code", "=", $pais)->get();
        if (isset($queryCountry) &&  $queryCountry->count() == 0) {
            //echo "no existe el pais";
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
     * @return Factory|View
     */
    public function show_view_provider(Request $request)
    {
        $dat = $request->get('dat');
        $provider['fail_msg'] = Session::get('fail_msg');
        $provider['check_msg'] = Session::get('check_msg');
        if (isset($dat) && !empty($dat)) {
            $word = $dat['search'];
            $provider['provider'] = DB::table('countries')
                ->join('departments', ProviderController::DEPARTMENTS_COUNTRY_ID, '=', ProviderController::COUNTRIES_ID)
                ->join('cities', ProviderController::CITIES_DEPARTMENT_ID, '=', ProviderController::DEPARTMENTS_ID)
                ->join('providers',ProviderController::PROVIDERS_CITY_ID,'=',ProviderController::CITIES_ID)
                ->join('provider_phones', ProviderController::PROVIDER_PHONES_PROVIDER_ID, '=', ProviderController::PROVIDERS_ID)
                ->select(ProviderController::CITIES_NAME_AS_CITY, ProviderController::CITIES_ID_AS_CITY_ID, ProviderController::DEPARTMENTS_NAME_AS_DEPARTMENT, ProviderController::COUNTRIES_NAME_AS_COUNTRY, 'providers.*',ProviderController::PROVIDER_PHONE_NUMBER)
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
                ->join('departments', ProviderController::DEPARTMENTS_COUNTRY_ID, '=', ProviderController::COUNTRIES_ID)
                ->join('cities', ProviderController::CITIES_DEPARTMENT_ID, '=', ProviderController::DEPARTMENTS_ID)
                ->join('providers',ProviderController::PROVIDERS_CITY_ID,'=',ProviderController::CITIES_ID)
                ->join('provider_phones', ProviderController::PROVIDER_PHONES_PROVIDER_ID, '=', ProviderController::PROVIDERS_ID)
                ->select(ProviderController::CITIES_NAME_AS_CITY, ProviderController::CITIES_ID_AS_CITY_ID, ProviderController::DEPARTMENTS_NAME_AS_DEPARTMENT, ProviderController::COUNTRIES_NAME_AS_COUNTRY, 'providers.*',ProviderController::PROVIDER_PHONE_NUMBER)
                ->where('providers.deleted_at','=',null)
                ->get();

        }
        return view("provider", $provider);
    }

    /**
     * Función que obtiene un proveedor mediante su id
     * @param $id
     * @return Collection
     */
    public function get_provider($id){

        return DB::table('countries')
            ->join('departments', ProviderController::DEPARTMENTS_COUNTRY_ID, '=', ProviderController::COUNTRIES_ID)
            ->join('cities', ProviderController::CITIES_DEPARTMENT_ID, '=', ProviderController::DEPARTMENTS_ID)
            ->join('providers',ProviderController::PROVIDERS_CITY_ID,'=',ProviderController::CITIES_ID)
            ->join('provider_phones', ProviderController::PROVIDER_PHONES_PROVIDER_ID, '=', ProviderController::PROVIDERS_ID)
            ->select(ProviderController::CITIES_NAME_AS_CITY, ProviderController::CITIES_ID_AS_CITY_ID, ProviderController::DEPARTMENTS_NAME_AS_DEPARTMENT, ProviderController::COUNTRIES_NAME_AS_COUNTRY,ProviderController::PROVIDER_PHONE_NUMBER,
                'providers.address',ProviderController::PROVIDERS_ID,'providers.mail','providers.name','providers.nit',)
            ->where('providers.deleted_at','=',null)
            ->where(ProviderController::PROVIDERS_ID,'=',$id)
            ->get();

    }

    /**
     * Función que edita un proveedor
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit_provider(Request $request){
        $dat = $request->get('dat');

        $provider = DB::table('providers')
            ->join('provider_phones',ProviderController::PROVIDERS_ID,'=',ProviderController::PROVIDER_PHONES_PROVIDER_ID)
            ->where(ProviderController::PROVIDERS_ID,'=',$dat['id'])
            ->get()->first();

        $exist_provider = Provider::where('nit','=',$dat['nit'])->get();
        $exist_phone = Provider_phone::where('number','=',$dat['phone'])->get();
        $exist_mail = Provider::where('mail','=',$dat['mail'])->get();

        $flag_nit = true;

        //Verifica si la el nit se encuentra registrado
        if($exist_provider->count() == 1 && ($exist_provider[0]->id != $provider->id)) {
            $flag_nit=false;
            $request->session()->flash('fail_msg','Ya existe un proveedor con este nit');
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
        if($exist_mail->count() == 1 && ($exist_mail[0]->id != $provider->id)){
            $flag_mail=false;
            $request->session()->flash('fail_msg','Un proveedor ya tiene este correo electrónico');
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

            $exist_phone = Provider_phone::where('provider_id','=',$dat['id'])->first();
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
