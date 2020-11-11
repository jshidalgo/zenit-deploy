//Este archivo contiene las funciones utilizadas
// Inicio empleados ----------------------------------------
/**
 * Función que valida los campos del formulario  de agregar empleado
 * */
function validateFormEmployee() {
    var cc =$('#cc-employee').val();
    var name =$('#name-employee').val();
    var lt_name =$('#last-name-employee').val();
    var phone =$('#phone-employee').val();
    var addr =$('#addr-employee').val();
    var mail = $('#mail-employee').val();
    var vt_mail = validatemail(mail);
    if( cc !== "" && name !== "" && lt_name !== "" && phone !== "" && addr !== "" && mail !== "" && vt_mail){
        $('#modal-add-employee #form-add-employee').submit();
    }
    if(cc === ""){
        $('.msg-error-cc').css('display','block');
    }else{
        $('.msg-error-cc').css('display','none');
    }

    if(name === ""){
        $('.msg-error-name').css('display','block');
    }else{
        $('.msg-error-name').css('display','none');
    }

    if(lt_name === ""){
        $('.msg-error-last-name').css('display','block');
    }else{
        $('.msg-error-last-name').css('display','none');
    }
    if(phone === ""){
        $('.msg-error-phone').css('display','block');
    }else{
        $('.msg-error-phone').css('display','none');
    }
    if(addr === ""){
        $('.msg-error-address').css('display','block');
    }else{
        $('.msg-error-address').css('display','none');
    }
    if(mail === ""){
        $('.msg-error-mail ').css('display','block');
    }else{
        $('.msg-error-mail').css('display','none');
    }

    if(!vt_mail){
        $('.msg-error-invalid-mail').css('display','block');
    }else{
        $('.msg-error-invalid-mail').css('display','none');
    }
}

/**
 * Función que valida los campos del formulario de editar empleado
 */
function validateFormEditEmployee() {
    var cc =$('#cc-employee-edit').val();
    var name =$('#name-employee-edit').val();
    var lt_name =$('#last-name-employee-edit').val();
    var phone =$('#phone-employee-edit').val();
    var addr =$('#addr-employee-edit').val();
    var mail = $('#mail-employee-edit').val();
    var vt_mail = validatemail(mail);
    if( cc !== "" && name !== "" && lt_name !== "" && phone !== "" && addr !== "" && mail !== "" && vt_mail){
        $('#modal-edit-employee #form-edit-employee').submit();
    }
    if(cc === ""){
        $('.msg-error-cc-edit').css('display','block');
    }else{
        $('.msg-error-cc-edit').css('display','none');
    }

    if(name === ""){
        $('.msg-error-name-edit').css('display','block');
    }else{
        $('.msg-error-name-edit').css('display','none');
    }

    if(lt_name === ""){
        $('.msg-error-last-name-edit').css('display','block');
    }else{
        $('.msg-error-last-name-edit').css('display','none');
    }
    if(phone === ""){
        $('.msg-error-phone-edit').css('display','block');
    }else{
        $('.msg-error-phone-edit').css('display','none');
    }
    if(addr === ""){
        $('.msg-error-address-edit').css('display','block');
    }else{
        $('.msg-error-address-edit').css('display','none');
    }
    if(mail === ""){
        $('.msg-error-mail-edit').css('display','block');
    }else{
        $('.msg-error-mail-edit').css('display','none');
    }

    if(!vt_mail){
        $('.msg-error-invalid-mail-edit').css('display','block');
    }else{
        $('.msg-error-invalid-mail-edit').css('display','none');
    }
}
/**
 * Función que limpia todos los campos disponibles en la vista del empleado
 * */
function clearFieldEmployee() {
    document.querySelectorAll('#modal-edit-employee input, #modal-add-employee input').forEach(function (element) {
       element.value="";
    });
}
/**
 * Función que valida la estructura del correo
 * @return {boolean}
 */
function validatemail(mail){
    if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
    {
        return true;
    }
    return false;
}

/**
 * Función que valida la los datos ingresados en buscar empleado
 * @return {boolean}
 */
function validateSearchEmployee() {
    var search= $('#input-search').val();
    if(search !== ''){
        $('#form-search-employee').submit();
    }
}

/**
 * Función que carga los datos del empleado en el modal de editar
 */
