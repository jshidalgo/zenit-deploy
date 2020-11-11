@extends('home')
@section('content')
<section id="view-customer">
    <div class="text-intro">
        <h1>Gestión de clientes</h1>
        <span>Agrega, actualiza o elimina registros de clientes</span>
    </div>
    <div id="actions-bar">
        <form id="form-search-customer" method="GET" action="{{route('view_customer')}}">
            <input id="input-search" name="dat[search]" type="text" placeholder="Buscar">
            <button type="submit" class="btn-search" onclick="validateSearchCustomer()"><i class="fa fa-search"></i></button>
        </form>

        <div id="actions-buttons">
            <button type="button" onclick="show_edit_customer()"><i class="far fa-edit"></i></button>
            <button onclick="remove_customer()" ><i class="fas fa-trash-alt"></i></button>
            <button type="button" data-toggle="modal" data-target="#modal-add-customer"><i class="fas fa-plus"></i></button>
        </div>
    </div>
    <div id="section-table">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <td class="col-check"></td>
                        <th scope="col">ID</th>
                        <th scope="col">Cédula</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Dirección</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td class="col-check"></td>
                        <th scope="col">ID</th>
                        <th scope="col">Cédula</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Dirección</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($customer as $aux)
                    <tr>
                        <td class="col-check"><input type="checkbox"></td>
                        <td>{{$aux->id}}</td>
                        <td>{{$aux->identification_card}}</td>
                        <td>{{$aux->name}}</td>
                        <td>{{$aux->last_name}}</td>
                        <td>NN</td>
                        <td>{{$aux->mail}}</td>
                        <td>{{$aux->address}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <!--Modal Agregar-->
    <div id="modal-add-customer" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Agregar datos del cliente</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-add-customer" method="post" action="{{route('add_customer')}}">
                        @csrf
                        <div class="row">
                            <label for="cc-customer">Cédula</label>
                            <input name="dat[cc]" id="cc-customer" type="text" placeholder="Número cédula">
                            <span class="msg-error-cc">Ingrese el número de cédula</span>
                        </div>
                        <div class="row">
                            <label for="name-customer">Nombre</label>
                            <input name="dat[name]" id="name-customer" type="text" placeholder="Nombre cliente">
                            <span class="msg-error-name">Ingrese el nombre del cliente</span>
                        </div>
                        <div class="row">
                            <label for="last-name-customer">Apellidos</label>
                            <input name="dat[lastName]" id="last-name-customer" type="text" placeholder="Apellidos cliente">
                            <span class="msg-error-last-name">Ingrese el o los apellidos del cliente</span>
                        </div>
                        <div class="row">
                            <label for="phone-customer">Teléfono</label>
                            <input name="dat[phone]" id="phone-customer" type="text" placeholder="Teléfono">
                            <span class="msg-error-phone">Ingrese un número de teléfono</span>
                        </div>
                        <div class="row">
                            <label for="mail-customer">Correo</label>
                            <input name="dat[mail]" id="mail-customer" type="email" placeholder="Correo">
                            <span class="msg-error-mail">Ingrese un correo electrónico</span>
                            <span class="msg-error-invalid-mail">Ingrese un correo electrónico valido</span>
                        </div>
                        <div class="row">
                            <label for="address-customer">Dirección</label>
                            <input name="dat[address]" id="address-customer" type="text" placeholder="Dirección">
                            <span class="msg-error-address">Ingrese una dirección</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldCustomer()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-customer" onclick="validateFormAddCustomer()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!---Modal Editar-->
    <div id="modal-edit-customer" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar datos del cliente</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-edit-customer" method="POST" action="{{route('edit_customer')}}">
                        @csrf
                        <div class="row">
                            <label for="id-customer-edit">ID</label>
                            <input name="dat[id]" id="id-customer-edit" type="text" placeholder="ID" readonly>
                        </div>
                        <div class="row">
                            <label for="cc-customer-edit">Cédula cliente</label>
                            <input name="dat[cc]" id="cc-customer-edit" type="text" placeholder="Cédula">
                            <span class="msg-error-cc-edit">Ingrese una cédula</span>
                        </div>
                        <div class="row">
                            <label for="name-customer-edit">Nombre</label>
                            <input name="dat[name]" id="name-customer-edit" type="text" placeholder="Nombre">
                            <span class="msg-error-name-edit">Ingrese un nombre</span>
                        </div>
                        <div class="row">
                            <label for="last-name-customer-edit">Apellidos</label>
                            <input  name="dat[last_name]" id="last-name-customer-edit" type="text" placeholder="Apellidos">
                            <span class="msg-error-last-name-edit">Ingrese los apellidos</span>
                        </div>
                        <div class="row">
                            <label for="phone-customer-edit">Télefono</label>
                            <input name="dat[phone]" id="phone-customer-edit" type="text" placeholder="Teléfono">
                            <span class="msg-error-phone-edit">Ingrese un teléfono</span>
                        </div>
                        <div class="row">
                            <label for="addr-customer-edit">Dirección</label>
                            <input name="dat[address]" id="addr-customer-edit" type="text"  placeholder="Dirección">
                            <span class="msg-error-address-edit">Ingrese una Dirección</span>
                        </div>
                        <div class="row">
                            <label for="mail-customer-edit">Correo</label>
                            <input name="dat[mail]" id="mail-customer-edit" type="text" placeholder="Correo electrónico">
                            <span class="msg-error-mail-edit">Ingrese un correo electrónico</span>
                            <span class="msg-error-invalid-mail-edit">Ingrese un correo electrónico valido</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldCustomer()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-customer" onclick="validateFormEditCustomer()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    //mensajes de alerta
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

    /**
     * Función que elimina clientes
     */
    function remove_customer() {
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[1].innerHTML));
        console.log(selected);
        if(selected.length >= 1){
            $.ajax({
                type:'delete',
                url:'clientes',
                data:{
                    _token:'{{csrf_token()}}',
                    selected: selected
                }
            }).done(function(data) {
                if(data==1){
                    Swal.fire(
                        'Se completo la operación con éxito',
                        'Se eliminaron los clientes seleccionados',
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
