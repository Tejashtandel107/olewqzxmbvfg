var form_api = null;
const formData = {
    "salary_plus_bonus": {
        "price":"",
        "bonus_target": 6000,
        "price_righe_ordinaria": 0.015,
        "price_righe_semplificata": 0.015,
        "price_righe_corrispettivi_semplificata": 1.5,
        "price_righe_paghe_semplificata": 0.45,
        "price_righe_am_ordinaria": 0.00,
        "price_righe_am_semplificata": 0.00,
        "price_righe_am_corrispettivi_semplificata": 0.00,
        "price_righe_am_paghe_semplificata": 0.00
    },
    "per_righe_and_per_registrazioni" : {
        "price": 0,
        "bonus_target": 0,
        "price_righe_ordinaria": 0.15,
        "price_righe_semplificata": 0.15,
        "price_righe_corrispettivi_semplificata": 3,
        "price_righe_paghe_semplificata": 3,
        "price_righe_am_ordinaria": 0.015,
        "price_righe_am_semplificata": 0.015,
        "price_righe_am_corrispettivi_semplificata": 1.500,
        "price_righe_am_paghe_semplificata": 0.450
    }
}
$(function () {
    form_api = {
        $form: null,
        init: function ($form) {
            this.$form = $form;
            this.bindEvents();
        },
        getFormMethod: function () {
            var form_method = this.$form.find('input[name="_method"]').val();
            if (typeof form_method === "undefined")
                form_method = this.$form.attr("method");

            return form_method.toLowerCase();
        },
        bindEvents: function () {
            form_api.$form.formValidation({
                framework: "bootstrap4",
                button: {
                    selector: "#submitbtn",
                    disabled: "",
                },
                icon: null,
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: "Inserisci il nome",
                            },
                        },
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: "Inserisci l'indirizzo e-mail",
                            },
                            emailAddress: {
                                message:
                                    "Per favore fornisci un indirizzo email valido",
                            },
                        },
                    },
                    password: {
                        validators: {
                            notEmpty: {
                                message: "Inserisci la tua password",
                            },
                        },
                    },
                    "account_manager_id[]": {
                        validators: {
                            notEmpty: {
                                message:
                                    "Seleziona il tuo account manager.",
                            },
                        },
                    },
                    "client_id[]": {
                        validators: {
                            notEmpty: {
                                message:
                                    "Si prega di selezionare il studio.",
                            },
                        },
                    },
                    pricing_type: {
                        validators: {
                            notEmpty: {
                                message: "Seleziona il tipo di prezzo.",
                            },
                        },
                    },
                    price: {
                        validators: {
                            notEmpty: {
                                message:
                                    "Questo campo è obbligatorio. Per favore inserire un valore.",
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message:
                                    "Questo è un campo numerico. Si prega di inserire solo il numero.",
                            },
                        },
                    },
                    price_righe_ordinaria: {
                        validators: {
                            notEmpty: {
                                message:
                                    "Questo campo è obbligatorio. Per favore inserire un valore.",
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message:
                                    "Questo è un campo numerico. Si prega di inserire solo il numero.",
                            },
                        },
                    },
                    price_righe_semplificata: {
                        validators: {
                            notEmpty: {
                                message:
                                    "Questo campo è obbligatorio. Per favore inserire un valore.",
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message:
                                    "Questo è un campo numerico. Si prega di inserire solo il numero.",
                            },
                        },
                    },
                    price_righe_corrispettivi_semplificata: {
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
                    price_righe_paghe_semplificata: {
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
                    price_righe_am_ordinaria: {
                        validators: {
                            notEmpty: {
                                message:
                                    "Questo campo è obbligatorio. Per favore inserire un valore.",
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message:
                                    "Questo è un campo numerico. Si prega di inserire solo il numero.",
                            },
                        },
                    },
                    price_righe_am_semplificata: {
                        validators: {
                            notEmpty: {
                                message:
                                    "Questo campo è obbligatorio. Per favore inserire un valore.",
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message:
                                    "Questo è un campo numerico. Si prega di inserire solo il numero.",
                            },
                        },
                    },
                    price_righe_am_corrispettivi_semplificata: {
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
                    price_righe_am_paghe_semplificata: {
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
                    bonus_target: {
                        validators: {
                            notEmpty: {
                                message:
                                    "Questo campo è obbligatorio. Per favore inserire un valore.",
                            },
                            regexp: {
                                regexp: /^[0-9_.]+$/,
                                message:
                                    "Questo è un campo numerico. Si prega di inserire solo il numero.",
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
                },
                err: {
                    clazz: "text-danger",
                },
                row: {
                    invalid: "",
                    valid: "",
                },
                onSuccess: function (e) {
                    e.preventDefault();
                    $(e.target).ajaxSubmit({
                        beforeSubmit: beforeformRequest,
                        error: onAjaxCallError,
                        success: formResponse,
                        dataType: "json",
                    });
                },
            })
            .on("err.validator.fv", function (e, data) {
                // $(e.target)    --> The field element
                // data.fv        --> The FormValidation instance
                // data.field     --> The field name
                // data.element   --> The field element
                // data.validator --> The current validator name
                data.element
                    .data("fv.messages")
                    // Hide all the messages
                    .find('.error[data-fv-for="' + data.field + '"]')
                    .hide()
                    // Show only message associated with current validator
                    .filter('[data-fv-validator="' + data.validator + '"]')
                    .show();
            });
            $("#generate-password").click(function () {
                var gnpassword = generatePassword(12);
                $("#password-section").show();
                $("#password").val(gnpassword);
            });
            // set default pricings if is a create form
            if (form_api.getFormMethod() === "post") {
                const pricingType = form_api.$form.find('select[name="pricing_type"]');
                pricingType.change(function () {
                    const selectedType = $(this).val();
                    updateFormFields(selectedType);
                });
                updateFormFields(pricingType.val());
            }
        }
    };
    form_api.init($("#operator-form"));
});
function updateFormFields(pricingType) {
    if (pricingType === '') {
        pricingType = 'salary_plus_bonus';
    }
    const values = formData[pricingType];
    for (const key in values) {
        $('#operator-form [name="' + key + '"]').val(values[key]);
    }
}

/* Register Date Picker */
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

function onAjaxCallError(requestObject, error, errorThrown) {
    $("#submitbtn").btnSpinner({ disabled: false });
    showErrorsNotification(
        requestObject.responseJSON.message,
        requestObject.responseJSON.errors
    );
}

function formResponse(responseText, statusText) {
    var caption = "Operatore aggiornato con successo.";

    if (form_api.getFormMethod() === "post") {
        caption = "Operatore creato con successo.";
        form_api.$form[0].reset();
    }

    $("#submitbtn").btnSpinner({ disabled: false });
    $("#notify").notification({
        caption: caption,
        type: "success",
        sticky: false,
    });
}
