
$(function() { 
    $("#submitbtn").click(function(event){
        event.preventDefault();
        $('#import-company-form').ajaxSubmit({
            error:onAjaxCallError,
            success:formResponse,
            dataType: 'json',
        });
    });
});

function onAjaxCallError(requestObject, error, errorThrown){
    $("#submitbtn").btnSpinner({disabled:false});
    showErrorsNotification(requestObject.responseJSON.message,requestObject.responseJSON.errors);
}

function formResponse(responseText, statusText) {
    hideLoader();
    $('#submitbtn').btnSpinner({disabled:false});

    if(responseText.type == "success"){
        $("#notify").notification({caption: responseText.message, type:responseText.type, sticky:false,closebutton:false});
    }
    else{
        $("#notify").notification({caption: responseText.message, type:responseText.type, sticky:true,closebutton:false});
    }
}
