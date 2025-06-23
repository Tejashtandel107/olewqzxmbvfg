var form_api = null;
$(function() {
    form_api = {
        $form: null,
        init: function(form){
            this.$form = form;
            this.bindEvents();
        },
        getFormMethod: function(){
            var form_method = this.$form.find('input[name="_method"]').val(); 
            if(typeof form_method === 'undefined')
                form_method = this.$form.attr('method');

            return form_method.toLowerCase();
        },
        bindEvents: function(){
            form_api.$form.formValidation({
                framework: "bootstrap4",
                button: {
                    selector: '#submitbtn',
                    disabled: ''
                },
                icon: null,
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'Inserisci il nome'
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: "Inserisci l'indirizzo e-mail"
                            },
                            emailAddress: {
                                message: 'Per favore fornisci un indirizzo email valido'
                            }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: "Per favore, inserisci la password",
                            }
                        },
                    },
                    address: {
                        validators: {
                            notEmpty: {
                                message: "Il campo dell'indirizzo è obbligatorio."
                            }
                        }
                    },
                    vat_number: {
                        validators: {
                            notEmpty: {
                                message: "Inserisci la tua partita IVA"
                            }
                        }
                    },
                    city: {
                        validators: {
                            notEmpty: {
                                message: "Il campo Città/Paese è obbligatorio."
                            }
                        }
                    },
                    postal_code: {
                        validators: {
                            notEmpty: {
                                message: "Il campo del codice postale è obbligatorio."
                            }
                        }
                    },
                    country_id: {
                        validators: {
                            notEmpty: {
                                message: "Seleziona il paese."
                            },
                            regexp: {
                                regexp: /^\d+$/,
                                message: "Seleziona il paese.",
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
            $("#generate-password").click(function () { 
                var gnpassword = generatePassword(12);
                $('#password-section').show();
                $('#password').val(gnpassword);
            });               
        }
    }
    form_api.init($('#client-form'));
});

function beforeformRequest(formData, jqForm, options) {
    $("#submitbtn").btnSpinner();    
}

function onAjaxCallError(requestObject, error, errorThrown){
    $("#submitbtn").btnSpinner({disabled:false});
    showErrorsNotification(requestObject.responseJSON.message,requestObject.responseJSON.errors);
}

function formResponse(responseText, statusText) {
    var caption = "Studio aggiornato con successo.";
    
    if(form_api.getFormMethod() === 'post'){
        caption = "Studio creato con successo.";
        form_api.$form[0].reset();
    }

    $("#submitbtn").btnSpinner({disabled:false});
    $("#notify").notification({caption: caption, type:"success", sticky:false});
}