function show_edit_employee() {
    var selected = Array();
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
        selected.push(element.closest('tr').children[2].innerHTML));
    console.log(selected);
    if(selected.length === 1){
        //Necesito cargar los datos en el modal
        $.ajax({
            type:'GET',
            url:'empleados/'+selected[0],
            data:{
                _token:'{{csrf_token()}}'
            }
        }).done(function(data) {
            document.getElementById('id-employee-edit').value=data[0].id;
            document.getElementById('cc-employee-edit').value=data[0].identification_card;
            document.getElementById('name-employee-edit').value=data[0].name;
            document.getElementById('last-name-employee-edit').value=data[0].last_name;
            document.getElementById('addr-employee-edit').value=data[0].address;
            document.getElementById('mail-employee-edit').value=data[0].mail;
            // document.getElementById('phone-employee-edit').value=data[1].number; // Hay que definir lo de los numeros, data[0] viene en null
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


//Fin empleados ---------------------------------------------------

//Inicio provedoores -----------------------------------------------

/**
 * Función que busca un cliente
 */
function validateSearchProvider() {
    var search= $('#input-search').val();
    if(search !== ''){
        $('#form-search-provider').submit();
    }
}


/**
 * Función que carga los datos del empleado en el modal de editar
 */
function show_edit_provider() {
    var selected = Array();
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
        selected.push(element.closest('tr').children[1].innerHTML));
    console.log(selected);
    if(selected.length === 1){
        //Necesito cargar los datos en el modal
        $.ajax({
            type:'GET',
            url:'proveedores/'+selected[0],
            data:{
                _token:'{{csrf_token()}}'
            }
        }).done(function(data) {
            console.log(data);
            document.getElementById('id-provider-edit').value=data[0].id;
            document.getElementById('nit-provider-edit').value=data[0].nit;
            document.getElementById('name-provider-edit').value=data[0].name;
            document.getElementById('mail-provider-edit').value=data[0].mail;
            document.getElementById('phone-provider-edit').value=data[0].number;
            document.getElementById('country-provider-edit').value=data[0].country;
            document.getElementById('departament-provider-edit').value=data[0].departament;
            document.getElementById('city-provider-edit').value=data[0].city;
            document.getElementById('addr-provider-edit').value=data[0].address;
        });
        //Mostrar modal para editar
        $('#modal-edit-provider').modal('show');
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
 * Función que limpia todos los campos disponibles en la vista de cliente
 **/
function clearFieldProvider() {
    document.querySelectorAll('#modal-edit-provider input, #modal-add-provider input').forEach(function (element) {
        element.value="";
    });
}

/**
 * metodo que valida las entradas de usuario
 */
function validateFormProvider(form) {
    var nit = $('#nit-provider'+form).val().trim();
    var name = $('#name-provider'+form).val().trim();
    var mail = $('#mail-provider'+form).val().trim();
    var vt_mail = validatemail(mail);
    var phone = $('#phone-provider'+form).val().trim();
    var country = $('#country-provider'+form).val().trim();
    var departament = $('#departament-provider'+form).val().trim();
    var city = $('#city-provider'+form).val().trim();
    var addr = $('#addr-provider'+form).val().trim();
    if (nit !== "" & name !== "" & mail !== "" & phone !== "" & country !== "" & departament !== "" & city !== "" & addr !== "" && vt_mail) {
        console.log(nit, name, mail, phone, country, departament, city, addr);
        if (form === '-edit') {
            $('#modal-edit-provider #form-edit-provider').submit();
        }else{

            $('#modal-add-provider #form-add-provider').submit();
        }
    }
    if (nit === "") {
        $('.msg-error-nit'+form).css('display', 'block');
    } else {
        $('.msg-error-nit'+form).css('display', 'none');
    }

    if (name === "") {
        $('.msg-error-name'+form).css('display', 'block');
    } else {
        $('.msg-error-name'+form).css('display', 'none');
    }

    if (country === "") {
        $('.msg-error-country'+form).css('display', 'block');
    } else {
        $('.msg-error-country'+form).css('display', 'none');
    }
    if (departament === "") {
        $('.msg-error-departament'+form).css('display', 'block');
    } else {
        $('.msg-error-departament'+form).css('display', 'none');
    }
    if (city === "") {
        $('.msg-error-city'+form).css('display', 'block');
    } else {
        $('.msg-error-city'+form).css('display', 'none');
    }
    if (phone === "") {
        $('.msg-error-phone'+form).css('display', 'block');
    } else {
        $('.msg-error-phone'+form).css('display', 'none');
    }
    if (addr === "") {
        $('.msg-error-addr'+form).css('display', 'block');
    } else {
        $('.msg-error-addr'+form).css('display', 'none');
    }
    if (mail === "") {
        $('.msg-error-mail '+form).css('display', 'block');
    } else {
        $('.msg-error-mail'+form).css('display', 'none');
    }

    if (!vt_mail) {
        $('.msg-error-invalid-mail'+form).css('display', 'block');
    } else {
        $('.msg-error-invalid-mail'+form).css('display', 'none');
    }
}

//Fin proveedores ---------------

//Inicio compras -----------------------
/**
 * Función que busca una compra
 */
function validateSearchPurchase() {
    var search= $('#input-search').val();
    if(search !== ''){
        $('#form-search-purchase').submit();
    }
}


/**
 * Función que carga los datos de la compra en el modal de compra
 */
function show_edit_purchase() {
    var selected = Array();
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
        selected.push(element.closest('tr').children[1].innerHTML));
    console.log(selected);
    if(selected.length === 1){
        //Necesito cargar los datos en el modal
        $.ajax({
            type:'GET',
            url:'compras/'+selected[0],
            data:{
                _token:'{{csrf_token()}}'
            }
        }).done(function(data) {
            console.log(data);

            document.getElementById('id-purchase-edit').value=data[0].id;
            document.getElementById('cod-edit-purchase').value=data[0].cod;
            document.getElementById('date-edit-purchase').value=data[0].date;
            document.getElementById('costU-edit-purchase').value=data[0].cost;
            document.getElementById('concept-edit-purchase').value=data[0].concept;
            document.getElementById('status-edit-purchase').value=data[0].status;
            document.getElementById('provider-edit-purchase').value = data[0].provider_id;
            // document.getElementById('phone-customer-edit').value=data[1].number;
        });
        //Mostrar modal para editar
        $('#modal-edit-purchase').modal('show');
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

//numero de productos agregados
var products = 2;

/**
 * función que permite agergar compras
 * @param modal
 */
function addProductPurchase(modal) {
    console.log(modal)
    console.log("Este es el modal ^")
    if (validateFormProductPurchase(0,modal)) {
        //se agrega el contenedor del producto

        var contenedorProducto = "<div class='row-product' id='product" + products + "'></div>";
        $('#row-products-'+modal).append(contenedorProducto);
        //se agrega el HMTL del nombre del producto
        var nombreProducto = "<div class='row'>" +
            "<label for='name-product-purchase" + products + "'>Nombre producto</label>" +
            "<input name='product[nameProduct" + products + "]' id='name-product-purchase" + products + "' type='text' placeholder='Nombre del producto'>" +
            "<span class='msg-error-name-product' id='msg-error-name-product" + products + "'>Ingrese nombre del producto</span>" +
            "</div>";
        $('#product' + products).append(nombreProducto);
        //se agrega el HTML de valor
        var valorProducto = "<div class='row'>" +
            "<label for='costU-product-purchase" + products + "'>Valor de costo unidad</label>" +
            "<input name='product[costProduct" + products + "]' id='costU-product-purchase" + products + "' type='number' placeholder='Valor de costo unitario del producto' min='0'>" +
            "<span class='msg-error-cost-product' id='msg-error-cost-product" + products + "'>Ingrese el valor de costo unidad del producto</span>" +
            "</div>";
        $('#product' + products).append(valorProducto);
        // se agrega el HTML de la cantidad
        var cantidadProducto = "<div class='row'>" +
            "<label for='amount-product-purchase" + products + "'>Cantidad</label>" +
            "<input name='product[amountProduct" + products + "]' id='amount-product-purchase" + products + "' type='number' placeholder='Cantidad producto disponible' min='1'>" +
            "<span class='msg-error-amount-product' id='msg-error-amount-product" + products + "'>Ingrese la cantidad de producto</span>" +
            "</div>";
        $('#product' + products).append(cantidadProducto);

        //se agrega el hTML de los botones, ocultando el de agregar del anterior
        $('#product-add' + (products - 1)).css('display', 'none');
        //mostrando el btn de eliminar
        $('#product-del' + (products - 1)).css('display', 'block');
        var btnProducto = "<div class='modal-footer'>" +
            "<i class='fas fa-plus-circle fa-2x' onclick='addProductPurchase(\""+modal+"\")' id='product-add" + products + "'></i>" +
            "<i class='fas fa-minus-circle fa-2x' id='product-del" + products + "' onclick='deleteProductPurchase(" + products + ")'></i>" +
            "</div>";
        $('#product' + products).append(btnProducto);
        //incrementar el valor de los productos agregados
        products += 1;
    }
}

/**
 * función que permite eliminar un producto del DOM
 * @param numberProduct - numero que identifica al producto dentro del DOM
 */
function deleteProductPurchase(numberProduct) {
    //$("#product3").detach()
    console.log(numberProduct);
    //eliminando el elemento
    //se elimina el div que contien todo la informacion del producto
    $("#product" + numberProduct).detach();
}

//metodo que valida el formulario del producto
//num -
//
//num = 0 - valide todos
//num = 1 - valide todos menos el ultimo
/**
 * función que permite validar los formularios de los productos
 * esto con el fin de omitir validar el ultimo formulario de producto
 * num = 0 - valide todos
 //num = 1 - valide todos menos el ultimo
 * @param num - numero que se le resta a length
 * @param modal - modal al cual se le hara la verificación, edit para modal de editar y add para el modal de agregar
 * @returns {boolean}
 */
function validateFormProductPurchase(num,modal) {
    //hijos dentro del contenedor div
    var products = $("#row-products-"+modal).children();
    var length = (products.length - num);
    console.log(length);
    for (i = 0; i < products.length; i++) {
        var idProduct = products[i].getAttribute('id').substring(7);
        var name = $('#name-product-purchase' + (idProduct)).val().trim();
        var cost = $('#costU-product-purchase' + (idProduct)).val().trim();
        var amount = $('#amount-product-purchase' + (idProduct)).val().trim();

        if (name !== "" && cost !== "" && amount !== "") {
            console.log(name, cost, amount);
            length -= 1;
        }
        if (name === "") {
            $('#msg-error-name-product' + (idProduct)).css('display', 'block');
        } else {
            $('#msg-error-name-product' + (idProduct)).css('display', 'none');
        }
        if (cost === "") {
            $('#msg-error-cost-product' + (idProduct)).css('display', 'block');

        } else {
            $('#msg-error-cost-product' + (idProduct)).css('display', 'none');
        }
        if (amount === "") {
            $('#msg-error-amount-product' + (idProduct)).css('display', 'block');
        } else {
            $('#msg-error-amount-product' + (idProduct)).css('display', 'none');
        }
    }

    if (length === 0) {
        console.log(length);
        return true;
    } else {
        return false;
    }
}

/**
 * función que permite validar el fomulario de compras
 * @param modal - modal al cual se le hara la verificación, add para el modal de agregar compra o edit para el modal de editar compra
 */
function validateFormPurchase(modal) {
    var cod = $('#cod-purchase').val();
    var date = $('#date-purchase').val();
    var cost = $('#costU-purchase').val();
    var concept = $('#concept-purchase').val();
    var status = $('#status-purchase').val();
    var provider = $('#provider-purchase').val();

    if (cod !== "" && date !== "" && cost !== "" && concept !== "" && status !== "-1" && provider !== "-1") {
        console.log(cod, date, cost, concept, status, provider);
        var products = $("#row-products-"+modal).children();
        var count = products.length;
        console.log(count + " - numero de productos")
        if (count === 1 & validateFormProductPurchase(0,modal)) {

            $('#modal-add-purchase #form-add-purchase').submit();

        } else if (count !== 1 && validateFormProductPurchase(1,modal)) {
            $(products[count - 1]).detach();
            $('#modal-add-purchase #form-add-purchase').submit();
        }
    }
    if (cod === "") {
        $('.msg-error-cod').css('display', 'block');
    } else {
        $('.msg-error-cod').css('display', 'none');
    }
    if (date === "") {
        $('.msg-error-date').css('display', 'block');
    } else {
        $('.msg-error-date').css('display', 'none');
    }
    if (cost === "") {
        $('.msg-error-cost').css('display', 'block');

    } else {
        $('.msg-error-cost').css('display', 'none');
    }
    if (concept === "") {
        $('.msg-error-concept').css('display', 'block');
    } else {
        $('.msg-error-concept').css('display', 'none');
    }

    if (status === "-1") {
        $('.msg-error-status').css('display', 'block');
    } else {
        $('.msg-error-status').css('display', 'none');
    }
    if (provider === "-1") {
        $('.msg-error-provider').css('display', 'block');
    } else {
        $('.msg-error-provider').css('display', 'none');
    }

}

//Fin compras ---------------

//Inicio productos ------------
/**
 * Función que busca un producto
 */
function validateSearchProduct() {
    var search= $('#input-search').val().trim();

    if(search !== ''){
        $('#form-search-product').submit();
    }
}

/**
 * Función que carga los datos del un producto en el modal editar
 */
function show_edit_product() {
    var selected = Array();
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
        selected.push(element.closest('tr').children[1].innerHTML));
    console.log(selected);
    if(selected.length === 1){
        //Necesito cargar los datos en el modal
        $.ajax({
            type:'GET',
            url:'productos/'+selected[0],
            data:{
                _token:'{{csrf_token()}}'
            }
        }).done(function(data) {
            console.log(data);
            document.getElementById('id-product-edit').value=data[0].id;
            document.getElementById('cod-product-edit').value=data[0].code;
            document.getElementById('name-product-edit').value=data[0].name;
            document.getElementById('sale-price-product-edit').value=data[0].sale_price;
            document.getElementById('amount-product-edit').value=data[0].units_available;
            document.getElementById('description-product-edit').value=data[0].description;
        });
        //Mostrar modal para editar
        $('#modal-edit-product').modal('show');
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
 * Función que limpia todos los campos disponibles en la vista de productos
 **/
function clearFieldProduct() {
    document.querySelectorAll('#modal-edit-product input, #modal-add-product input, #modal-edit-product textarea, #modal-add-product textarea').forEach(function (element) {
        element.value="";
    });
}

/**
 * funcion que valida el formulario para agregar o editar un producto
 * @param form - formulario a validar, -edit para el formulario que edita un producto o -add para el que los agrega
 */
function validateFormProduct(form) {
    var cod = $('#cod-product'+form).val().trim();
    var name = $('#name-product'+form).val().trim();
    var cost = $('#sale-price-product'+form).val().trim();
    var amount = $('#amount-product'+form).val().trim();
    var des = $('#description-product'+form).val().trim();

    if (cod != "" & name != "" & cost != "" & amount != "" & des != "") {
        console.log(cod, name, cost, amount, des);
        if (form === '-edit') {
            $('#modal-edit-product #form-edit-product').submit();

        }else{
            $('#modal-add-product #form-add-product').submit();
        }
    }
    if (cod == "") {
        $('.msg-error-cod'+form).css('display', 'block');
    }else {
        $('.msg-error-cod'+form).css('display', 'none');
    }
    if (name == "") {
        $('.msg-error-name'+form).css('display', 'block');
    }else {
        $('.msg-error-name'+form).css('display', 'none');
    }
    if (cost == "") {
        $('.msg-error-cost'+form).css('display', 'block');

    }else {
        $('.msg-error-cost'+form).css('display', 'none');
    }
    if (amount == "") {
        $('.msg-error-amount'+form).css('display', 'block');
    }else {
        $('.msg-error-amount'+form).css('display', 'none');
    }
    if (des == "") {
        $('.msg-error-description'+form).css('display', 'block');
    }else {
        $('.msg-error-description'+form).css('display', 'none');
    }
}

//Fin productos ----

//Inicio vehiculos ----

/**
 * Función que carga los datos en el modal de Editar vehiculo
 */
function show_edit_vehicle() {
    var selected = Array();
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(element =>
        selected.push(element.closest('tr').children[1].innerHTML));
    console.log(selected);
    if(selected.length === 1){
        //Necesito cargar los datos en el modal
        $.ajax({
            type:'GET',
            url:'vehiculos/'+selected[0],
            data:{
                _token:'{{csrf_token()}}'
            }
        }).done(function(data) {
            console.log(data);
            document.getElementById('id-vehicle-edit').value=data['result'].id;
            document.getElementById('plate-vehicle-edit').value=data['result'].license_plate;
            document.getElementById('color-vehicle-edit').value=data['result'].color;
            document.getElementById('cylinder-vehicle-edit').value=data['result'].cylinder_capacity;
            document.getElementById('name-vehicle-edit').value=data['result'].name;
            document.getElementById('model-vehicle-edit').value=data['result'].model;
            document.getElementById('brand-vehicle-edit').value=data['result'].brand;
        });
        //Mostrar modal para editar
        $('#modal-edit-vehicle').modal('show');
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
 * Función que valida el fomrulario de agregar vehiculo
 */
function validateFormVehicle() {
    var plate = $("#plate-vehicle").val().trim();
    var color = $("#color-vehicle").val().trim();
    var cylinder = $("#cylinder-vehicle").val().trim();
    var model = $("#model-vehicle").val().trim();
    var brand = $("#brand-vehicle").val().trim();
    var name = $('#name-vehicle').val().trim();
    if (plate !== "" && color !== "" && cylinder !== "" && model !== "" && brand !== "" && name !== "") {
        $('#modal-add-vehicle #form-add-vehicle').submit();
    }

    if (plate == "") {
        $('.msg-error-plate').css('display', 'block');
    } else {
        $('.msg-error-plate').css('display', 'none');
    }
    if (color == "") {
        $('.msg-error-color').css('display', 'block');
    } else {
        $('.msg-error-color').css('display', 'none');
    }
    if (cylinder == "") {
        $('.msg-error-cylinder').css('display', 'block');
    } else {
        $('.msg-error-cylinder').css('display', 'none');
    }
    if (model == "") {
        $('.msg-error-model').css('display', 'block');
    } else {
        $('.msg-error-model').css('display', 'none');
    }
    if (brand == "") {
        $('.msg-error-brand').css('display', 'block');
    } else {
        $('.msg-error-brand').css('display', 'none');
    }
    if (name == "") {
        $('.msg-error-name').css('display', 'block');
    } else {
        $('.msg-error-name').css('display', 'none');
    }
}

/**
 * Función que valida el formulario de editar vehiculo
 */
function validateFormeEditVehicle() {
    var plate = $("#plate-vehicle-edit").val().trim();
    var color = $("#color-vehicle-edit").val().trim();
    var cylinder = $("#cylinder-vehicle-edit").val().trim();
    var model = $("#model-vehicle-edit").val().trim();
    var brand = $("#brand-vehicle-edit").val().trim();
    var name = $('#name-vehicle-edit').val().trim();
    if (plate !== "" && color !== "" && cylinder !== "" && model !== "" && brand !== "" && name !== "") {
        $('#modal-edit-vehicle #form-edit-vehicle').submit();
    }

    if (plate == "") {
        $('.msg-error-plate-edit').css('display', 'block');
    } else {
        $('.msg-error-plate-edit').css('display', 'none');
    }
    if (color == "") {
        $('.msg-error-color-edit').css('display', 'block');
    } else {
        $('.msg-error-color-edit').css('display', 'none');
    }
    if (cylinder == "") {
        $('.msg-error-cylinder-edit').css('display', 'block');
    } else {
        $('.msg-error-cylinder-edit').css('display', 'none');
    }
    if (model == "") {
        $('.msg-error-model-edit').css('display', 'block');
    } else {
        $('.msg-error-model-edit').css('display', 'none');
    }
    if (brand == "") {
        $('.msg-error-brand-edit').css('display', 'block');
    } else {
        $('.msg-error-brand-edit').css('display', 'none');
    }
    if (name == "") {
        $('.msg-error-name-edit').css('display', 'block');
    } else {
        $('.msg-error-name-edit').css('display', 'none');
    }
}

/**
 * Función que limpia todos los campos disponibles en la vista de vehiculos
 **/
function clearFieldVehicles() {
    document.querySelectorAll('#modal-edit-vehicle input, #modal-add-vehicle input').forEach(function (element) {
        element.value="";
    });
}
//Fin vehiculos ------
