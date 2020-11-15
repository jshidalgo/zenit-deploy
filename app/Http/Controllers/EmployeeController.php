<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Employee_phone;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Class EmployeeController
 * @package App\Http\Controllers
 */
class EmployeeController extends Controller
{
    /**
     * Funcion que se encarga de registrar un nuevo empleado
     * @param Request $request
     * @return RedirectResponse
     */
    public function create_employee(Request $request){

        $dat=$request->get('dat');

        $exist_employee = Employee::where('identification_card','=',$dat['cc'])->get();
        $exist_phone = Employee_phone::where('number','=',$dat['phone'])->get();
        $exist_mail = Employee::where('mail','=',$dat['mail'])->get();

        //Verifica que el empleado ya este registrado
        if(isset($exist_employee) &&  $exist_employee->count() == 0){

            //Verifica si el telefono ingresado ya esta registrado
            if(isset($exist_phone) &&  $exist_phone->count() == 0){

                //Verifica si el correo ya esta registrado
                if(isset($exist_mail) && $exist_mail->count() == 0){
                    $employee = new Employee();
                    $employee->identification_card = $dat['cc'];
                    $employee->name = $dat['name'];
                    $employee->last_name = $dat['last_name'];
                    $employee->address = $dat['address'];
                    $employee->mail = $dat['mail'];
                    $employee->save();

                    $phone= new Employee_phone();
                    $phone->number = $dat['phone'];
                    $phone->employee_id= $employee->id;
                    $phone->save();

                    $request->session()->flash('check_msg','El empleado se registro con éxito');
                }else{
                    $request->session()->flash('fail_msg','Este correo ya se encuentra asociado a un cliente');
                }
            }else{
                $request->session()->flash('fail_msg','Este teléfono ya se encuentra asociado a un cliente');
            }
        }else{
            $request->session()->flash('fail_msg','Este empleado ya se encuentra registrado');
        }
        return redirect()->route('view_employee');
    }

    /**
     * Función que busca empleado a partir de una palabra
     * Esta función solo tiene en cuenta la cédula, nombre y los apellidos
     * @param Request $request
     * @return Factory|View
     */
    public function show_view_employee(Request $request){

        $dat = $request->get('dat');
        $employees['fail_msg'] = Session::get('fail_msg');
        $employees['check_msg'] = Session::get('check_msg');
        if(isset($dat) && !empty($dat)){
            //palabra recibida
            $word = $dat['search'];
            $employees['employees'] = DB::table('employees')
                ->join('employee_phones', 'employee_phones.employee_id','=', 'employees.id')
                ->where('identification_card','like','%'.$word.'%')
                ->orWhere('name','like','%'.$word.'%')
                ->orWhere('last_name','like','%'.$word.'%')
                ->select('employees.*','employee_phones.number')
                ->get();

        }else{
            $employees['employees'] = DB::table('employees')
                ->join('employee_phones', 'employee_phones.employee_id','=', 'employees.id')
                ->where('employees.deleted_at','=',null)
                ->select('employees.*','employee_phones.number')
                ->get();
        }

        return view('employee',$employees);
    }

    /**
     * Función que obtiene un empleado mediante su cédula
     * @param $cc
     * @return Collection
     */
    public function get_employee($cc){

        return DB::table('employees')
            ->join('employee_phones', 'employee_phones.employee_id','=', 'employees.id')
            ->where('employees.identification_card','=',$cc)
            ->select('employees.id','employees.identification_card','employees.name','employees.last_name','employees.address','employees.mail','employee_phones.number')
            ->get();

    }

    /**
     * Función que se encarga de modificar un empleado
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit_employee(Request $request){
        $dat = $request->get('dat');

        $employee = DB::table('employees')
            ->join('employee_phones','employees.id','=','employee_phones.employee_id')
            ->where('employees.id','=',$dat['id'])
            ->get()->first();

        $exist_employee = Employee::where('identification_card','=',$dat['cc'])->get();
        $exist_phone = Employee_phone::where('number','=',$dat['phone'])->get();
        $exist_mail = Employee::where('mail','=',$dat['mail'])->get();

        $flag_cc = true;

        //Verifica si la cédula ya se encuentra registrada
        if($exist_employee->count() == 1) {
            if($exist_employee[0]->id != $employee->id){
                $flag_cc=false;
                $request->session()->flash('fail_msg','Ya existe un empleado con esta cédula');
            }
        }

        $flag_phone = true;
        // Verifica si el telefono ingresado ya se encuentra registrado
        if($exist_employee->count() >= 1){
            foreach ($exist_phone as $aux){
                if($aux->employee_id != $employee->id){
                    $flag_phone=false;
                    $request->session()->flash('fail_msg','Un empleado ya tiene este número de teléfono');
                }
            }

        }

        $flag_mail=true;
        //Verifica si el mail ingresado ya se encuentra registrado
        if($exist_mail->count() == 1){
            if($exist_mail[0]->id != $employee->id){
                $flag_mail=false;
                $request->session()->flash('fail_msg','Un empleado ya tiene este correo electrónico');

            }
        }

        if($flag_cc && $flag_phone && $flag_mail){
            $exist_employee = Employee::find($dat['id']);
            $exist_employee->identification_card = $dat['cc'];
            $exist_employee->name = $dat['name'];
            $exist_employee->last_name = $dat['last_name'];
            $exist_employee->address = $dat['address'];
            $exist_employee->mail = $dat['mail'];
            $exist_employee->save();

            $exist_phone = Employee_phone::where('employee_id','=',$dat['id'])->first();
            $exist_phone->number = $dat['phone'];
            $exist_phone->save();
            $request->session()->flash('check_msg','Se actualizaron los datos del empleado con éxito');
        }

        return redirect()->route('view_employee');
    }

    /**
     * Función que elimina empleados
     * @param Request $request
     * @return int
     */
    public function delete_employee(Request $request){
        if(sizeof($request->selected)>0){
            foreach ($request->selected as $aux){
                $employee = Employee::where('identification_card','=',$aux)->first();
                if(!empty($employee)){
                    $phones = Employee_phone::where('employee_id','=',$employee->id)->get();
                    foreach ($phones as $phone){
                        $phone->delete();
                    }
                    $employee->delete();
                }
            }
            return 1;
        }
        return 0;
    }
}
