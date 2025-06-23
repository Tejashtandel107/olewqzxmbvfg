$(function() {        
    $('#form-rest-password').formValidation({
        framework: "bootstrap4",
        button: {
            selector: '#submitbtn',
            disabled: ''
        },
        icon: null,
        fields: {
            current_password: {
                validators: {
                    notEmpty: {
                        message: "L'current password il campo è obbligatiorio."
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: "L'password il campo è obbligatiorio."
                    }
                }
            },
            password_confirmation: {
                validators: {
                    notEmpty: {
                        message: "L'password confirmation il campo è obbligatiorio."
                    },
                    identical: {
                        field: 'password',
                        message: 'La nuova password e la conferma della nuova password devono essere uguali'
                    }
                }
            },
        },
        err: {
            clazz: 'text-danger'
        },
        row: {
            invalid: '',
            valid:''
        },
        onSuccess: function(e) {
            e.preventDefault();
            $(e.target).ajaxSubmit({
                beforeSubmit:beforeformRequest,
                error:onAjaxCallError,
                success:formResponse,
                dataType: 'json'
            });
        }
    })
    .on('err.validator.fv', function(e, data) {
        // $(e.target)    --> The field element
        // data.fv        --> The FormValidation instance
        // data.field     --> The field name
        // data.element   --> The field element
        // data.validator --> The current validator name
        data.element
        .data('fv.messages')
        // Hide all the messages
        .find('.error[data-fv-for="' + data.field + '"]').hide()
        // Show only message associated with current validator
        .filter('[data-fv-validator="' + data.validator + '"]').show();

    });    
});
function beforeformRequest(formData, jqForm, options) {
    $("#submitbtn").btnSpinner();
}

function onAjaxCallError(requestObject, error, errorThrown){
    $("#submitbtn").btnSpinner({disabled:false});
    showErrorsNotification(requestObject.responseJSON.message,requestObject.responseJSON.errors);
}

function formResponse(responseText, statusText) {
    $("#submitbtn").btnSpinner({disabled:false});
    $("#password-update-notify").notification({caption: "La tua password è stata aggiornata", type:"success", sticky:false, onhide:function(){
        RefreshLocation();
    }});
}

function ResetPasswordModal() {
    $("#modal-reset-password").modal('show');
    $('#form-rest-password').trigger("reset");
};