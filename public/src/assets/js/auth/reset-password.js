$(function() {
    $('#password-reset').formValidation({
        framework: "bootstrap4",
        button: {
            selector: '#submitbtn',
            disabled: ''
        },
        icon: null,
        fields: {
			email: {
				validators: {
					notEmpty: {
						message: 'Inserisci l`indirizzo e-mail'
					}
				}
			},
			password: {
				validators: {
					notEmpty: {
						message: 'Inserisci una nuova password'
					}
				}
			},
			password_confirmation: {
				validators: {
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

    var msgs=[];
    var caption = (typeof requestObject.responseJSON.message !== 'undefined') ? requestObject.responseJSON.message : "Siamo spiacenti, abbiamo riscontrato un errore. Per favore riprova.";
    
    if(typeof requestObject.responseJSON.errors !=='undefined'){
        var errors = requestObject.responseJSON.errors;
        if(Object.keys(errors).length>1){
            jQuery.each(errors, function(i, val) {
                msgs.push({ message: val });
            });
            caption = requestObject.responseJSON.message;
        }
    }
    $("#notify").notification({caption: caption,messages:msgs,sticky:true});
}

function formResponse(responseText, statusText) {
    $("#submitbtn").btnSpinner({disabled:false});

    if(statusText == "success") {
        if(responseText.type=="success"){
            $("#notify").notification({caption: responseText.message, type:responseText.type, sticky:false,onhide:function(){
                window.location.href=responseText.redirectUrl;
            }});
        }
        else{
            $("#notify").notification({caption: responseText.message, type:responseText.type, sticky:true});
        }
    }
}
