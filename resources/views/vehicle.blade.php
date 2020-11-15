@extends('home')
@section('content')
<section id="view-vehicle">
    <div class="text-intro">
        <h1>Gestión de vehículos</h1>
        <span>Agrega, actualiza o elimina registros de vehículos</span>
    </div>
    <div id="actions-bar">
        <form id="form-search-vehicle" method="GET" action="{{route('view_vehicle')}}">
            <input id="input-search" name="dat[search]" type="text" placeholder="Buscar">
            <button type="submit" class="btn-search" onclick=""><i class="fa fa-search"></i></button>
        </form>


        <div id="actions-buttons">
            <button type="button" onclick="show_edit_vehicle()"><i class="far fa-edit"></i></button>
            <button onclick="removeVehicle()"><i class="fas fa-trash-alt"></i></button>
            <button type="button" data-toggle="modal" data-target="#modal-add-vehicle"><i class="fas fa-plus"></i></button>
        </div>
    </div>
    <div id="section-table">
        <div class="table-responsive">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th scope="col" class="col-check"></th>
                        <th scope="col">ID</th>
                        <th scope="col">Matricula</th>
                        <th scope="col">Color</th>
                        <th scope="col">Cilindraje</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Marca</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th scope="col" class="col-check"></th>
                        <th scope="col">ID</th>
                        <th scope="col">Matricula</th>
                        <th scope="col">Color</th>
                        <th scope="col">Cilindraje</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Marca</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($vehicles as $aux)
                    <tr>
                    <td class="col-check"><input type="checkbox"></td>
                        <td>{{$aux->id}}</td>
                        <td>{{$aux->license_plate}}</td>
                        <td>{{$aux->color}}</td>
                        <td>{{$aux->cylinder_capacity}}</td>
                        <td>{{$aux->name}}</td>
                        <td>{{$aux->model}}</td>
                        <td>{{$aux->brand}}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <!--Modal Agregar-->
    <div id="modal-add-vehicle" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Agregar datos del vehículo</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-add-vehicle" method="post" action="{{route('add_vehicle')}}">
                        @csrf
                        <div class="row">
                            <label for="plate-vehicle">Matricula</label>
                            <input name="dat[plate]" id="plate-vehicle" type="text" placeholder="Número de placa">
                            <span class="msg-error-plate">Ingrese el número de placa</span>
                        </div>
                        <div class="row">
                            <label for="color-vehicle">Color</label>
                            <input name="dat[color]" id="color-vehicle" type="text" placeholder="Color">
                            <span class="msg-error-color">Ingrese color de vehículo</span>
                        </div>
                        <div class="row">
                            <label for="cylinder-vehicle">Cilindraje</label>
                            <input name="dat[cylinder]" id="cylinder-vehicle" type="number" placeholder="Cilindraje" min=0>
                            <span class="msg-error-cylinder">Ingrese un cilindraje</span>
                        </div>
                        <div class="row">
                            <label for="name-vehicle">Nombre</label>
                            <input name="dat[name]" id="name-vehicle" type="text" placeholder="Nombre, ej: GT">
                            <span class="msg-error-name">Ingrese un nombre de vehículo</span>
                        </div>
                        <div class="row">
                            <label for="model-vehicle">Modelo</label>
                            <input name="dat[model]" id="model-vehicle" type="number" placeholder="Modelo" min=1900>
                            <span class="msg-error-model">Ingrese un modelo de vehículo</span>
                        </div>
                        <div class="row">
                            <label for="brand-vehicle">Marca</label>
                            <input name="dat[brand]" id="brand-vehicle" type="text" placeholder="Marca">
                            <span class="msg-error-brand">Ingrese una marca</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldVehicles()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-vehicle" onclick="validateFormVehicle()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Editar-->
    <div id="modal-edit-vehicle" class="modal fade"  tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar datos del vehículo</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-edit-vehicle" method="POST" action="{{route('edit_vehicle')}}">
                        @csrf
                        <div class="row">
                            <label for="id-vehicle-edit">ID</label>
                            <input name="dat[id]" id="id-vehicle-edit" type="text" placeholder="ID" class="readonly" readonly>
                        </div>
                        <div class="row">
                            <label for="plate-vehicle-edit">Matricula</label>
                            <input name="dat[plate]" id="plate-vehicle-edit" type="text" placeholder="Número de placa">
                            <span class="msg-error-plate-edit">Ingrese el número de placa</span>
                        </div>
                        <div class="row">
                            <label for="color-vehicle-edit">Color</label>
                            <input name="dat[color]" id="color-vehicle-edit" type="text" placeholder="Color">
                            <span class="msg-error-color-edit">Ingrese color de vehículo</span>
                        </div>
                        <div class="row">
                            <label for="cylinder-vehicle-edit">Cilindraje</label>
                            <input name="dat[cylinder]" id="cylinder-vehicle-edit" type="number" placeholder="Cilindraje" min=0>
                            <span class="msg-error-cylinder-edit">Ingrese un cilindraje</span>
                        </div>
                        <div class="row">
                            <label for="name-vehicle-edit">Nombre</label>
                            <input name="dat[name]" id="name-vehicle-edit" type="text" placeholder="Nombre, ej: GT">
                            <span class="msg-error-name-edit">Ingrese un nombre de vehículo</span>
                        </div>
                        <div class="row">
                            <label for="model-vehicle-edit">Modelo</label>
                            <input name="dat[model]" id="model-vehicle-edit" type="number" placeholder="Modelo" min=1900>
                            <span class="msg-error-model-edit">Ingrese un modelo de vehículo</span>
                        </div>
                        <div class="row">
                            <label for="brand-vehicle-edit">Marca</label>
                            <input name="dat[brand]" id="brand-vehicle-edit" type="text" placeholder="Marca">
                            <span class="msg-error-brand-edit">Ingrese una marca</span>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn-clear" onclick="clearFieldVehicles()">Limpiar</button>
                    <a class="btn-cancel" data-dismiss="modal" aria-label="Close">Cancelar</a>
                    <button class="btn-add-vehicle" onclick="validateFormeEditVehicle()">Actualizar</button>
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
     * Función que elimina elimina los vehículos seleecionados
     */
    function removeVehicle() {
        var selected = Array();
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
            selected.push(element.closest('tr').children[1].innerHTML));
        console.log(selected);
        if(selected.length >= 1){
            $.ajax({
                type:'DELETE',
                url:'vehiculos',
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
