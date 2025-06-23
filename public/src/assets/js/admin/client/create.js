var form_api = null;
$(function() {
    $(".date_range").datepicker({
        autoclose:true,
        language:'it-IT',
        orientation:'left bottom',
        format: "mm/yyyy",
        viewMode: "months", 
        minViewMode: "months",
        endDate: '+0d'
    });
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
                    pricing_type:{
                        validators: {
                            notEmpty: {
                                message: "Seleziona il tipo di prezzo."
                            }
                        }
                    },
                    price: {
                        validators: {
                            notEmpty: {
                                message: "Questo campo è obbligatorio. Per favore inserire un valore."
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                            },
                        },
                    },
                    price_ordinaria: {
                        validators: {
                            notEmpty: {
                                message: "Questo campo è obbligatorio. Per favore inserire un valore."
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                            },
                        },
                    },
                    price_semplificata: {
                        validators: {
                            notEmpty: {
                                message: "Questo campo è obbligatorio. Per favore inserire un valore."
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                            },
                        },
                    },
                    price_corrispettivi: {
                        validators: {
                            notEmpty: {
                                message: "Questo campo è obbligatorio. Per favore inserire un valore."
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                            },
                        },
                    },
                    price_paghe_semplificata: {
                        validators: {
                            notEmpty: {
                                message: "Questo campo è obbligatorio. Per favore inserire un valore."
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                            },
                        },
                    },
                    milestone: {
                        validators: {
                            notEmpty: {
                                message: "Questo campo è obbligatorio. Per favore inserire un valore."
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                            },
                        },
                    },
                    price_change_start_date: {
                        validators: {
                            notEmpty: {
                                message: "Seleziona la data di inizio.",
                            },
                        },
                    },
                    price_change_end_date: {
                        validators: {
                            notEmpty: {
                                message: "Seleziona la data di fine.",
                            },
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
                    price_level_id:{
                        validators : {
                            callback: {
                                message: 'Il livello di prezzo è richiesto',
                                callback: function(value, validator, $field) {
                                    if(value!=""){
                                        return true;
                                    }
                                    return ($('[name="activation_date"]').val()!="") ? false : true;
                                }
                            }
                        }
                    },
                    base_price_level_id:{
                        validators : {
                            callback: {
                                message: 'Il livello di prezzo originale è obbligatorio',
                                callback: function(value, validator, $field) {
                                    if(value!=""){
                                        return true;
                                    }
                                    return ($('[name="activation_date"]').val()!="") ? false : true;
                                }
                            }
                        }
                    }
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
            .on('change', '[name="activation_date"]', function(e) {
                form_api.$form.formValidation('revalidateField', 'price_level_id');
                form_api.$form.formValidation('revalidateField', 'base_price_level_id');
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

/* start date and end date Date Picker*/
$('#start_date,#end_date').daterangepicker({
    singleDatePicker: true,
    timePicker: false,
    autoApply:true,
    autoUpdateInput:true,
    maxDate:moment().subtract('day'),
    locale: {
        format: 'DD/MM/YYYY',
        cancelLabel:"Annulla",
        applyLabel:'Seleziona',
    }
});

$('#apply_price_change').change(function() {
    if (!$(this).is(':checked')) {
      $("#date-range").hide();
    }else{
        $("#date-range").show();
    }
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