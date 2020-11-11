@extends('home')
@section('content')
<section id="view-purchase">
    <div class="text-intro">
        <h1>Gestión de compras</h1>
        <span>Agrega, actualiza o elimina registros de compras</span>
    </div>
    <div id="actions-bar">
        <form id="form-search-purchase" method="GET" action="{{route('view_purchase')}}">
            <input id="input-search" name="dat[search]" type="text" placeholder="Buscar">
            <button type="submit" class="btn-search" onclick="validateSearchPurchase()"><i class="fa fa-search"></i></button>
        </form>

        <div id="actions-buttons">
            <button type="button" onclick="show_edit_purchase()"><i class="far fa-edit"></i></button>
            <button onclick="remove_purchase()" ><i class="fas fa-trash-alt"></i></button>
            <button type="button" data-toggle="modal" data-target="#modal-add-purchase"><i class="fas fa-plus"></i></button>
        </div>
    </div>
    <div id="section-table">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <td class="col-check"></td>
                        <th scope="col">ID</th>
                        <th scope="col">Código</th>
                        <th scope="col">Fecha compra</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Proveedor</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td class="col-check"></td>
                        <th scope="col">ID</th>
                        <th scope="col">Código</th>
                        <th scope="col">Fecha compra</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Proveedor</th>
                    </tr>
                </tfoot>
                <tbody>

                    @foreach($data[0] as $aux)
                    <tr>
                        <td class="col-check"><input type="checkbox"></td>
                        <td>{{$aux->id}}</td>
                        <td>{{$aux->cod}}</td>
                        <td>{{date("d/m/Y", strtotime($aux->date))}}</td>
                        <td>{{$aux->cost}}</td>
                        <td>{{$aux->concept}}</td>
                        <td>{{(($aux->status)=="NoPago")?"Pendiente de pago":"Pago"}}</td>
                        <td>{{$aux->provider_name}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <!-- modal agregar -->
    <div id="modal-add-purchase"  class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Agregar datos de la compra</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="form-add-purchase" method="post" action="{{route('add_purchase')}}">
                        @csrf
                        <div class="row">
                            <label for="cod-purchase">Código</label>
                            <input name="dat[cod]" id="cod-purchase" type="text" placeholder="Código de compra">
                            <span class="msg-error-cod">Ingrese el código del producto</span>
                        </div>

                        <div class="row">
                            <label for="date-purchase">Fecha</label>
                            <input name="dat[date]" id="date-purchase" type="date" placeholder="dd/mm/aaaa" required pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}">
                            <span class="msg-error-date">Ingrese una fecha</span>
                        </div>

                        <div class="row">
                            <label for="costU-purchase">Valor de compra</label>
                            <input name="dat[cost]" id="costU-purchase" type="number" placeholder="Valor de la compra" min='0'>
                            <span class="msg-error-cost">Ingrese el valor de la compra</span>
                        </div>
                        <div class="row">
                            <label for="concept-purchase">Descripción</label>
                            <textarea name="dat[concept]" id="concept-purchase" placeholder="Descripción o motivo de la compra" rows="3"></textarea>
                            <span class="msg-error-concept">Ingrese el concepto de la compra</span>
                        </div>
                        <div class="row">
                            <label for="status-purchase">Estado</label>
                            <select class="form-control" id="status-purchase" name="dat[status]">
                                <option value="-1" selected>Seleccione un estado</option>
                                <option value="Pago">Pago</option>
                                <option value="NoPago">Pendiente de pago</option>
                            </select>
                            <span class="msg-error-status">Seleccione un estado</span>
                        </div>
                        <div class="row">
                            <label for="provider-purchase">Proveedor</label>
                            <select class="form-control" id="provider-purchase" name="dat[provider]">
                                <option value="-1" selected>Seleccione un proveedor</option>
                            </select>
                            <span class="msg-error-provider">Seleccione un proveedor</span>

                        </div>
                        <div class="row"><b><label for="name-product-purchase1">Productos</label></b>
                        </div>

                        <div class="row-products" id="row-products-add">
                            <!-- esto es lo que se repite -->
                            <div class="row-product" id="product1">
                                <div class="row">
                                    <label for="name-product-purchase1">Nombre producto</label>
                                    <input name="product[nameProduct1]" id="name-product-purchase1" type="text" placeholder="Nombre del producto">
                                    <span class="msg-error-name-product" id='msg-error-name-product1'>Ingrese el nombre del producto</span>
                                </div>
                                <div class="row">
                                    <label for="costU-product-purchase1">Valor de costo unidad</label>
                                    <input name="product[costProduct1]" id="costU-product-purchase1" type="number" placeholder="Valor de costo unitario del producto" min='0'>
                                    <span class="msg-error-cost-product" id="msg-error-cost-product1">Ingrese el valor de costo unidad del producto</span>
                                </div>
                                <div class="row">
                                    <label for="amount-product-purchase1">Cantidad</label>
                                    <input name="product[amountProduct1]" id="amount-product-purchase1" type="number" placeholder="Cantidad producto disponible" min='1'>
                                    <span class="msg-error-amount-product" id="msg-error-amount-product1">Ingrese la cantidad de producto</span>
                                </div>
                                <div class="modal-footer">
                                    <i class="fas fa-plus-circle fa-2x" onclick="addProductPurchase('add')" id='product-add1'></i>
                                    <i class="fas fa-minus-circle fa-2x" id='product-del1' onclick="deleteProductPurchase(1)"></i>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearField()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-purchase" onclick="validateFormPurchase('add')">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- modal editar -->
    <div id="modal-edit-purchase"  class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar datos de la compra</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="form-edit-purchase" method="post" action="{{route('edit_purchase')}}">
                        @csrf
                        <div class="row">
                            <label for="id-purchase-edit">ID</label>
                            <input name="dat[id]" id="id-purchase-edit" type="text" placeholder="ID" readonly>
                        </div>
                        <div class="row">
                            <label for="cod-edit-purchase">Código</label>
                            <input name="dat[cod]" id="cod-edit-purchase" type="text" placeholder="Código de compra">
                            <span class="msg-error-cod-edit">Ingrese el código del producto</span>
                        </div>

                        <div class="row">
                            <label for="date-edit-purchase">Fecha</label>
                            <input name="dat[date]" id="date-edit-purchase" type="date" placeholder="dd/mm/aaaa" required pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}">
                            <span class="msg-error-date-edit">Ingrese una fecha</span>
                        </div>

                        <div class="row">
                            <label for="costU-edit-purchase">Valor de compra</label>
                            <input name="dat[cost]" id="costU-edit-purchase" type="number" placeholder="Valor de la compra" min='0'>
                            <span class="msg-error-cost-edit">Ingrese el valor de la compra</span>
                        </div>
                        <div class="row">
                            <label for="concept-edit-purchase">Descripción</label>
                            <textarea name="dat[concept]" id="concept-edit-purchase" placeholder="Descripción o motivo de la compra" rows="3"></textarea>
                            <span class="msg-error-concept-edit">Ingrese el concepto de la compra</span>
                        </div>
                        <div class="row">
                            <label for="status-edit-purchase">Estado</label>
                            <select class="form-control" id="status-edit-purchase" name="dat[status]">
                                <option value="-1" selected>Seleccione un estado</option>
                                <option value="Pago">Pago</option>
                                <option value="NoPago">Pendiente de pago</option>
                            </select>
                            <span class="msg-error-status-edit">Seleccione un estado</span>
                        </div>
                        <div class="row">
                            <label for="provider-edit-purchase">Proveedor</label>
                            <select class="form-control" id="provider-edit-purchase" name="dat[provider]">
                                <option value="-1" selected>Seleccione un proveedor</option>
                            </select>
                            <span class="msg-error-provider-edit">Seleccione un proveedor</span>

                        </div>
                        <div class="row"><b><label for="name-product-edit-purchase1">Productos</label></b>
                        </div>

                        <div class="row-products" id="row-products-edit">
                            <!-- esto es lo que se repite -->
                            <div class="row-product" id="product1">
                                <div class="row">
                                    <label for="name-product-edit-purchase1">Nombre producto</label>
                                    <input name="product[nameProduct1]" id="name-product-edit-purchase1" type="text" placeholder="Nombre del producto">
                                    <span class="msg-error-name-product-edit" id='msg-error-name-edit-product1'>Ingrese el nombre del producto</span>
                                </div>
                                <div class="row">
                                    <label for="costU-product-edit-purchase1">Valor de costo unidad</label>
                                    <input name="product[costProduct1]" id="costU-product-edit-purchase1" type="number" placeholder="Valor de costo unitario del producto" min='0'>
                                    <span class="msg-error-cost-product-edit" id="msg-error-cost-edit-product1">Ingrese el valor de costo unidad del producto</span>
                                </div>
                                <div class="row">
                                    <label for="amount-product-edit-purchase1">Cantidad</label>
                                    <input name="product[amountProduct1]" id="amount-product-edit-purchase1" type="number" placeholder="Cantidad producto disponible" min='1'>
                                    <span class="msg-error-amount-product-edit" id="msg-error-amount-edit-product1">Ingrese la cantidad de producto</span>
                                </div>
                                <div class="modal-footer">
                                    <i class="fas fa-plus-circle fa-2x" onclick="addProductPurchase('edit')" id='product-add1'></i>
                                    <i class="fas fa-minus-circle fa-2x" id='product-del1' onclick="deleteProductPurchase(1)"></i>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearField()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-purchase" onclick="validateFormPurchase('edit')">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>

     /**
     * Función que elimina una compra
     */
    function remove_purchase() {
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[1].innerHTML));
        console.log(selected);
        if(selected.length >= 1){
            $.ajax({
                type:'delete',
                url:'compras',
                data:{
                    _token:'{{csrf_token()}}',
                    selected: selected
                }
            }).done(function(data) {
                if(data==1){
                    Swal.fire(
                        'Se completo la operación con éxito',
                        'Se eliminaron las compras seleccionados',
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

    /**
     * Función que limpia todos los campos disponibles en las compras
     **/
    function clearFieldPurchase() {
        document.querySelectorAll('#modal-edit-purchase input, #modal-add-purchase input').forEach(function (element) {
            element.value="";
        });
    }

    //mensajes de respuesta
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

    //agrega la información de los proveedores registrados
    var proveedores = @json($data[1]); // no es un error
    proveedores.forEach(element => {
        $('#provider-purchase').append($('<option />', {
            text: element.name,
            value: element.id,
        }));
        $('#provider-edit-purchase').append($('<option />', {
            text: element.name,
            value: element.id,
        }));
    });




</script>
@endsection
