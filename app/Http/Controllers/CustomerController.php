<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Customer_phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Class CustomerController
 * @package App\Http\Controllers
 */
class CustomerController extends Controller
{
    const CUSTOMERS_ID = 'customers.id';
    const CUSTOMER_PHONES_CUSTOMER_ID = 'customer_phones.customer_id';
    const IDENTIFICATION_CARD = 'identification_card';
    const CUSTOMER_PHONE_NUMBER = 'customer_phones.number';
    const CUSTOMER_IDENTIFICATION_CARD = 'customers.identification_card';
    /**
     * Funcion que se encarga de registrar un nuevo cliente
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_customer(Request $request){
        $dat=$request->get('dat');

        $exist_customer= Customer::where(CustomerController::IDENTIFICATION_CARD,'=',$dat['cc'])->get();
        $exist_phone = Customer_phone::where('number','=',$dat['phone'])->get();
        $exist_mail = Customer::where('mail','=',$dat['mail'])->get();

        //Verifica que el cliente ya este registrado
        if(isset($exist_customer) &&  $exist_customer->count() == 0){

            //Verifica si el telefono ingresado ya esta registrado
            if(isset($exist_phone) &&  $exist_phone->count() == 0){

                //Verifica si el correo ya esta registrado
                if(isset($exist_mail) && $exist_mail->count() == 0){

                    $customer_trashed = Customer::onlyTrashed()
                        ->where('identification_card','=',$dat['cc'])
                        ->orWhere('mail','=',$dat['mail'])->get()->first();

                    if(isset($customer_trashed) && $customer_trashed != null) {
                        $customer_trashed->forceDelete();
                    }

                    $customer = new Customer();
                    $customer->identification_card = $dat['cc'];
                    $customer->name = $dat['name'];
                    $customer->last_name = $dat['lastName'];
                    $customer->address = $dat['address'];
                    $customer->mail = $dat['mail'];
                    $customer->save();

                    $phone= new Customer_phone();
                    $phone->number = $dat['phone'];
                    $phone->customer_id = $customer->id;
                    $phone->save();

                    $request->session()->flash('check_msg','El cliente se registro con éxito');
                }else{
                    $request->session()->flash('fail_msg','Este correo ya se encuentra asociado a un cliente');
                }
            }else{
                $request->session()->flash('fail_msg','Este teléfono ya se encuentra asociado a un cliente');
            }
        }else{
            $request->session()->flash('fail_msg','Este cliente ya se encuentra registrado');
        }
        return redirect()->route('view_customer');
    }

    /**
     * Función que busca vehiculos a partir de una palabra
     *
     * @param Request $request
     * @param $word
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_view_customer(Request $request)
    {
        $dat = $request->get('dat');
        $customer['fail_msg'] = Session::get('fail_msg');
        $customer['check_msg'] = Session::get('check_msg');
        if (isset($dat) && !empty($dat)) {
            $word = $dat['search'];
            $customer['customer'] = DB::table('customer_phones')
                ->join('customers', CustomerController::CUSTOMERS_ID, '=', CustomerController::CUSTOMER_PHONES_CUSTOMER_ID)

                ->where('customers.deleted_at','=',null)
                ->where(function ($query) use ($word){
                    $query->orWhere(CustomerController::IDENTIFICATION_CARD,'like','%'.$word.'%')
                    ->orWhere('name','like','%'.$word.'%')
                    ->orWhere('last_name','like','%'.$word.'%')
                    ->orWhere(CustomerController::CUSTOMER_PHONE_NUMBER,'like','%'.$word.'%');
                })
                ->select(CustomerController::CUSTOMER_PHONE_NUMBER, 'customers.*')
                ->get();
        } else {

            $customer['customer'] = DB::table('customer_phones')
                ->join('customers', CustomerController::CUSTOMERS_ID, '=', CustomerController::CUSTOMER_PHONES_CUSTOMER_ID)
                ->where('customers.deleted_at','=',null)
                ->select(CustomerController::CUSTOMER_PHONE_NUMBER, 'customers.*')
                ->get();

        }
        return view('customer',$customer);
    }

    /**
     * Función que obtiene un cliente mediante su cédula
     * @param $cc
     * @return \Illuminate\Support\Collection
     */
    public function get_customer($cc){

        return  DB::table('customer_phones')
            ->join('customers', CustomerController::CUSTOMERS_ID, '=', CustomerController::CUSTOMER_PHONES_CUSTOMER_ID)
            ->where(CustomerController::CUSTOMER_IDENTIFICATION_CARD, '=', $cc)
            ->select(CustomerController::CUSTOMER_PHONE_NUMBER, CustomerController::CUSTOMERS_ID, CustomerController::CUSTOMER_IDENTIFICATION_CARD, 'customers.name', 'customers.last_name', 'customers.address', 'customers.mail')
            ->get();
    }

