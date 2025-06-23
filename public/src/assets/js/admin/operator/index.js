function OperatorDeleteModal(url, name) {  
    $("#form-operator-delete").attr('action',url);
    $('#modal-operator-name').html(name);
    $("#modal-operator-delete").modal('show');
    $('#form-operator-delete').trigger("reset");
};
function operatorDelete(event) {
    event.preventDefault();
    var $form = $("#form-operator-delete");
    $.ajax({
        type: "DELETE",
        url: $form.attr('action'),
        dataType: "json",
        data:$form.serialize(),
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
    $("#modal-notify").notification({caption: "Operatore eliminato con successo.", type:"success", sticky:false, onhide:function(){
        RefreshLocation();
    }});
}

