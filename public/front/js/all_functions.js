//Este archivo contiene las funciones utilizadas

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
function clearField() {
    document.querySelectorAll('#modal-edit-employee input, #modal-add-employee input').forEach(function (element) {
       element.value="";
    });
}
/**
 * Función que valida la estructura del correo
 * @return {boolean}
 */
function validatemail(mail)
{
    if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
    {
        return true;
    }
    return false;
}

function validateSearchEmployee() {
    var search= $('#input-search').val();
    if(search !== ''){
        $('#form-search-employee').submit();
    }
}
