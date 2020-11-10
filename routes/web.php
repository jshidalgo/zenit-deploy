<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});
//ruta home
Route::get('/home',[HomeController::class,'index'])->name('home');

//rutas para empleados
Route::get('/empleados',[EmployeeController::class,'show_view_employee'])->name('view_employee');
Route::post('/empleados',[EmployeeController::class,'create_employee'])->name('add_employee');
Route::get('/empleados/{cedula?}',[EmployeeController::class,'get_employee'])->name('get_employee');
Route::post('/empleados/editar',[EmployeeController::class,'edit_employee'])->name('edit_employee');
Route::delete('/empleados',[EmployeeController::class,'delete_employee'])->name('delete_employee');
