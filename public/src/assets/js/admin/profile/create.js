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
    $('#form-setting-update').formValidation({
        framework: "bootstrap4",
        button: {
            selector: '#settingUpdate',
            disabled: ''
        },
        icon: null,
        fields: {
            company_name: {
		        validators: {
		            notEmpty: {
		                message: "Il campo nome azienda è obbligatorio."
		            }
		        }
		    },
		    company_address: {
		        validators: {
		            notEmpty: {
		                message: "Il campo della riga 1 dell'indirizzo è obbligatorio."
		            }
		        }
		    },
			company_city: {
		        validators: {
		            notEmpty: {
		                message: "Il campo Paese è obbligatorio."
		            }
		        }
		    },
			company_postal_code: {
		        validators: {
		            notEmpty: {
		                message: "Il campo Paese è obbligatorio."
		            }
		        }
		    },
		    country_name: {
		        validators: {
		            notEmpty: {
		                message: "Il campo Paese è obbligatorio."
		            }
		        }
		    },
		    devpos_exchange_rate: {
		        validators: {
		            notEmpty: {
		                message: "Il campo del tasso di cambio devpos è obbligatorio."
		            },
                    regexp: {
                        regexp: /^\d+(\.\d+)?$/,
                        message: "Questo è un campo numerico. Si prega di inserire solo numeri.",
                    }
		        },
		    },
		    devpos_tenant: {
		        validators: {
		            notEmpty: {
		                message: "Il campo tenant devpos è obbligatorio."
		            }
		        }
		    },
		    devpos_username: {
		        validators: {
		            notEmpty: {
		                message: "Il campo nome utente devpos è obbligatorio."
		            }
		        }
		    },
		    devpos_password: {
		        validators: {
		            notEmpty: {
		                message: "Il campo password devpos è obbligatorio."
		            }
		        }
		    },
		    devpos_business_unit_code: {
		        validators: {
		            notEmpty: {
		                message: "Il campo codice unità aziendale devpos è obbligatorio."
		            }
		        }
		    },
		    devpos_operator_code: {
		        validators: {
		            notEmpty: {
		                message: "Il campo codice operatore devpos è obbligatorio."
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
    })
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