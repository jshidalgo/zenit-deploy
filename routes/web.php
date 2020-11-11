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

//rutas para los proveedores
Route::get('/proveedores',[ProviderController::class,'show_view_provider'])->name('view_provider');
Route::post('/proveedores',[ProviderController::class,'create_provider'])->name('add_provider');
Route::get('/proveedores/{id?}',[ProviderController::class,'get_provider'])->name('get_provider');
Route::post('/proveedores/editar',[ProviderController::class,'edit_provider'])->name('edit_provider');
Route::delete('/proveedores',[ProviderController::class,'delete_provider'])->name('delete_provider');

//rutas para compras
Route::get('/compras',[PurchaseController::class,'show_view_purchase'])->name('view_purchase');
Route::post('/compras',[PurchaseController::class,'create_purchase'])->name('add_purchase');
Route::get('/compras/{id?}',[PurchaseController::class,'get_purchase'])->name('get_purchase');
Route::post('/compras/editar',[PurchaseController::class,'edit_purchase'])->name('edit_purchase');
Route::delete('/compras',[PurchaseController::class,'delete_purchase'])->name('delete_purchase');

//rutas para productos
Route::get('/productos',[ProductController::class,'show_view_product'])->name('view_product');
Route::post('/productos',[ProductController::class,'create_product'])->name('add_product');
Route::get('/productos/{id?}',[ProductController::class,'get_product'])->name('get_product');
Route::post('/productos/editar',[ProductController::class,'edit_product'])->name('edit_product');
Route::delete('/productos',[ProductController::class,'delete_product'])->name('delete_product');

//rutas para vehiculos
Route::get('/vehiculos',[VehicleController::class,'show_view_vehicle'])->name('view_vehicle');
Route::post('/vehiculos',[VehicleController::class,'create_vehicle'])->name('add_vehicle');
Route::get('/vehiculos/{id?}',[VehicleController::class,'get_vehicle'])->name('get_vehicle');
Route::post('/vehiculos/editar',[VehicleController::class,'edit_vehicle'])->name('edit_vehicle');
Route::delete('/vehiculos',[VehicleController::class,'delete_vehicle'])->name('delete_vehicle');


//rutas para clientes
Route::get('/clientes',[CustomerController::class,'show_view_customer'])->name('view_customer');
Route::post('/clientes',[CustomerController::class,'create_customer'])->name('add_customer');
Route::get('/clientes/{cedula?}',[CustomerController::class,'get_customer'])->name('get_customer');
Route::post('/clientes/editar',[CustomerController::class,'edit_customer'])->name('edit_customer');
Route::delete('/clientes',[CustomerController::class,'delete_customer'])->name('delete_customer');
