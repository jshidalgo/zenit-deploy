@extends('home')
@section('content')
<section id="view-record">
    <div class="text-intro">
        <h1>Gestión de Servicios</h1>
        <span>Agrega, actualiza o elimina servicios prestados</span>
    </div>
    <div id="actions-bar">
        <form id="form-search-record" method="get" action="{{route('view_record')}}">
            <input id="input-search" name="dat[search]" type="text" placeholder="Buscar">
            <button type="submit" class="btn-search" onclick=""><i class="fa fa-search"></i></button>
        </form>

        <div id="actions-buttons">
            <button type="button" onclick="show_edit_record()"><i class="far fa-edit"></i></button>
            <button onclick="remove_record()"><i class="fas fa-trash-alt"></i></button>
            <button type="button" data-toggle="modal" data-target="#modal-add-record"><i class="fas fa-plus"></i></button>
        </div>
    </div>
    <div id="section-table">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th scope="col" class="col-check"></th>
                        <th scope="col">ID</th>
                        <th scope="col">Fecha entrada - salida</th>
                        <th scope="col">Placa vehículo</th>
                        <th scope="col">Nombre cliente</th>
                        <th scope="col">Apellidos clientes</th>
                        <th scope="col">Teléfono cliente</th>
                        <th scope="col">Empleado</th>
                        <th scope="col">Servicio</th>
                </thead>
                <tfoot>
                    <tr>
                        <th scope="col" class="col-check"></th>
                        <th scope="col">ID</th>
                        <th scope="col">Fecha entrada - salida</th>
                        <th scope="col">Placa vehículo</th>
                        <th scope="col">Nombre cliente</th>
                        <th scope="col">Apellidos clientes</th>
                        <th scope="col">Teléfono cliente</th>
                        <th scope="col">Empleado</th>
                        <th scope="col">Servicio</th>
                    </tr>
                </tfoot>
                <tbody>

                @foreach($record as $aux)
                    <tr>
                        <td class="col-check"><input type="checkbox"></td>
                        <td>{{$aux->id}}</td>
                        <td>{{$aux->entry_date}} - {{$aux->departure_date}}</td>
                        <td>{{$aux->license_plate}}</td>
                        <td>{{$aux->customer_name}}</td>
                        <td>{{$aux->customer_last_name}}</td>
                        <td>{{$aux->customer_number}}</td>
                        <td>{{$aux->employee_name}} {{$aux->employee_last_name}}</td>
                        <td>NN</td>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>

    <!--Modal agregar-->
    <div id="modal-add-record" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Agregar datos del servicio</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                            <form id="form-add-record" class="row" method="post" action="{{route('add_record')}}">
                            @csrf
                            <div class="col-12 col-customer">
                                <div class="col-12">
                                    <h3 class="sub-title">Datos del cliente</h3>
                                </div>
                                <div class="col-12">
                                    <label>Cliente:</label>
                                    {!! Form::select('dat[id_customer]',$misClientes, null,['placeholder'=>'Seleccione un Cliente']) !!}
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="name-customer-add">Nombre:</label>
                                    <input id="name-customer-add" placeholder="Nombre" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="last-name-customer-add">Apellidos:</label>
                                    <input id="last-name-customer-add" placeholder="Apellidos" readonly>
                                </div>

                                <div class="col-secundary col-3">
                                    <label for="address-customer-add">Dirección:</label>
                                    <input id="address-customer-add" placeholder="Dirección" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="mail-customer-add">Correo:</label>
                                    <input id="mail-customer-add" placeholder="Correo electrónico" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-vehicle">
                                <div class="col-12">
                                    <h3 class="sub-title">Datos del vehiculo</h3>
                                </div>
                                <div class="col-primary col-12">
                                    <label>Vehiculo:</label>
                                    {!! Form::select('dat[id_vehicle]',$misVehiculos, null,['placeholder'=>'Seleccione un Vehiculo']) !!}
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="plate-vehicle-add">Nombre:</label>
                                    <input id="plate-vehicle-add" placeholder="Placa" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="color-vehicle-add">Color:</label>
                                    <input id="color-vehicle-add" placeholder="Color" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="cylinder-vehicle-add">Cilindraje:</label>
                                    <input id="cylinder-vehicle-add" placeholder="Cilindraje" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="model-vehicle-add">Modelo:</label>
                                    <input id="model-vehicle-add" placeholder="Modelo" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="reference-vehicle-add">Referencia:</label>
                                    <input id="reference-vehicle-add" placeholder="Referencia" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="brand-vehicle-add">Marca:</label>
                                    <input id="brand-vehicle-add" placeholder="Marca" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="mileage-vehicle-add">Kilometraje:</label>
                                    <input type="number" name="dat[mileage]" id="mileage-vehicle-add" placeholder="Kilometraje" min="0">
                                </div>
                            </div>

                            <div class="col-6 col-employee">
                                <div class="col-12">
                                    <h3 class="sub-title">Empleado asignado</h3>
                                </div>
                                <div class="col-12">
                                    <label>Empleado:</label>
                                    {!! Form::select('dat[id_employee]',$misEmpleados, null,['placeholder'=>'Seleccione un Empleado']) !!}
                                </div>
                            </div>

                            <div class="col-6 col-date">
                                <div class="col-12">
                                    <h3 class="sub-title">Fechas del registro</h3>
                                </div>
                                <div class="col-12">
                                    <label>Fecha de entrada:</label>
                                    <input type="date" id="entry_date" name="dat[entry_date]">
                                </div>
                                <div class="col-12">
                                    <label>Fecha de salida:</label>
                                    <input type="date" id="out_date" name="dat[out_date]">
                                </div>

                            </div>

                            <div class="col-12 col-products">
                                <div class="col-12">
                                    <h3 class="sub-title">Sección de productos</h3>
                                </div>

                                <!--Tabla de productos en inventario-->
                                <div class="col-6 col-inventory">
                                    <h3 class="title-table">Productos en inventario</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                        </thead>
                                        @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="{{$product->id}}">
                                            </td>
                                            <td>
                                                {{$product->name}}
                                            </td>
                                            <td>
                                                <input type="number" min="0" max="{{$product->units_available}}" value="{{$product->units_available}}">
                                            </td>
                                            <td>
                                                <button type="button" onclick="addProductRecord(`modal-add-record`)">Agregar</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <!--Tabla de productos utilizados-->
                                <div class="col-6 col-products-used">
                                    <h3 class="title-table">Productos utilizados</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Seccion de servicios -->
                            <div class="col-12 col-services">
                                <div class="col-12">
                                    <h3 class="sub-title">Sección de Servicios</h3>
                                </div>
                                <div class="col-3">
                                    <label for="name-service-add">Nombre:</label>
                                    <input id="name-service-add" type="text" name="dat[name-service-add]">
                                    <input type="hidden" id="id-primary-service">
                                </div>
                                <div class="col-4">
                                    <label for="description-service-add">Descripción:</label>
                                    <textarea name="dat[description-service-add]" id="description-service-add" cols="30" rows="2"></textarea>
                                </div>
                                <div class="col-3">
                                    <label for="cost-service">Costo:</label>
                                    <input id="cost-service" name="dat[cost-service]" type="number" min="0" value="0">
                                </div>
                                <div class="col-2">
                                    <button id="btn-add-service" type="button" onclick="addServiceRecord(`modal-add-record`,`add`)">Agregar</button>
                                    <button id="btn-edit-service" type="button" onclick="editServiceRecord(`modal-add-record`,`add`)">Editar</button>
                                </div>
                                <div class="col-12">
                                    <h3 class="title-table">Servivicos realizados</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" id="products_used" name="dat[products_used]" value="">
                            <input type="hidden" id="services_finished" name="dat[services_finished]" value="">
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearField()">Limpiar</button>
                    <a class="btn-cancel">Cancelar</a>
                    <button class="btn-add-record" onclick="valitateRecordAdd()">Agregar</button>
                </div>
            </div>
        </div>
    </div>


    <!--Modal editar-->
    <div id="modal-edit-record" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar datos del servicio</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="form-edit-record" class="row" method="post" action="{{route('edit_record')}}">
                            @csrf
                            <div class="col-12 col-customer">
                                <div class="col-12">
                                    <h3 class="sub-title">Datos del cliente</h3>
                                </div>
                                <div class="col-12">
                                    <label>Cliente:</label>
                                    {!! Form::select('dat[id_customer]',$misClientes, null,['placeholder'=>'Seleccione un Cliente']) !!}
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="name-customer-edit">Nombre:</label>
                                    <input id="name-customer-edit" placeholder="Nombre" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="last-name-customer-edit">Apellidos:</label>
                                    <input id="last-name-customer-edit" placeholder="Apellidos" readonly>
                                </div>

                                <div class="col-secundary col-3">
                                    <label for="address-customer-edit">Dirección:</label>
                                    <input id="address-customer-edit" placeholder="Dirección" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="mail-customer-edit">Correo:</label>
                                    <input id="mail-customer-edit" placeholder="Correo electrónico" readonly>
                                </div>
                            </div>

                            <div class="col-12 col-vehicle">
                                <div class="col-12">
                                    <h3 class="sub-title">Datos del vehiculo</h3>
                                </div>
                                <div class="col-primary col-12">
                                    <label>Vehiculo:</label>
                                    {!! Form::select('dat[id_vehicle]',$misVehiculos, null,['placeholder'=>'Seleccione un Vehiculo']) !!}
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="plate-vehicle-edit">Nombre:</label>
                                    <input id="plate-vehicle-edit" placeholder="Placa" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="color-vehicle-edit">Color:</label>
                                    <input id="color-vehicle-edit" placeholder="Color" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="cylinder-vehicle-edit">Cilindraje:</label>
                                    <input id="cylinder-vehicle-edit" placeholder="Cilindraje" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="model-vehicle-edit">Modelo:</label>
                                    <input id="model-vehicle-edit" placeholder="Modelo" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="reference-vehicle-edit">Referencia:</label>
                                    <input id="reference-vehicle-edit" placeholder="Referencia" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="brand-vehicle-edit">Marca:</label>
                                    <input id="brand-vehicle-edit" placeholder="Marca" readonly>
                                </div>
                                <div class="col-secundary col-3">
                                    <label for="mileage-vehicle-edit">Kilometraje:</label>
                                    <input type="number" name="dat[mileage_edit]" id="mileage-vehicle-add" placeholder="Kilometraje" min="0">
                                </div>
                            </div>

                            <div class="col-6 col-employee">
                                <div class="col-12">
                                    <h3 class="sub-title">Empleado asignado</h3>
                                </div>
                                <div class="col-12">
                                    <label>Empleado:</label>
                                    {!! Form::select('dat[id_employee]',$misEmpleados, null,['placeholder'=>'Seleccione un Empleado']) !!}
                                </div>
                            </div>

                            <div class="col-6 col-date">
                                <div class="col-12">
                                    <h3 class="sub-title">Fechas del registro</h3>
                                </div>
                                <div class="col-12">
                                    <label>Fecha de entrada:</label>
                                    <input type="date" id="entry_date_edit" name="dat[entry_date_edit]">
                                </div>
                                <div class="col-12">
                                    <label>Fecha de salida:</label>
                                    <input type="date" id="out_date_edit" name="dat[out_date_edit]">
                                </div>

                            </div>

                            <div class="col-12 col-products">
                                <div class="col-12">
                                    <h3 class="sub-title">Sección de productos</h3>
                                </div>

                                <!--Tabla de productos en inventario-->
                                <div class="col-6 col-inventory">
                                    <h3 class="title-table">Productos en inventario</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                        </thead>
                                        @foreach($products as $product)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" id="{{$product->id}}">
                                                </td>
                                                <td>
                                                    {{$product->name}}
                                                </td>
                                                <td>
                                                    <input type="number" min="0" max="{{$product->units_available}}" value="{{$product->units_available}}">
                                                </td>
                                                <td>
                                                    <button type="button" onclick="addProductRecord(`modal-edit-record`)">Agregar</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                                <!--Tabla de productos utilizados-->
                                <div class="col-6 col-products-used">
                                    <h3 class="title-table">Productos utilizados</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Seccion de servicios -->
                            <div class="col-12 col-services">
                                <div class="col-12">
                                    <h3 class="sub-title">Sección de Servicios</h3>
                                </div>
                                <div class="col-3">
                                    <label for="name-service-edit">Nombre:</label>
                                    <input id="name-service-edit" type="text" name="dat[name-service-add]">
                                    <input type="hidden" id="id-primary-service">
                                </div>
                                <div class="col-4">
                                    <label for="description-service-edit">Descripción:</label>
                                    <textarea name="dat[description-service-edit]" id="description-service-edit" cols="30" rows="2"></textarea>
                                </div>
                                <div class="col-3">
                                    <label for="cost-service">Costo:</label>
                                    <input id="cost-service" name="dat[cost-service]" type="number" min="0" value="0">
                                </div>
                                <div class="col-2">
                                    <button id="btn-add-service" type="button" onclick="addServiceRecord(`modal-edit-record`,`edit`)">Editar</button>
                                    <button id="btn-edit-service" type="button" onclick="editServiceRecord(`modal-edit-record`,`edit`)">Editar</button>
                                </div>
                                <div class="col-12">
                                    <h3 class="title-table">Servivicos realizados</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" id="products_used" name="dat[products_used]" value="">
                            <input type="hidden" id="services_finished" name="dat[services_finished]" value="">
                            <input type="hidden" id="id_record" name="dat[id_record]" value="">
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearField()">Limpiar</button>
                    <a class="btn-cancel">Cancelar</a>
                    <button class="btn-add-record" onclick="valitateRecordEdit()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>

    //mensajes emergentes
    var check_msg = '{{isset($check_msg) && !empty($check_msg) && $check_msg!=='' ? $check_msg : ''}}';
    var fail_msg = '{{isset($fail_msg) && !empty($fail_msg) && $fail_msg!=='' ? $fail_msg : ''}}';

    if (check_msg !== '') {
        Swal.fire(
            'Se completo la operación con éxito',
            check_msg,
            'success'
        )
    }
    if (fail_msg) {
        Swal.fire({
            icon: 'error',
            title: 'Ocurrió un error!',
            text: fail_msg
        });
    }

    /**
     * Funcion que se encarga de cargar los campos del cliente
     * Dependiendo del que haya sido seleccionado en el select
     */
    $('#modal-add-record .col-customer select').change(function () {
        var customer = $('#modal-add-record .col-customer select').val();
        $.ajax({
            type:'get',
            url:'/servicios/cliente/{id?}',
            data:{
                _token:'{{csrf_token()}}',
                id: customer
            }
        }).done(function(data) {
            if(data !== "") {
                $('#modal-add-record .col-customer #name-customer-add').val(data.name);
                $('#modal-add-record .col-customer #last-name-customer-add').val(data.last_name);
                $('#modal-add-record .col-customer #address-customer-add').val(data.address);
                $('#modal-add-record .col-customer #mail-customer-add').val(data.mail);
            }else{
                $('#modal-add-record .col-customer #name-customer-add').val("");
                $('#modal-add-record .col-customer #last-name-customer-add').val("");
                $('#modal-add-record .col-customer #address-customer-add').val("");
                $('#modal-add-record .col-customer #mail-customer-add').val("");
            }
        });
    });

    $('#modal-edit-record .col-customer select').change(function () {
        var customer = $('#modal-edit-record .col-customer select').val();
        $.ajax({
            type:'get',
            url:'/servicios/cliente/{id?}',
            data:{
                _token:'{{csrf_token()}}',
                id: customer
            }
        }).done(function(data) {
            if(data !== "") {
                $('#modal-edit-record .col-customer #name-customer-edit').val(data.name);
                $('#modal-edit-record .col-customer #last-name-customer-edit').val(data.last_name);
                $('#modal-edit-record .col-customer #address-customer-edit').val(data.address);
                $('#modal-edit-record .col-customer #mail-customer-edit').val(data.mail);
            }else{
                $('#modal-edit-record .col-customer #name-customer-edit').val("");
                $('#modal-edit-record .col-customer #last-name-customer-edit').val("");
                $('#modal-edit-record .col-customer #address-customer-edit').val("");
                $('#modal-edit-record .col-customer #mail-customer-edit').val("");
            }
        });
    });

    /**
     * Funcion que se encarga de cargar los campos del vehiculo
     * Dependiendo del que haya sido seleccionado en el select
     */
    $('#modal-add-record .col-vehicle select').change(function () {
        var vehicle = $('#modal-add-record .col-vehicle select').val();
        $.ajax({
            type:'get',
            url:'/servicios/vehiculo/{id?}',
            data:{
                _token:'{{csrf_token()}}',
                id: vehicle
            }
        }).done(function(data) {
            if(data.length > 0) {
                $('#modal-add-record .col-vehicle #plate-vehicle-add').val(data[0].license_plate);
                $('#modal-add-record .col-vehicle #color-vehicle-add').val(data[0].color);
                $('#modal-add-record .col-vehicle #cylinder-vehicle-add').val(data[0].cylinder_capacity);
                $('#modal-add-record .col-vehicle #model-vehicle-add').val(data[0].model);
                $('#modal-add-record .col-vehicle #reference-vehicle-add').val(data[0].vehicle_reference);
                $('#modal-add-record .col-vehicle #brand-vehicle-add').val(data[0].brand);
            }else{
                $('#modal-add-record .col-vehicle #plate-vehicle-add').val("");
                $('#modal-add-record .col-vehicle #color-vehicle-add').val("");
                $('#modal-add-record .col-vehicle #cylinder-vehicle-add').val("");
                $('#modal-add-record .col-vehicle #model-vehicle-add').val("");
                $('#modal-add-record .col-vehicle #reference-vehicle-add').val("");
                $('#modal-add-record .col-vehicle #brand-vehicle-add').val("");
            }
        });
    });

    $('#modal-edit-record .col-vehicle select').change(function () {
        var vehicle = $('#modal-edit-record .col-vehicle select').val();
        $.ajax({
            type:'get',
            url:'/servicios/vehiculo/{id?}',
            data:{
                _token:'{{csrf_token()}}',
                id: vehicle
            }
        }).done(function(data) {
            if(data.length > 0) {
                $('#modal-edit-record .col-vehicle #plate-vehicle-edit').val(data[0].license_plate);
                $('#modal-edit-record .col-vehicle #color-vehicle-edit').val(data[0].color);
                $('#modal-edit-record .col-vehicle #cylinder-vehicle-edit').val(data[0].cylinder_capacity);
                $('#modal-edit-record .col-vehicle #model-vehicle-edit').val(data[0].model);
                $('#modal-edit-record .col-vehicle #reference-vehicle-edit').val(data[0].vehicle_reference);
                $('#modal-edit-record .col-vehicle #brand-vehicle-edit').val(data[0].brand);
            }else{
                $('#modal-edit-record .col-vehicle #plate-vehicle-edit').val("");
                $('#modal-edit-record .col-vehicle #color-vehicle-edit').val("");
                $('#modal-edit-record .col-vehicle #cylinder-vehicle-edit').val("");
                $('#modal-edit-record .col-vehicle #model-vehicle-edit').val("");
                $('#modal-edit-record .col-vehicle #reference-vehicle-edit').val("");
                $('#modal-edit-record .col-vehicle #brand-vehicle-edit').val("");
            }
        });
    });

    /**
     * Función que transfiere los productos del inventario al registro
     * De la tabla de inventario a la tabla de usados
     */
    function addProductRecord(id_modal){
        // Filas de productos
        var selected = Array();
        var value_selected = 0;
        var id_selected = 0;

        //Obtiene los IDs de los productos ya agregados
        var products_added = Array();
        $('#'+id_modal+' .col-products-used table tbody td input[type=checkbox]').each(function (){
            products_added.push($(this)[0].id);
        });

        $('#'+id_modal+' .col-inventory table td input[type=checkbox]:checked').each(function (){
            value_selected = $(this).parent().siblings()[1].children[0].value;
            id_selected = $(this)[0].id;
            if(value_selected !== 0 ){
                if(!products_added.includes(id_selected)){
                    //Si el elemento no existe en la tabla de usados, hay que agregar la nueva fila
                    selected.push($(this).parents('tr').clone());
                    $(this).parent().siblings()[1].children[0].max=$(this).parent().siblings()[1].children[0].max - value_selected;
                    $(this).parent().siblings()[1].children[0].value = $(this).parent().siblings()[1].children[0].max;
                }else{
                    //Si el elemento ya existe en la tabla de usados, hay que actualizar los valores
                    var product_used="";
                    products_added.forEach(function (element){
                        if(element === id_selected){
                            product_used = $('#'+id_modal+' .col-products-used table tbody td input[id="'+element+'"]').parent().siblings()[1].children[0];
                            product_used.value= parseInt(product_used.value)+parseInt(value_selected);

                            var product_invent = $('#'+id_modal+' .col-inventory table tbody td input[id="'+element+'"]').parent().siblings()[1].children[0];
                            product_invent.max = parseInt(product_invent.max) - parseInt(value_selected);
                            product_invent.value = parseInt(product_invent.max);
                        }
                    });
                }
            }
        });

        var cmp= "";
        var txt = "";

        selected.forEach(function (elemento){

            if(id_modal === "modal-add-record"){
                cmp= document.createElement("button");
                txt = document.createTextNode("Actualizar")
                cmp.appendChild(txt);
                cmp.setAttribute('onclick','updateProductRecord()');
                cmp.setAttribute('type','button');

                elemento[0].children[3].children[0].replaceWith(cmp);
                elemento[0].children[0].children[0].checked=false;

                $('#'+id_modal+' .col-products-used table tbody').append(elemento);
            }else if(id_modal === "modal-edit-record"){
                cmp= document.createElement("button");
                txt = document.createTextNode("Actualizar")
                cmp.appendChild(txt);
                cmp.setAttribute('onclick','updateProductRecordEdit()');
                cmp.setAttribute('type','button');
                elemento[0].children[3].children[0].replaceWith(cmp);
                elemento[0].children[0].children[0].checked=false;
                $('#'+id_modal+' .col-products-used table tbody').append(elemento);
            }
        });
    }

    /**
     * Función que actualiza las unidades de los productos del inventario
     * con respecto a los usados en el registro
     */
    function updateProductRecord(){

        $('#modal-add-record .col-products-used table tbody td input[type=checkbox]').parents('tr').each(function (){
            var id_used = $(this)[0].children[0].children[0].id;
            var value_used = $(this)[0].children[2].children[0].value;

            var product_update = $('#modal-add-record .col-inventory table tbody td input[id="'+id_used+'"]').parents('tr')[0].children[2].children[0];
            //var total = parseInt(product_update.max) + parseInt(value_used);

            $.ajax({
                type:'get',
                url:'/servicios/producto/{id?}',
                data:{
                    _token:'{{csrf_token()}}',
                    id: id_used
                }
            }).done(function(data) {
                product_update.max = data.units_available - parseInt(value_used);
                product_update.value = data.units_available - parseInt(value_used);
            });
        });
    }

    /**
     * Funcion que valida el formulario y lo envia al controlador
     */
    function valitateRecordAdd(){
        var customer = $('#modal-add-record .col-customer select').val();
        var vehicle = $('#modal-add-record .col-vehicle select').val();
        var employee = $('#modal-add-record .col-employee select').val();
        var entry_date = $('#modal-add-record .col-date #entry_date').val();
        var mileage = $('#modal-add-record .col-services #mileage-vehicle-add').val();

        if(customer !== "" && vehicle !== "" && employee !== "" && entry_date !== "" && mileage !== ""){
            //Productos que se encuentran en la tabla de repuestos utilizados
            var products_used = new Map();

            $('#modal-add-record .col-products-used table tbody tr').each(function (){
                products_used.set(($(this)[0].children[0].children[0].id),($(this)[0].children[2].children[0].value));
            });
            products_used = JSON.stringify(Array.from(products_used.entries()))
            $('#form-add-record #products_used').val(products_used);

            var services_used = Array();
            $('#modal-add-record .col-services table tbody tr').each(function (){
                services_used.push([($(this)[0].children[1].textContent.trim()),($(this)[0].children[2].textContent.trim()),($(this)[0].children[3].textContent.trim())]);
            });
            services_used = JSON.stringify(Array.from(services_used));
            $('#form-add-record #services_finished').val(services_used);

            $('#form-add-record').submit();
        }

        if(customer === ""){
            console.log('falta algo');
        }
        if(vehicle === ""){
            console.log('falta algo');
        }
        if(employee === ""){
            console.log('falta algo');
        }
        if(entry_date === ""){
            console.log('falta algo');
        }
    }


    /**
     * Funcion que agregar servicios en el modal de crear registro
     **/
    let services_tmp = 1;
    function addServiceRecord(id_modal,accion){
        var name = $('#'+id_modal+' .col-services #name-service-'+accion+'').val() ;
        var description = $('#'+id_modal+' .col-services #description-service-'+accion+'').val();
        var cost = $('#'+id_modal+' .col-services #cost-service').val();

        if(name !== "" && description !== ""){
            if(id_modal === "modal-add-record"){
                $cmp = '<tr><td><input id="'+services_tmp+'" type="hidden"></td><td>'+name+'</td><td>'+description+'</td><td>$'+cost+'</td><td><button type="button" onclick="editTableServiceRecord(this,`modal-add-record`,`add`)">Editar</button><button onclick="removeServiceRecordAdd(this)" type="button">Eliminar</button></td></tr>'
                services_tmp++;
            }else if(id_modal === "modal-edit-record"){
                $cmp = '<tr class="pending-service"><td><input id="p'+services_tmp+'" type="hidden"></td><td>'+name+'</td><td>'+description+'</td><td>$'+cost+'</td><td><button type="button" onclick="editTableServiceRecord(this,`modal-edit-record`,`edit`)">Editar</button><button onclick="removeServiceRecordAdd(this)" type="button">Eliminar</button></td></tr>';
                services_tmp++;
            }
            $('#'+id_modal+' .col-services table tbody').append($cmp);
            $('#'+id_modal+' .col-services #name-service-'+accion+'').val("") ;
            $('#'+id_modal+' .col-services #description-service-'+accion+'').val("");
            $('#'+id_modal+' .col-services #cost-service').val(0);
        }
    }

    /**
     * Funcion que carga los datos del servicio a editar
     **/
    function editTableServiceRecord(component,id_modal,action){
        var id = component.closest('tr').children[0].children[0].id;
        var name = component.closest('tr').children[1].textContent.trim();
        var description = component.closest('tr').children[2].textContent.trim();
        var cost = parseInt(component.closest('tr').children[3].textContent.trim().substring(1));

        $('#'+id_modal+' .col-services #name-service-'+action+'').val(name);
        $('#'+id_modal+' .col-services #description-service-'+action+'').val(description);
        $('#'+id_modal+' .col-services #cost-service').val(cost);
        $('#'+id_modal+' .col-services #id-primary-service').val(id);

        $('#'+id_modal+' .col-services #btn-edit-service').css('display','block');
        $('#'+id_modal+' .col-services #btn-add-service').css('display','none');

    }

    /**
     * Funcion que edita servicios en el modal de crear registro
     **/
    function editServiceRecord(id_modal,accion){
        var id = $('#'+id_modal+' .col-services #id-primary-service').val();
        var name = $('#'+id_modal+' .col-services #name-service-'+accion+'').val() ;
        var description = $('#'+id_modal+' .col-services #description-service-'+accion+'').val();
        var cost = $('#'+id_modal+' .col-services #cost-service').val();


        if(name !== "" && description !== ""){
            var input = $('#'+id_modal+' .col-services table tr input[id="'+id+'"]').parents('tr')[0];
            input.children[1].textContent=name;
            input.children[2].textContent = description;
            input.children[3].textContent="$"+cost;
            $('#'+id_modal+' .col-services #btn-edit-service').css('display','none');
            $('#'+id_modal+' .col-services #btn-add-service').css('display','block');

            $('#'+id_modal+' .col-services #id-primary-service').val("");
            $('#'+id_modal+' .col-services #name-service-'+accion+'').val("") ;
            $('#'+id_modal+' .col-services #description-service-'+accion+'').val("");
            $('#'+id_modal+' .col-services #cost-service').val(0);
        }
    }

    /**
     * Funcion que elimina el servicio de la tabla, en el modal de crear registro
     * @param component
     */
    function removeServiceRecordAdd(component){
        component.closest('tr').remove();
    }

    function valitateRecordEdit(){

        var customer = $('#modal-edit-record .col-customer select').val();
        var vehicle = $('#modal-edit-record .col-vehicle select').val();
        var mileage = $('#modal-edit-record .col-vehicle #mileage-vehicle-add').val();
        var employee = $('#modal-edit-record .col-employee select').val();
        var entry_date = $('#modal-edit-record .col-date #entry_date_edit').val();

       if(customer !== "" && vehicle !== "" && mileage !== "" && employee !== "" && entry_date !== ""){

            var products_used = new Map();
            $('#modal-edit-record .col-products-used table tbody tr').each(function (){
                products_used.set(($(this)[0].children[0].children[0].id),($(this)[0].children[2].children[0].value));
            });
            products_used = JSON.stringify(Array.from(products_used.entries()));
            $('#modal-edit-record #products_used').val(products_used);

            var services_used = Array();
            $('#modal-edit-record .col-services table tbody tr').each(function (){
                services_used.push([($(this)[0].children[0].children[0].id),($(this)[0].children[1].textContent.trim()),($(this)[0].children[2].textContent.trim()),($(this)[0].children[3].textContent.trim())]);
            });
            services_used = JSON.stringify(Array.from(services_used));
            $('#modal-edit-record #services_finished').val(services_used);


            $('#modal-edit-record #form-edit-record').submit();

        }


    }
    function updateQuantityProductsTable(){
        //Estructura clave valor que contendra los elementos que toca modificar
        var products_update = new Map();
        $('#modal-edit-record .col-products-used table tbody tr').each(function (){
            products_update.set(($(this)[0].children[0].children[0].id),($(this)[0].children[2].children[0].value));
        });
    }

    let id_record_edit = 0; //Capturamos el ID del registro que se desea editar
    function show_edit_record(){
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[1].innerHTML));
        id_record_edit = selected[0];
        $('#id_record').val(selected[0]);
        if(selected.length === 1){

            $.ajax({
                type:'GET',
                url:'servicios/'+selected[0],
                data:{
                    _token:'{{csrf_token()}}'
                }
            }).done(function (data){
                console.log(data);

                $('#form-edit-record .col-customer select').val(data[0].customer_id);
                $('#form-edit-record .col-vehicle select').val(data[0].vehicle_id);
                $('#form-edit-record .col-employee select').val(data[0].employee_id);

                $('#form-edit-record .col-customer #name-customer-edit').val(data[1].name);
                $('#form-edit-record .col-customer #last-name-customer-edit').val(data[1].last_name);
                $('#form-edit-record .col-customer #address-customer-edit').val(data[1].address);
                $('#form-edit-record .col-customer #mail-customer-edit').val(data[1].mail);

                $('#form-edit-record .col-vehicle #plate-vehicle-edit').val(data[2].license_plate);
                $('#form-edit-record .col-vehicle #color-vehicle-edit').val(data[2].color);
                $('#form-edit-record .col-vehicle #cylinder-vehicle-edit').val(data[2].cylinder_capacity);
                $('#form-edit-record .col-vehicle #model-vehicle-edit').val(data[2].model);
                $('#form-edit-record .col-vehicle #reference-vehicle-edit').val(data[2].name);
                //$('#form-edit-record .col-vehicle #brand-vehicle-edit').val(data[2].brand_id);
                $('#form-edit-record .col-vehicle #mileage-vehicle-add').val(data[0].mileage);
                $('#form-edit-record .col-date #entry_date_edit').val(data[0].entry_date);
                $('#form-edit-record .col-date #out_date_edit').val(data[0].departure_date);

                $('#form-edit-record .col-products-used table tbody').empty();

                data[4].forEach(function (element){
                    $('#form-edit-record .col-products-used table tbody').append('<tr><td><input type="checkbox" id="'+element.product_id+'"></td><td>'+element.product_name+'</td><td><input type="number" min="0"  max="'+(element.unit_available+element.product_quantity)+'" value="'+element.product_quantity+'"></td><td><button type="button" onclick="updateProductRecordEdit()">Actualizar</button></td></tr>');
                });
                //Se elimina el contenido de la tabla de servicios antes de cargar los correspondientes
                $('#form-edit-record .col-services table tbody').empty();

                data[5].forEach(function (element){
                    $('#form-edit-record .col-services table tbody').append('<tr><td><input id="'+element.service_id+'" type="hidden"></td><td>'+element.service_name+'</td><td>'+element.service_description+'</td><td>$'+element.service_price+'</td><td><button type="button" onclick="editTableServiceRecord(this,`modal-edit-record`,`edit`)">Editar</button><button onclick="removeServiceRecordAdd(this)" type="button">Eliminar</button></td></tr>');
                });

                $('#modal-edit-record').modal('show');
            });
        }else if(selected.length > 1){
        Swal.fire({
            icon: 'error',
            title: 'Ocurrió un error!',
            text: 'No puede editar más de un elemento a la vez'
        });
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error!',
                text: 'Debes de seleccionar un elemento'
            });
        }
    }
    function updateProductRecordEdit(){

        $('#modal-edit-record .col-products-used table tbody td input[type=checkbox]').parents('tr').each(function (){

            var id_used = $(this)[0].children[0].children[0].id;
            var value_used = $(this)[0].children[2].children[0].value;
            //console.log("ID: "+id_used+" ; Update: "+value_used);

            //Input de la cantidad del producto en la tabla inventario
            var product_update = $('#modal-edit-record .col-inventory table tbody td input[id="'+id_used+'"]').parents('tr')[0].children[2].children[0];

            $.ajax({
                type:'get',
                url:'/servicios/repuestos/{id_record?}',
                data:{
                    _token:'{{csrf_token()}}',
                    id_record: id_record_edit,
                    id_product: id_used
                }
            }).done(function(data) {
                if(data[0].length > 0){
                    //Actualiza los productos utilizados en el registro actual, los que estan asociados en la BD
                    data[0].forEach(function (aux){
                        if(aux.product_id === parseInt(id_used)){
                            console.log("camvia");
                            console.log("inventario: "+aux.quantity_inventory);
                            console.log("registrado: "+aux.quantity_used);
                            console.log("nuevo: "+value_used);
                            product_update.max = aux.quantity_used + aux.quantity_inventory - parseInt(value_used);
                            product_update.value = aux.quantity_used + aux.quantity_inventory - parseInt(value_used);
                        }
                    });
                }else{
                    //Actualiza el inventario para los productos que aun no se encuentran registrado en el registro
                    product_update.max = data[1].units_available - value_used;
                    product_update.value = data[1].units_available - value_used;
                }
            });
        });
    }

    function remove_record(){
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[1].innerHTML));
        console.log(selected);
        if(selected.length >= 1){
            $.ajax({
                type:'delete',
                url:'/servicios',
                data:{
                    _token:'{{csrf_token()}}',
                    selected: selected
                }
            }).done(function(data) {
                if(data==1){
                    Swal.fire(
                        'Se completo la operación con éxito',
                        'Se eliminaron los registros seleccionados',
                        'success',
                    );
                    location.reload();
                }
            });
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un error!',
                text: 'Debes de seleccionar al menos un elemento'
            });
        }
    }

</script>
@endsection
