function deleteCompanyModal(url, name) {  
    $("#form-company-delete").attr('action',url);
    $('#modal-company-name').html(name);
    $("#modal-company-delete").modal('show');
    $('#form-company-delete').trigger("reset");
};
function companyDelete(event) {
    event.preventDefault();
    var $form = $("#form-company-delete");
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
    $("#modal-notify").notification({caption: "Azienda eliminata con successo.", type:"success", sticky:false, onhide:function(){
        RefreshLocation();
    }});
}