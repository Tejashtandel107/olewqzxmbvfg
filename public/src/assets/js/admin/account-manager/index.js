function deleteAccountManagerModal(url, name) {  
    $("#form-account-manager-delete").attr('action',url);
    $('#modal-account-manager-name').html(name);
    $("#modal-account-manager-delete").modal('show');
    $('#form-account-manager-delete').trigger("reset");
};
function accountManagerDelete(event) {
    event.preventDefault();
    var $form = $("#form-account-manager-delete");
    $.ajax({
        type: "DELETE",
        url: $form.attr('action'),
        data:$form.serialize(),
        dataType: "json",
        success: deleteResponse,
        error: onAjaxCallError,
        beforeSend: function () {
            $("#btn-delete").btnSpinner();  
        },
    });
}
function onAjaxCallError(requestObject, error, errorThrown){
    $("#btn-delete").btnSpinner({disabled:false});
    showErrorsNotification(requestObject.responseJSON.message,requestObject.responseJSON.errors);
}
function deleteResponse(responseText, statusText) {
    $("#btn-delete").btnSpinner({disabled:false});
    $("#modal-notify").notification({caption: "Account manager eliminato con successo.", type:"success", sticky:false, onhide:function(){
        RefreshLocation();
    }});
}