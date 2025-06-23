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
                    company_name: {
                        validators: {
                            notEmpty: {
                                message: "Seleziona il nome dell'azienda."
                            }
                        }
                    },
                    company_type: {
                        validators: {
                            notEmpty: {
                                message: "Si prega di selezionare il tipo di azienda."
                            }
                        }
                    },
                    user_id:{
                        validators: {
                            notEmpty: {
                                message: "Si prega di selezionare il studio."
                            }
                        }
                    },
                    vat_tax:{
                        validators: {
                            notEmpty: {
                                message: "Inserisci partita IVA/Codice fiscale."
                            }
                        }
                    },
                    business_type:{
                        validators: {
                            notEmpty: {
                                message: "Seleziona il tipo di attività."
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
        }
    }
    form_api.init($('#company-form'));
});

function beforeformRequest(formData, jqForm, options) {
    $("#submitbtn").btnSpinner();    
}

function onAjaxCallError(requestObject, error, errorThrown){
    $("#submitbtn").btnSpinner({disabled:false});
    showErrorsNotification(requestObject.responseJSON.message,requestObject.responseJSON.errors);
}

function formResponse(responseText, statusText) {
    var caption = "Azienda aggiornata con successo.";
    
    if(form_api.getFormMethod() === 'post')
    {
        caption = "Azienda creata con successo.";
        form_api.$form[0].reset();
    }

    $("#submitbtn").btnSpinner({disabled:false});
    $("#notify").notification({caption: caption, type:"success", sticky:false});
}