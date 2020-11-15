@extends('home')
@section('content')
<section id="view-product">
    <div class="text-intro">
        <h1>Gestión de productos</h1>
        <span>Agrega, actualiza o elimina registros de productos</span>
    </div>
    <div id="actions-bar">
        <form id="form-search-product" method="GET" action="{{route('view_product')}}">
            <input id="input-search" name="dat[search]" type="text" placeholder="Buscar">
            <button type="submit" class="btn-search" onclick="validateSearchProduct()"><i class="fa fa-search"></i></button>
        </form>

        <div id="actions-buttons">
            <button type="button" onclick="show_edit_product()"><i class="far fa-edit"></i></button>
            <button onclick="remove_product()" ><i class="fas fa-trash-alt"></i></button>
            <button type="button" data-toggle="modal" data-target="#modal-add-product"><i class="fas fa-plus"></i></button>
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
                        <th scope="col">Nombre</th>
                        <th scope="col">Valor Unidad</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Proveedor</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td class="col-check"></td>
                        <th scope="col">ID</th>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Valor Unidad</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Proveedor</th>
                    </tr>
                </tfoot>
                <tbody>
                @foreach($product as $aux)
                    <tr>
                        <td class="col-check"><input type="checkbox"></td>
                        <td>{{$aux->id}}</td>
                        <td>{{$aux->code}}</td>
                        <td>{{$aux->name}}</td>
                        <td>{{$aux->sale_price}}</td>
                        <td>{{$aux->units_available}}</td>
                        <td>{{$aux->description}}</td>
                        <td>{{$aux->provider_name}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <!-- modal agregar -->
    <div id="modal-add-product" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Agregar datos del producto</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="form-add-product" method="post" action="{{route('add_product')}}">
                        @csrf
                        <div class="row">
                            <label for="cod-product">Código</label>
                            <input name="dat[cod]" id="cod-product" type="text" placeholder="Código producto">
                            <span class="msg-error-cod">Ingrese el código del producto</span>
                        </div>
                        <div class="row">
                            <label for="name-product">Nombre</label>
                            <input name="dat[name]" id="name-product" type="text" placeholder="Nombre">
                            <span class="msg-error-name">Ingrese un nombre del producto</span>
                        </div>
                        <div class="row">
                            <label for="sale-price-product">Valor venta de unidad</label>
                            <input name="dat[price]" id="sale-price-product" type="number" placeholder="Valor unitario de venta del producto" min='0'>
                            <span class="msg-error-cost">Ingrese el valor de venta de la unidad del producto</span>
                        </div>
                        <div class="row">
                            <label for="amount-product">Cantidad</label>
                            <input name="dat[amount]" id="amount-product" type="number" placeholder="Cantidad producto disponible" min='1'>
                            <span class="msg-error-amount">Ingrese la cantidad de producto</span>
                        </div>
                        <div class="row">
                            <label for="description-product">Descripción</label>
                            <textarea name="dat[description]" class="form-control" id="description-product" placeholder="Descripcón del producto, información de interes" rows="3"></textarea>
                            <span class="msg-error-description">Ingrese una descripción</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldProduct()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-product" onclick="validateFormProduct('')">Agregar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- modal edidar -->
    <div id="modal-edit-product" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar datos del producto</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="form-edit-product" method="post" action="{{route('edit_product')}}">
                        @csrf
                        <div class="row">
                            <label for="id-product-edit">ID</label>
                            <input name="dat[id]" id="id-product-edit" type="text" placeholder="ID" class="readonly" readonly>
                        </div>
                        <div class="row">
                            <label for="cod-product-edit">Código</label>
                            <input name="dat[cod]" id="cod-product-edit" type="text" placeholder="Código producto">
                            <span class="msg-error-cod-edit">Ingrese el código del producto</span>
                        </div>
                        <div class="row">
                            <label for="name-product-edit">Nombre</label>
                            <input name="dat[name]" id="name-product-edit" type="text" placeholder="Nombre">
                            <span class="msg-error-name-edit">Ingrese un nombre del producto</span>
                        </div>
                        <div class="row">
                            <label for="sale-price-product-edit">Valor venta de unidad</label>
                            <input name="dat[price]" id="sale-price-product-edit" type="number" placeholder="Valor unitario de venta del producto" min='0'>
                            <span class="msg-error-cost-edit">Ingrese el valor de venta de la unidad del producto</span>
                        </div>
                        <div class="row">
                            <label for="amount-product-edit">Cantidad</label>
                            <input name="dat[amount]" id="amount-product-edit" type="number" placeholder="Cantidad producto disponible" min='1'>
                            <span class="msg-error-amount-edit">Ingrese la cantidad de producto</span>
                        </div>
                        <div class="row">
                            <label for="description-product-edit">Descripción</label>
                            <textarea name="dat[description]" class="form-control" id="description-product-edit" placeholder="Descripcón del producto, información de interes" rows="3"></textarea>
                            <span class="msg-error-description-edit">Ingrese una descripción</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldProduct()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-product" onclick="validateFormProduct('-edit')">Actualizar</button>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>

  /**
     * Función que elimina productos
     */
    function remove_product() {
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[1].innerHTML));
        console.log(selected);
        if(selected.length >= 1){
            $.ajax({
                type:'delete',
                url:'productos',
                data:{
                    _token:'{{csrf_token()}}',
                    selected: selected
                }
            }).done(function(data) {
                if(data==1){
                    Swal.fire(
                        'Se completo la operación con éxito',
                        'Se eliminaron los productos seleccionados',
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

    //mensajes de alerta
    var check_msg = '{{isset($check_msg) && !empty($check_msg) && $check_msg!='' ? $check_msg : ''}}';
    var fail_msg = '{{isset($fail_msg) && !empty($fail_msg) && $fail_msg!='' ? $fail_msg : ''}}';

    if(check_msg !== ''){
        Swal.fire(
            'Se completo la operación con éxito',
            check_msg,
            'success'
        )
    }
    if(fail_msg){
        Swal.fire({
            icon: 'error',
            title: 'Ocurrió un error!',
            text: fail_msg
        });
    }
</script>
@endsection
