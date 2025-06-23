function deleteClientModal(url, name) {  
    $("#form-client-delete").attr('action',url);
    $('#modal-client-name').html(name);
    $("#modal-client-delete").modal('show');
    $('#form-client-delete').trigger("reset");
};
function clientDelete(event) {
    event.preventDefault();
    var $form = $("#form-client-delete");
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
    $("#modal-notify").notification({caption: "Studio eliminata con successo.", type:"success", sticky:false, onhide:function(){
        RefreshLocation();
    }});
}