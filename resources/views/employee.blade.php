@extends('home')
@section('content')
<section id="view-employee">
    <div class="text-intro">
        <h1>Gestión de empleados</h1>
        <span>Agrega, actualiza o elimina registros de empleados</span>
    </div>
    <div id="actions-bar">
        <form id="form-search-employee" method="get" action="{{route('view_employee')}}">
            <input id="input-search" name="dat[search]" type="text" placeholder="Buscar">
            <button type="submit" class="btn-search" onclick="validateSearchEmployee()"><i class="fa fa-search"></i></button>
        </form>

        <div id="actions-buttons">
            <button type="button" onclick="show_edit_employee()"><i class="far fa-edit"></i></button>
            <button onclick="remove_employee()" ><i class="fas fa-trash-alt"></i></button>
            <button type="button" data-toggle="modal" data-target="#modal-add-employee"><i class="fas fa-plus"></i></button>
        </div>
    </div>
    <div id="section-table">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                <tr>
                    <th scope="col" class="col-check"></th>
                    <th scope="col">ID</th>
                    <th scope="col">Cédula</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Télefono</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Correo</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th scope="col" class="col-check"></th>
                    <th scope="col">ID</th>
                    <th scope="col">Cédula</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Télefono</th>
                    <th scope="col">Dirección</th>
                    <th scope="col">Correo</th>
                </tr>
                </tfoot>
                <tbody>

                @foreach($employees as $aux)

                    <tr>
                        <td class="col-check"><input type="checkbox"></td>
                        <td>{{$aux->id}}</td>
                        <td>{{$aux->identification_card}}</td>
                        <td>{{$aux->name}}</td>
                        <td>{{$aux->last_name}}</td>
                        <td>{{$aux->number}}</td>
                        <td>{{$aux->address}}</td>
                        <td>{{$aux->mail}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <!--Modal agregar-->
    <div id="modal-add-employee" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Agregar datos del cliente</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="form-add-employee" method="POST" action="{{route('add_employee')}}">
                        @csrf
                        <div class="row">
                            <label for="cc-employee">Cédula cliente</label>
                            <input name="dat[cc]" id="cc-employee" type="text" placeholder="Cédula">
                            <span class="msg-error-cc">Ingrese una cédula</span>
                        </div>
                        <div class="row">
                            <label for="name-employee">Nombre</label>
                            <input name="dat[name]" id="name-employee" type="text" placeholder="Nombre">
                            <span class="msg-error-name">Ingrese un nombre</span>
                        </div>
                        <div class="row">
                            <label for="last-name-employee">Apellidos</label>
                            <input  name="dat[last_name]" id="last-name-employee" type="text" placeholder="Apellidos">
                            <span class="msg-error-last-name">Ingrese los apellidos</span>
                        </div>
                        <div class="row">
                            <label for="phone-employee">Télefono</label>
                            <input name="dat[phone]" id="phone-employee" type="text" placeholder="Teléfono">
                            <span class="msg-error-phone">Ingrese un teléfono</span>
                        </div>
                        <div class="row">
                            <label for="addr-employee">Dirección</label>
                            <input name="dat[address]" id="addr-employee" type="text"  placeholder="Dirección">
                            <span class="msg-error-address">Ingrese una Dirección</span>
                        </div>
                        <div class="row">
                            <label for="mail-employee">Correo</label>
                            <input name="dat[mail]" id="mail-employee" type="text" placeholder="Correo electrónico">
                            <span class="msg-error-mail">Ingrese un correo electrónico</span>
                            <span class="msg-error-invalid-mail">Ingrese un correo electrónico valido</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldEmployee()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-employee" onclick="validateFormEmployee()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!---Modal Editar-->
    <div id="modal-edit-employee" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar datos del cliente</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-edit-employee" method="POST" action="{{route('edit_employee')}}">
                        @csrf
                        <div class="row">
                            <label for="id-employee-edit">ID</label>
                            <input name="dat[id]" id="id-employee-edit" type="text" placeholder="ID" class="readonly" readonly>
                        </div>
                        <div class="row">
                            <label for="cc-employee-edit">Cédula cliente</label>
                            <input name="dat[cc]" id="cc-employee-edit" type="text" placeholder="Cédula">
                            <span class="msg-error-cc-edit">Ingrese una cédula</span>
                        </div>
                        <div class="row">
                            <label for="name-employee-edit">Nombre</label>
                            <input name="dat[name]" id="name-employee-edit" type="text" placeholder="Nombre">
                            <span class="msg-error-name-edit">Ingrese un nombre</span>
                        </div>
                        <div class="row">
                            <label for="last-name-employee-edit">Apellidos</label>
                            <input  name="dat[last_name]" id="last-name-employee-edit" type="text" placeholder="Apellidos">
                            <span class="msg-error-last-name-edit">Ingrese los apellidos</span>
                        </div>
                        <div class="row">
                            <label for="phone-employee-edit">Télefono</label>
                            <input name="dat[phone]" id="phone-employee-edit" type="text" placeholder="Teléfono">
                            <span class="msg-error-phone-edit">Ingrese un teléfono</span>
                        </div>
                        <div class="row">
                            <label for="addr-employee-edit">Dirección</label>
                            <input name="dat[address]" id="addr-employee-edit" type="text"  placeholder="Dirección">
                            <span class="msg-error-address-edit">Ingrese una Dirección</span>
                        </div>
                        <div class="row">
                            <label for="mail-employee-edit">Correo</label>
                            <input name="dat[mail]" id="mail-employee-edit" type="text" placeholder="Correo electrónico">
                            <span class="msg-error-mail-edit">Ingrese un correo electrónico</span>
                            <span class="msg-error-invalid-mail-edit">Ingrese un correo electrónico valido</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldEmployee()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-employee" onclick="validateFormEditEmployee()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    /**
     * Función que permite eliminar los empleados seleccionados
     */
    function remove_employee() {
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[2].innerHTML));
        console.log(selected);
        if(selected.length >= 1){
            $.ajax({
                type:'delete',
                url:'empleados',
                data:{
                    _token:'{{csrf_token()}}',
                    selected: selected
                }
            }).done(function(data) {
                if(data==1){
                    Swal.fire(
                        'Se completo la operación con éxito',
                        'Se eliminaron los empleados seleccionados',
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
