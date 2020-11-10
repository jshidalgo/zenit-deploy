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
            document.getElementById('phone-employee-edit').value=data[1].number;
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

