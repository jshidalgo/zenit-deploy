@extends('home')
@section('content')
<section id="view-provider">
    <div class="text-intro">
        <h1>Gestión de proveedores</h1>
        <span>Agrega, actualiza o elimina registros de proveedores</span>
    </div>
    <div id="actions-bar">
        <form id="form-search-provider" method="GET" action="{{route('view_provider')}}">
            <input id="input-search" name="dat[search]" type="text" placeholder="Buscar">
            <button type="submit" class="btn-search" onclick="validateSearchProvider()"><i class="fa fa-search"></i></button>
        </form>

        <div id="actions-buttons">
            <button type="button" onclick="show_edit_provider()"><i class="far fa-edit"></i></button>
            <button onclick="remove_provider()" ><i class="fas fa-trash-alt"></i></button>
            <button type="button" data-toggle="modal" data-target="#modal-add-provider"><i class="fas fa-plus"></i></button>
        </div>
    </div>
    <div id="section-table">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <td class="col-check"></td>
                        <th scope="col">ID</th>
                        <th scope="col">Nit</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">País</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col">Dirección</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td class="col-check"></td>
                        <th scope="col">ID</th>
                        <th scope="col">Nit</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">País</th>
                        <th scope="col">Departamento</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col">Dirección</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($provider as $aux)

                    <tr>
                        <td class="col-check"><input type="checkbox"></td>
                        <td>{{$aux->id}}</td>
                        <td>{{$aux->nit}}</td>
                        <td>{{$aux->name}}</td>
                        <td>{{$aux->mail}}</td>
                        <td>{{$aux->number}}</td>
                        <td>{{$aux->country}}</td>
                        <td>{{$aux->departament}}</td>
                        <td>{{$aux->city}}</td>
                        <td>{{$aux->address}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <!-- modal agregar -->
    <div id="modal-add-provider" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Agregar datos del proveedor</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="form-add-provider" method="post" action="{{route('add_provider')}}">
                        @csrf
                        <div class="row">
                            <label for="nit-provider">Nit</label>
                            <input name="dat[nit]" id="nit-provider" type="text" placeholder="Nit">
                            <span class="msg-error-nit">Ingrese el Nit del proveedor</span>
                        </div>
                        <div class="row">
                            <label for="name-provider">Nombre</label>
                            <input name="dat[name]" id="name-provider" type="text" placeholder="Nombre proveedor">
                            <span class="msg-error-name">Ingrese el nombre del proveedor</span>
                        </div>
                        <div class="row">
                            <label for="mail-provider">Correo</label>
                            <input name="dat[mail]" id="mail-provider" type="email" placeholder="Correo electrónico">
                            <span class="msg-error-mail">Ingrese un correo electrónico</span>
                            <span class="msg-error-invalid-mail">Ingrese un correo electrónico valido</span>
                        </div>
                        <div class="row">
                            <label for="phone-provider">Télefono</label>
                            <input name="dat[phone]" id="phone-provider" type="text" placeholder="Teléfono">
                            <span class="msg-error-phone">Ingrese un número de teléfono</span>
                        </div>
                        <!-- país, departamento y cidudad sale una sugerencia segun lo que este en el BD -->
                        <div class="row">
                            <label for="country-provider">País</label>
                            <input id="country-provider" name="dat[country]" placeholder="País">
                            <span class="msg-error-country">Ingrese un país</span>
                        </div>
                        <div class="row">
                            <label for="departament-provider">Departamento</label>
                            <input name="dat[departament]" id="departament-provider" type="text" placeholder="Departamento">
                            <span class="msg-error-departament">Ingrese un departamento</span>
                        </div>
                        <div class="row">
                            <label for="city-provider">Ciudad</label>
                            <input name="dat[city]" id="city-provider" type="text" placeholder="Ciudad">
                            <span class="msg-error-city">Ingrese una ciudad</span>
                        </div>
                        <div class="row">
                            <label for="addr-provider">Dirección</label>
                            <input name="dat[address]" id="addr-provider" type="text" placeholder="Dirección">
                            <span class="msg-error-addr">Ingrese una dirección</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldProvider()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-provider" onclick="validateFormProvider('')">Agregar</button>
                </div>
            </div>
        </div>

    </div>
    <!-- modal editar -->
    <div id="modal-edit-provider" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar datos del proveedor</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="form-edit-provider" method="post" action="{{route('edit_provider')}}">
                        @csrf
                        <div class="row">
                            <label for="id-provider-edit">ID</label>
                            <input name="dat[id]" id="id-provider-edit" type="text" placeholder="ID" class="readonly" readonly>
                        </div>
                        <div class="row">
                            <label for="nit-provider-edit">Nit</label>
                            <input name="dat[nit]" id="nit-provider-edit" type="text" placeholder="Nit">
                            <span class="msg-error-nit-edit">Ingrese el Nit del proveedor</span>
                        </div>
                        <div class="row">
                            <label for="name-provider-edit">Nombre</label>
                            <input name="dat[name]" id="name-provider-edit" type="text" placeholder="Nombre proveedor">
                            <span class="msg-error-name-edit">Ingrese el nombre del proveedor</span>
                        </div>
                        <div class="row">
                            <label for="mail-provider-edit">Correo</label>
                            <input name="dat[mail]" id="mail-provider-edit" type="email" placeholder="Correo electrónico">
                            <span class="msg-error-mail-edit">Ingrese un correo electrónico</span>
                            <span class="msg-error-invalid-mail-edit">Ingrese un correo electrónico valido</span>
                        </div>
                        <div class="row">
                            <label for="phone-provider-edit">Télefono</label>
                            <input name="dat[phone]" id="phone-provider-edit" type="text" placeholder="Teléfono">
                            <span class="msg-error-phone-edit">Ingrese un número de teléfono</span>
                        </div>
                        <!-- país, departamento y cidudad sale una sugerencia segun lo que este en el BD -->
                        <div class="row">
                            <label for="country-provider-edit">País</label>
                            <input id="country-provider-edit" name="dat[country]" placeholder="País">
                            <span class="msg-error-country-edit">Ingrese un país</span>
                        </div>
                        <div class="row">
                            <label for="departament-provider-edit">Departamento</label>
                            <input name="dat[departament]" id="departament-provider-edit" type="text" placeholder="Departamento">
                            <span class="msg-error-departament-edit">Ingrese un departamento</span>
                        </div>
                        <div class="row">
                            <label for="city-provider-edit">Ciudad</label>
                            <input name="dat[city]" id="city-provider-edit" type="text" placeholder="Ciudad">
                            <span class="msg-error-city-edit">Ingrese una ciudad</span>
                        </div>
                        <div class="row">
                            <label for="addr-provider-edit">Dirección</label>
                            <input name="dat[address]" id="addr-provider-edit" type="text" placeholder="Dirección">
                            <span class="msg-error-addr-edit">Ingrese una dirección</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldProvider()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-provider" onclick="validateFormProvider('-edit')">Actualizar</button>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script>

    /**
     * Función que elimina proveedores
     */
    function remove_provider() {
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[1].innerHTML));
        console.log(selected);
        if(selected.length >= 1){
            $.ajax({
                type:'delete',
                url:'proveedores',
                data:{
                    _token:'{{csrf_token()}}',
                    selected: selected
                }
            }).done(function(data) {
                if(data==1){
                    Swal.fire(
                        'Se completo la operación con éxito',
                        'Se eliminaron los proveedores seleccionados',
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


    //alertas
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



</script>
@endsection