    /**
     * Función que edita un cliente
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit_customer(Request $request){
        $dat = $request->get('dat');
        $customer = DB::table('customers')
            ->join('customer_phones',CustomerController::CUSTOMERS_ID,'=',CustomerController::CUSTOMER_PHONES_CUSTOMER_ID)
            ->where(CustomerController::CUSTOMERS_ID,'=',$dat['id'])
            ->get()->first();

        $exist_customer = Customer::where(CustomerController::IDENTIFICATION_CARD,'=',$dat['cc'])->get();
        $exist_phone = Customer_phone::where('number','=',$dat['phone'])->get();
        $exist_mail = Customer::where('mail','=',$dat['mail'])->get();

        $flag_cc = true;

        //Verifica si la cédula ya se encuentra registrada
        if($exist_customer->count() == 1 && ($exist_customer[0]->id != $customer->id)) {
            $flag_cc=false;
            $request->session()->flash('fail_msg','Ya existe un cliente con esta cédula');
        }

        $flag_phone = true;
        // Verifica si el telefono ingresado ya se encuentra registrado
        if($exist_customer->count() >= 1){
            foreach ($exist_phone as $aux){
                if($aux->customer_id != $customer->id){
                    $flag_phone=false;
                    $request->session()->flash('fail_msg','Un cliente ya tiene este número de teléfono');
                }
            }

        }

        $flag_mail=true;
        //Verifica si el mail ingresado ya se encuentra registrado
        if($exist_mail->count() == 1 && ($exist_mail[0]->id != $customer->id)){
            $flag_mail=false;
            $request->session()->flash('fail_msg','Un cliente ya tiene este correo electrónico');
        }

        if($flag_cc && $flag_phone && $flag_mail){
            $exist_customer = Customer::find($dat['id']);
            $exist_customer->identification_card = $dat['cc'];
            $exist_customer->name = $dat['name'];
            $exist_customer->last_name = $dat['last_name'];
            $exist_customer->address = $dat['address'];
            $exist_customer->mail = $dat['mail'];

            $exist_customer->save();

            $exist_phone = Customer_phone::where('customer_id','=',$dat['id'])->first();
            $exist_phone->number = $dat['phone'];

            $exist_phone->save();

            $request->session()->flash('check_msg','Se actualizaron los datos del cliente con éxito');
        }
        return redirect()->route('view_customer');

    }

    /**
     * Función que elimina los clientes seleccionados
     * @param Request $request
     * @return int
     */
    public function delete_customer(Request $request){

        if(sizeof($request->selected) > 0){
            foreach ($request->selected as $aux){
                $customer = Customer::find($aux);
                if(!empty($customer)){
                    $phones = Customer_phone::where('customer_id','=',$customer->id)->get();
                    foreach ($phones as $phone){
                        $phone->delete();
                    }
                    $customer->delete();
                }
            }
            return 1;
        }
        return 0;
    }
}
