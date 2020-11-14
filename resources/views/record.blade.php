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
            <button onclick="remove_recod()"><i class="fas fa-trash-alt"></i></button>
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
                    <div class="container">

                        <form id="form-add-record" method="post" action="{{route('add_record')}}">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <label for="name-record"><b>Ingreso y salida de vehículo</b></label>
                                    </div>
                                    <div class="row">
                                        <label for="entry-date-record">Fecha entrada</label>
                                        <input name="dat[entry_date]" id="entry-date-record" type="date" placeholder="dd/mm/aaaa" required pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}">
                                        <span class="msg-error-entry-date">Ingrese una fecha de ingreso de vehículo</span>
                                    </div>
                                    <div class="row">
                                        <label for="departure-date-record">Fecha salida</label>
                                        <input name="dat[departure_date]" id="departure-date-record" type="date" placeholder="dd/mm/aaaa" required pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}">
                                        <span class="msg-error-departure-date">Ingrese una fehca de salida</span>
                                    </div>
                                    <div class="row">
                                        <label for="id-vehicle-record"><b>Datos vehículo</b></label>
                                    </div>
                                    <div class="row">
                                        <label for="id-vehicle-record">Placa vehículo</label>
                                        <select class="form-control" id="id-vehicle-record" name="dat[id_vehicle]">
                                            <option value="-1" selected>Seleccione una placa</option>
                                        </select>
                                        <span class="msg-error-plate">Seleccione un número de placa</span>
                                    </div>
                                    <div class="row">
                                        <label for="mileage-record">Kilometraje vehículo</label>
                                        <input name="dat[mileage]" id="mileage-record" type="number" placeholder="Kilometraje de vehículo" min="0">
                                        <span class="msg-error-mileage">Ingrese el kilometraje del vehículo</span>
                                    </div>
                                    <div class="row">
                                        <label for="id-customer-record"><b>Datos cliente</b></label>
                                    </div>
                                    <div class="row">
                                        <label for="id-customer-record">Cédula cliente</label>
                                        <select onchange='fillClient()' class="form-control" id="id-customer-record" name="dat[id_customer]">
                                            <option value="-1" selected>Seleccione una cédula</option>
                                        </select>
                                        <span class="msg-error-cc">Seleccione un número de cédula</span>
                                    </div>
                                    <div class="row">
                                        <label for="name-record">Nombre cliente</label>
                                        <input name="dat[name]" id="name-record" type="text" placeholder="Nombre cliente" class="readonly" readonly>
                                        <span class="msg-error-name">Ingrese el nombre del cliente</span>
                                    </div>
                                    <div class="row">
                                        <label for="last-name-record">Apellidos cliente</label>
                                        <input name="dat[last_name]" id="last-name-record" type="text" placeholder="Apellidos del cliente" class="readonly" readonly>
                                        <span class="msg-error-last-name">Ingrese el apellido del cliente</span>
                                    </div>
                                    <div class="row">
                                        <label for="phone-record">Télefono</label>
                                        <input name="dat[phone]" id="phone-record" type="text" placeholder="Teléfono" class="readonly" readonly>
                                        <span class="msg-error-phone">Ingrese un teléfono</span>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="row">
                                        <label for="id-employee-record"><b>Infomación de empleado</b></label>
                                    </div>
                                    <div class="row">
                                        <label for="id-employee-record">Empleado</label>
                                        <select class="form-control" id="id-employee-record" name="dat[id_employee]">
                                            <option value="-1" selected>Seleccione una empleado</option>
                                        </select>
                                        <span class="msg-error-cc-employee">Seleccione un empleado</span>
                                    </div>

                                    <div class="row">
                                        <label for="id-vehicle-record"><b>Datos del servicio</b></label>
                                    </div>
                                    <div class="row-services">
                                        <!-- esto es lo que se repite -->
                                        <div class="row-service" id="service1">
                                            <div class="row">
                                                <label for="service-name-record1">Nombre servicio</label>
                                                <input type="text" placeholder="Nombre del servicio" id="service-name-record1" name="service[name1]">
                                                <span class="msg-error-service-name" id="msg-error-service-name1">Ingrese el nombre del servicio</span>
                                            </div>
                                            <div class="row">
                                                <label for="service-description-record1">Descripción servicio</label>
                                                <textarea name="service[description1]" id="service-description-record1" placeholder="Descripción del servicio" rows="3"></textarea>
                                                <span class="msg-error-service-description" id="msg-error-service-description1">Ingrese la descripción del servicio</span>
                                            </div>
                                            <div class="row">
                                                <label for="service-price-record1">Valor del servicio</label>
                                                <input onchange='calcularPrecioFinal()'  name="service[price1]" id="service-price-record1" placeholder="Valor del servicio" type="number" min='0'>
                                                <span class="msg-error-service-price" id="msg-error-service-price1">Ingrese el valor del servicio</span>
                                            </div>
                                            <div class="modal-footer">
                                                <i class="fas fa-plus-circle fa-2x" onclick="addService()" id='service-add1'></i>
                                                <i class="fas fa-minus-circle fa-2x" id='service-del1' onclick="deleteService(1)"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for=""><b>Infomación de productos</b></label>
                                    </div>
                                    <div class="row-products">
                                        <!-- esto es lo que se repite -->
                                        <div class="row-product" id="product1">
                                            <div class="row">
                                                <label for="name-product-record1">Nombre producto</label>
                                                <select onchange='fillSelectedProduct(1)' class="form-control" id="name-product-record1" name="product[idProduct1]">
                                                    <option value="-1" selected>Seleccione un producto</option>
                                                </select>
                                                                                               
                                                <span class="msg-error-name-product" id='msg-error-name-product1'>Seleccione un producto</span>
                                            </div>
                                            <div class="row">
                                                <label for="product-description-record1">Descripción producto</label>
                                                <textarea name="product[description1]" id="product-description-record1" placeholder="Descripción del producto" rows="3" class="readonly" readonly ></textarea>
                                                <span class="msg-error-product-description" id="msg-error-product-description1">Ingrese la descripción del producto</span>
                                            </div>
                                            <div class="row">
                                                <label for="amount-product-record1">Cantidad</label>
                                                <input onchange='calculatePriceProduct(1)' name="product[amountProduct1]" id="amount-product-record1" type="number" placeholder="Cantidad producto usado" min='1'>
                                                <span class="msg-error-amount-product" id="msg-error-amount-product1">Ingrese la cantidad de producto usado</span>
                                            </div>
                                            <div class="row">
                                                <label for="price-product-record1">Precio venta</label>
                                                <input name="product[priceProduct1]" id="price-product-record1" type="text" placeholder="Precio de producto" class="readonly" readonly>
                                                <span class="msg-error-price-product" id="msg-error-price-product1">Ingrese el precio del producto</span>
                                            </div>
                                            <div class="modal-footer">
                                                <i class="fas fa-plus-circle fa-2x" onclick="addProduct()" id='product-add1'></i>
                                                <i class="fas fa-minus-circle fa-2x" id='product-del1' onclick="deleteProduct(1)"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="total-price-record"><b>Valor final</b></label>
                                    </div>
                                    <div class="row">
                                        <label for="total-price-record">Precio final</label>
                                        <input name="dat[total_price]" id="total-price-record" type="text" placeholder="Precio final" value="0" class="readonly" readonly>
                                        <span class="msg-error-total-price">Ingrese el precio final de los servicio prestados</span>
                                    </div>
                                </div>
                        
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearField()">Limpiar</button>
                    <a class="btn-cancel">Cancelar</a>
                    <button class="btn-add-record" onclick="valitateRecord()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script>
       
       /**
     * Función que carga los datos del record en el modal de editar
     */
    function show_edit_record() {
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[2].innerHTML));
        console.log(selected);
        if(selected.length === 1){
            //Necesito cargar los datos en el modal
            $.ajax({
                type:'GET',
                url:'servicios/'+selected[0],
                data:{
                    _token:'{{csrf_token()}}'
                }
            }).done(function(data) {
                // document.getElementById('id-employee-edit').value=data[0].id;
                // document.getElementById('cc-employee-edit').value=data[0].identification_card;
                // document.getElementById('name-employee-edit').value=data[0].name;
                // document.getElementById('last-name-employee-edit').value=data[0].last_name;
                // document.getElementById('addr-employee-edit').value=data[0].address;
                // document.getElementById('mail-employee-edit').value=data[0].mail;
                // document.getElementById('phone-employee-edit').value=data[1].number;
            });
            //Mostrar modal para editar
            $('#modal-edit-employee').modal('show');
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

    /**
     * Función que permite eliminar los servicios seleccionados
     */
    function remove_recod() {
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[1].innerHTML));
        console.log(selected);
        if(selected.length >= 1){
            $.ajax({
                type:'delete',
                url:'servicios',
                data:{
                    _token:'{{csrf_token()}}',
                    selected: selected
                }
            }).done(function(data) {
                if(data==1){
                    Swal.fire(
                        'Se completo la operación con éxito',
                        'Se eliminaron los servicios seleccionados',
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
         
        //Establecer fecha y hora actual
        var datetime = new Date();
        var date = datetime.getFullYear()+'-'+(datetime.getMonth()+1)+"-"+datetime.getDate();
        //esteblcer valores en componentes
        $('#departure-date-record').val(date);
        
        //llenar combos
        //combo vehiculos
        var proveedores = @json($vehicle); // no es un error
        
        proveedores.forEach(element => {
            $('#id-vehicle-record').append($('<option />', {
                text: element.license_plate,
                value: element.id,
            }));
        });

        //como clientes
        var customers = @json($customer); // no es un error
        customers.forEach(element => {
            $('#id-customer-record').append($('<option />', {
                text: element.identification_card,
                value: element.id,
            }));
        });
        //como empleados
        var employees = @json($employee); // no es un error
        employees.forEach(element => {
            var nombre = element.name + " " +element.last_name;
            $('#id-employee-record').append($('<option />', {
                text: nombre,
                value: element.id,
            }));
        });
        //combo productos
        fillProducts(1);
        function fillProducts(id){
            // console.log("llenado: "+id);
            var products = @json($product); // no es un error
            products.forEach(element => {
                if (element.units_available > 0) {
                    $('#name-product-record'+id).append($('<option />', {
                        text: element.name,
                        value: element.id,
                    }));
                }
            });
        }
        //llena la informacion asociada al producto seleccionado
        function fillSelectedProduct(id){
            var select = $('#name-product-record'+id).val();
            var products = @json($product); // no es un error
            products.forEach(element => {
                if (element.id == select) { //si lo cambia procure que los dos sean de la misma clase :)             
                    $('#product-description-record'+id).val(element.description);
                    $('#amount-product-record'+id).val(1)
                    $('#amount-product-record'+id).attr('max',element.units_available);
                    $('#price-product-record'+id).val(element.sale_price);
                    $('#price-product-record'+id).val(element.sale_price);
                    //actualiza el valor final
                    calcularPrecioFinal();
                }else if(select === "-1"){
                    $('#product-description-record'+id).val("");
                    $('#amount-product-record'+id).val("");
                    $('#price-product-record'+id).val("");
                }
            });
        }
        //llena la informacion del cliente
        function fillClient(){
            var select = $('#id-customer-record').val();
            var customers = @json($customer); // no es un error
            customers.forEach(element => {
                if (element.id == select) { //si lo cambia procure que los dos sean de la misma clase :)          
                    $('#name-record').val(element.name);
                    $('#last-name-record').val(element.last_name);
                    $('#phone-record').val(element.number);
                }else if (select === "-1") {    
                    $('#name-record').val("");
                    $('#last-name-record').val("");
                    $('#phone-record').val('');
                }
            });
        }

        //metodo que permite calcular el precio de los productos
        function calculatePriceProduct(id){
            var cantidad = parseInt($('#amount-product-record'+id).val());
            var valor = 0;
            var select = $('#name-product-record'+id).val();
            var products = @json($product); // no es un error
            products.forEach(element => {
                if (element.id == select) { //si lo cambia procure que los dos sean de la misma clase :) 
                    if (!isNaN(cantidad)) {
                        valor = parseInt(element.sale_price)*cantidad;       
                
                    }
                }else if(select === "-1"){
                   valor = 0;
                }
            });
            $('#price-product-record'+id).val(valor);
            calcularPrecioFinal();
        }

        //calcula el precio final del servicio
        function calcularPrecioFinal(){
            var totalServicio = 0
            var totalProductos = 0
            //precio productos
            var products = $(".row-products").children();
            
            for (i = 0; i < products.length; i++) {
                var idProduct = products[i].getAttribute('id').substring(7);
                var price = parseInt($('#price-product-record' + (idProduct)).val());
                if (!isNaN(price)) {
                    totalProductos+= price;
                }
            }
            //pricio servicio
            var service = $(".row-services").children();
            for (i = 0; i < service.length; i++) {
                var idService = service[i].getAttribute('id').substring(7);
                var price = parseInt($('#service-price-record' + (idService)).val());
                if (!isNaN(price)) {
                    totalServicio+=price;
                }
            }
            // console.log(totalProductos,totalServicio);
            // console.log("valor final: ");
            $('#total-price-record').val(totalServicio+totalProductos);
        }
        //mensajes emergentes
        var check_msg = '{{isset($check_msg) && !empty($check_msg) && $check_msg!='' ? $check_msg : ''}}';
        var fail_msg = '{{isset($fail_msg) && !empty($fail_msg) && $fail_msg!='' ? $fail_msg : ''}}';

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

        //numero de productos agregados
        var products = 2;
        //metodo para agregar un producto
        function addProduct() {

            if (valiteProducts(0)) {
                //se agrega el contenedor del producto
                // console.log(products);
                var contenedorProducto = "<div class='row-product' id='product" + products + "'></div>";
                $('.row-products').append(contenedorProducto);
                //se agrega el HMTL del nombre del producto
                var nombreProducto = "<div class='row'>" +
                    "<label for='name-product-record"+products+"'>Nombre producto</label>" +
                    "<select onchange='fillSelectedProduct("+products+")' class='form-control' id='name-product-record"+products+"' name='product[idProduct"+products+"]'>" +
                    "<option value='-1' selected>Seleccione un producto</option></select>"+
                    "<span class='msg-error-name-product' id='msg-error-name-product"+products+"'>Seleccione un producto</span>" +
                    "</div>";
                $('#product' + products).append(nombreProducto);
                //descripcion prod
                var descripcionProducto = "<div class='row'>" +
                    "<label for='product-description-record"+products+"'>Descripción producto</label>" +
                    "<textarea name='product[description"+products+"]' id='product-description-record"+products+"' placeholder='Descripción del producto' rows='3' class='readonly' readonly ></textarea>" +
                    "<span class='msg-error-product-description' id='msg-error-product-description"+products+"'>Ingrese la descripción del producto</span>" +
                    "</div>";
                $('#product' + products).append(descripcionProducto);
                fillProducts(products);

                // se agrega el HTML de la cantidad
                var cantidadProducto = "<div class='row'>" +
                    "<label for='amount-product-record"+products+"'>Cantidad</label>" +
                    "<input onchange='calculatePriceProduct("+products+")' name='product[amountProduct"+products+"]' id='amount-product-record"+products+"' type='number' placeholder='Cantidad producto usado' min='1'>" +
                    "<span class='msg-error-amount-product' id='msg-error-amount-product"+products+"'>Ingrese la cantidad de producto usado</span>" +
                    "</div>";
                $('#product' + products).append(cantidadProducto);

                 // se agrega el HTML de precio
                 var precioProducto = "<div class='row'>" +
                    "<label for='price-product-record"+products+"'>Precio venta</label>" +
                    "<input name='product[priceProduct"+products+"]' id='price-product-record"+products+"' type='text' placeholder='Precio de producto' class='readonly' readonly>" +
                    "<span class='msg-error-price-product' id='msg-error-price-product"+products+"'>Ingrese el precio del producto</span>" +
                    "</div>";
                $('#product' + products).append(precioProducto);
                
                //se agrega el hTML de los botones, ocultando el de agregar del anterior
                $('#product-add' + (products - 1)).css('display', 'none');
                //mostrando el btn de eliminar
                $('#product-del' + (products - 1)).css('display', 'block');
                var btnProducto = "<div class='modal-footer'>" +
                    "<i class='fas fa-plus-circle fa-2x' onclick='addProduct()' id='product-add" + products + "'></i>" +
                    "<i class='fas fa-minus-circle fa-2x' id='product-del" + products + "' onclick='deleteProduct(" + products + ")'></i>" +
                    "</div>";
                $('#product' + products).append(btnProducto);
                //incrementar el valor de los productos agregados
                products += 1;
            }
        }

        //metodo para eliminar un producto del DOM
        //numberProduct - numero que identifica al producto dentro del DOM
        function deleteProduct(numberProduct) {
            //$("#product3").detach()
            // console.log(numberProduct);
            //eliminando el elemento
            //se elimina el div que contien todo la informacion del producto
            $("#product" + numberProduct).detach();
            calcularPrecioFinal();
        }

        //numero de productos agregados
        var services = 2;
        //metodo para agregar un servicio
        function addService() {
            if (validateServices(0)) {


                // console.log("agregando servicio");
                var contenedorServicios = "<div class='row-product' id='service" + services + "'></div>";
                $('.row-services').append(contenedorServicios);
                //se agrega el HMTL del nombre del producto
                var nombreServicio = "<div class='row'>" +
                    "<label for='service-name-record" + services + "'>Nombre servicio</label>" +
                    "<input type='text' placeholder='Nombre del servicio' id='service-name-record" + services + "' name='service[name" + services + "]'>" +
                    "<span class='msg-error-service-name' id='msg-error-service-name" + services + "'>Ingrese el nombre del servicio</span>" +
                    "</div>";
                $('#service' + services).append(nombreServicio);
                //se agrega el HTML de la descripcion
                var descripcionServicio = "<div class='row'>" +
                    "<label for='service-description-record" + services + "'>Descripción servicio</label>" +
                    "<textarea name='service[description" + services + "]' id='service-description-record" + services + "' placeholder='Descripción del servicio' rows='3'></textarea>" +
                    "<span class='msg-error-service-description' id='msg-error-service-description" + services + "'>Ingrese la descripción del servicio</span>" +
                    "</div>";
                $('#service' + services).append(descripcionServicio);
                //se agrega el HTML del precio del servicio
                var precioServicio = "<div class='row'>" +
                    "<label for='service-price-record"+services+"'>Valor del servicio</label>" +
                    "<input onchange='calcularPrecioFinal()' name='service[price"+services+"]' id='service-price-record"+services+"' placeholder='Valor del servicio' type='number' min='0'>" +
                    "<span class='msg-error-service-price' id='msg-error-service-price"+services+"'>Ingrese el valor del servicio</span>" +
                    "</div>";
                $('#service' + services).append(precioServicio);
                //se agrega el hTML de los botones, ocultando el de agregar del anterior
                $('#service-add' + (services - 1)).css('display', 'none');
                //mostrando el btn de eliminar
                $('#service-del' + (services - 1)).css('display', 'block');
                var btnProducto = "<div class='modal-footer'>" +
                    "<i class='fas fa-plus-circle fa-2x' onclick='addService()' id='service-add" + services + "'></i>" +
                    "<i class='fas fa-minus-circle fa-2x' id='service-del" + services + "' onclick='deleteService(" + services + ")'></i>" +
                    "</div>";
                $('#service' + services).append(btnProducto);
                //incrementar el valor de los productos agregados
                services += 1;
            }
        }

        //metodo para eliminar un servicio del DOM
        //numberService - numero que identifica al servicio dentro del DOM
        function deleteService(numberService) {
            // console.log("Eliminando servicio " + numberService);
            //eliminando el elemento
            //se elimina el div que contien todo la informacion del producto
            $("#service" + numberService).detach();
            calcularPrecioFinal();
        }

        function valitateRecord() {
            var entry_date = $('#entry-date-record').val().trim();
            var departure_date = $('#departure-date-record').val().trim();
            var plate = $('#id-vehicle-record').val().trim();
            var mileage = $('#mileage-record').val().trim();
            var cc = $('#id-customer-record').val().trim();
            // var name = $('#name-record').val().trim();
            // var last_name = $('#last-name-record').val().trim();
            // var phone = $('#phone-record').val().trim();
            var cc_employee = $('#id-employee-record').val().trim();

            // if (entry_date !== "" && departure_date !== "" && plate !== "-1" && mileage !== "" && cc !== "-1" && name !== "" && last_name !== "" && phone !== "" && cc_employee !== "-1") {
            if (entry_date !== "" && departure_date !== "" && plate !== "-1" && mileage !== "" && cc !== "-1" && cc_employee !== "-1") {
                var services = $(".row-services").children();
                var count = services.length;
                console.log(count);
                //validando los servicios agregados
                //en caso de ser un solo servicio se valida
                //en caso de haber mas de 1 valida todos excepto el ultimo
                if (count === 1 && validateServices(0)) {
                    var products = $(".row-products").children();
                    var countP = products.length;
                    if (countP === 1 && valiteProducts(0)) {
                        $('#modal-add-record #form-add-record').submit();
                    } else if (countP !== 1 && valiteProducts(1)) {
                        //borra el ultimo de productos
                        $(products[countP - 1]).detach();
                        $('#modal-add-record #form-add-record').submit();
                        
                    }
                }else if (count !== 1 && validateServices(1)) {
                    var products = $(".row-products").children();
                    var countP = products.length;
                    if (countP === 1 && valiteProducts(0)) {
                        $(services[count - 1]).detach();
                        $('#modal-add-record #form-add-record').submit();
                    } else if (countP !== 1 && valiteProducts(1)) {
                        //borra el ultimo de productos
                        $(products[countP - 1]).detach();
                        $(services[count - 1]).detach();
                        $('#modal-add-record #form-add-record').submit();                        
                    }
                }
               
            }
            if (entry_date === "") {
                $('.msg-error-entry-date').css('display', 'block');
            } else {
                $('.msg-error-entry-date').css('display', 'none');
            }
            if (departure_date === "") {
                $('.msg-error-departure-date').css('display', 'block');
            } else {
                $('.msg-error-departure-date').css('display', 'none');
            }
            if (plate === "-1") {
                $('.msg-error-plate').css('display', 'block');
            } else {
                $('.msg-error-plate').css('display', 'none');
            }
            if (mileage === "") {
                $('.msg-error-mileage').css('display', 'block');
            } else {
                $('.msg-error-mileage').css('display', 'none');
            }
            if (cc === "-1") {
                $('.msg-error-cc').css('display', 'block');
            } else {
                $('.msg-error-cc').css('display', 'none');
            }
            // if (name === "") {
            //     $('.msg-error-name').css('display', 'block');
            // } else {
            //     $('.msg-error-name').css('display', 'none');
            // }
            // if (last_name === "") {
            //     $('.msg-error-last-name').css('display', 'block');
            // } else {
            //     $('.msg-error-last-name').css('display', 'none');
            // }
            // if (phone === "") {
            //     $('.msg-error-phone').css('display', 'block');
            // } else {
            //     $('.msg-error-phone').css('display', 'none');
            // }
            if (cc_employee === "-1") {
                $('.msg-error-cc-employee').css('display', 'block');
            } else {
                $('.msg-error-cc-employee').css('display', 'none');
            }
        }

        //metodo que valida el formulario del producto
        //num - numero que se le resta a length
        //esto con el fin de omitir validar el ultimo formulario de producto
        //num = 0 - valide todos
        //num = 1 - valide todos menos el ultimo
        function valiteProducts(num) {
            //hijos dentro del contenedor divS
            var products = $(".row-products").children();
            var length = (products.length - num);
            
            for (i = 0; i < length; i++) {
                var idProduct = products[i].getAttribute('id').substring(7);
                // console.log("product id: " + idProduct);
                var id = $('#name-product-record' + (idProduct)).val().trim();
                var amount = $('#amount-product-record' + (idProduct)).val().trim();
                var price = $('#price-product-record' + (idProduct)).val().trim();
                if (id !== "-1" && amount !== "" && price !== "") {
                    // console.log(id, amount);
                    length -= 1;
                }
                if (id === "-1") {
                    $('#msg-error-name-product' + (idProduct)).css('display', 'block');
                } else {
                    $('#msg-error-name-product' + (idProduct)).css('display', 'none');
                }
                
                if (amount === "") {
                    $('#msg-error-amount-product' + (idProduct)).css('display', 'block');
                } else {
                    $('#msg-error-amount-product' + (idProduct)).css('display', 'none');
                }
                if (price === "") {
                    $('#msg-error-price-product' + (idProduct)).css('display', 'block');
                } else {
                    $('#msg-error-price-product' + (idProduct)).css('display', 'none');
                }
            }

            calcularPrecioFinal();
            if (length === 0) {
                // console.log(length);
                return true;
            } else {
                return false;
            }
        }

        //metodo que valida el formulario del servicio
        //num - numero que se le resta a length
        //esto con el fin de omitir validar el ultimo formulario de producto
        //num = 0 - valide todos
        //num = 1 - valide todos menos el ultimo
        function validateServices(num) {
            //hijos dentro del contenedor div
            var service = $(".row-services").children();
            var length = (service.length - num);
            console.log("leng:",length);
            for (i = 0; i < length; i++) {
                var idService = service[i].getAttribute('id').substring(7);
                // console.log("service id: " + idService);
                var name = $('#service-name-record' + (idService)).val().trim();
                var description = $('#service-description-record' + (idService)).val().trim();
                var price = $('#service-price-record' + (idService)).val().trim();
                if (name !== "" && description !== "" && price !== "") {
                    // console.log(name, description);
                    length -= 1;
                }
                if (name === "") {
                    $('#msg-error-service-name' + (idService)).css('display', 'block');
                } else {
                    $('#msg-error-service-name' + (idService)).css('display', 'none');
                }
                if (description === "") {
                    $('#msg-error-service-description' + (idService)).css('display', 'block');
                } else {
                    $('#msg-error-service-description' + (idService)).css('display', 'none');
                }
                if (price === "") {
                    $('#msg-error-service-price' + (idService)).css('display', 'block');
                } else {
                    $('#msg-error-service-price' + (idService)).css('display', 'none');
                }
            }
            calcularPrecioFinal();
            if (length === 0) {
                // console.log(length);
                return true;
            } else {
                return false;
            }

        }
    </script>
    @endsection