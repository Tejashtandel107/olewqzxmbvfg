var form_api = null;

$(function () {
    form_api = {
        $form: null,
        init: function (form) {
            this.$form = form;
            this.bindEvents();
        },
        getFormMethod: function () {
            var form_method = this.$form.find('input[name="_method"]').val();
            if (typeof form_method === "undefined")
                form_method = this.$form.attr("method");

            return form_method.toLowerCase();
        },
        
        bindEvents: function () {
            form_api.$form
                .formValidation({
                    framework: "bootstrap4",
                    button: {
                        selector: "#submitbtn",
                        disabled: "",
                    },
                    icon: null,
                    fields: {
                        register_date: {
                            validators: {
                                notEmpty: {
                                    message: "Si prega di selezionare la data di registrazione",
                                }
                            }
                        },
                        client_id: {
                            validators: {
                                notEmpty: {
                                    message: "Si prega di selezionare il studio",
                                }
                            }
                        },
                        company_id: {
                            validators: {
                                notEmpty: {
                                    message: "Si prega di selezionare l'azienda",
                                }
                            }
                        },
                        purchase_invoice_from: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        purchase_invoice_to: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        purchase_invoice_lines: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        sales_invoice_from: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        sales_invoice_to: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        sales_invoice_lines: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        payment_register_day: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        payment_register_daily_records: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        payment_register_lines: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        petty_cash_bank_id: {
                            validators: {
                                notEmpty: {
                                    message: "Seleziona la banca",
                                }
                            }
                        },
                        petty_cash_other_bank: {
                            validators: {
                                notEmpty: {
                                    message: "Inserisci il nome della banca",
                                }
                            }
                        },
                        petty_cash_book_lines: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        petty_cash_book_registrations: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
                        },
                        extra_time: {
                            validators: {
                                regexp: {
                                    regexp: /^[0-9_.]+$/,
                                    message: 'Questo è un campo numerico. Si prega di inserire solo il numero.',
                                }
                            }
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
        },
    };
    form_api.init($("#account-form"));

    $("#client-id").on("change", function () {
        $company = $("#company-id");
        if (this.value == ""){
            $company.html('<option value="">Seleziona</option>');
        }else{
            $.ajax({
                url: "/api/companies?client_id="+ this.value +"&show_all=true",
                method: "get",
                dataType: "json",
                beforeSend: function () {
                    $company.html('<option value="">Seleziona</option>');
                    $company.prop("disabled", true);
                },
                success: function (response) {
                    $company.prop("disabled", false);
                    if (!isUndefined(response.data)) {
                        $.each(response.data, function (key, value) {
                            $company.append(
                                '<option value="' +
                                    value.companyId +
                                    '">' +
                                    value.companyName+ " ("+ value.companyType +")" +
                                    "</option>"
                            );
                        });
                    }
                },
                error: function (requestObject, error, errorThrown) {
                    $company.prop("disabled", false);
                    showErrorsNotification(
                        requestObject.responseJSON.message,
                        requestObject.responseJSON.errors
                    );
                },
            });
        }
    });
    /* Register Date Picker */
    $('#register_date').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        autoApply:true,
        autoUpdateInput:true,
        minDate:moment().subtract(2, "months"),
        maxDate:moment().subtract('day'),
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel:"Annulla",
            applyLabel:'Seleziona',
        }
    });
    
    $('#register_date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY'));
        form_api.$form.formValidation('revalidateField', 'register_date');
    });    
    /* Job Start Time and Job End Time Date & Time Picker */

    $('#job_start_time,#job_end_time').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        autoUpdateInput: false,
        maxDate:moment().subtract('day'),
        locale: {
            format: 'DD/MM/YYYY HH:mm',
            cancelLabel:"Annulla",
            applyLabel:'Seleziona',
        }
    });
    $('#job_start_time,#job_end_time').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY hh:mm A'));
        form_api.$form.formValidation('revalidateField', 'time_spent_start_time');
        form_api.$form.formValidation('revalidateField', 'time_spent_end_time');
    });

    /*Purchase Form and purchase To value Subtract and plus one value*/

    $("#purchase_invoice_from, #purchase_invoice_to").on('input',function(){
        var purchase_from = $("#purchase_invoice_from").val();
        var purchase_to = $("#purchase_invoice_to").val();
        setRegistrations(purchase_from,purchase_to,$("#purchase_invoice_registrations"));
    })
    $("#sales_invoice_from, #sales_invoice_to").on('input',function(){
        var sales_from = $("#sales_invoice_from").val();
        var sales_to = $("#sales_invoice_to").val();
        setRegistrations(sales_from,sales_to,$("#sales_invoice_registrations"));
    });
    $("#petty_cash_book_type").change (function () { 
        if($(this).val() == 'Banca'){
            $('.petty-cash-bank-id').show();
            $('.petty-cash-other-bank').hide();
            
            $('#petty_cash_bank_id').val("");
            $('#petty_cash_other_bank').val("");
        }
        else{
            $('.petty-cash-bank-id').hide();
            $('.petty-cash-other-bank').hide();
        }
    });
    $('#petty_cash_bank_id').change (function () {
        if($("#petty_cash_bank_id option:selected").text() == 'Altro'){
            $('.petty-cash-other-bank').show();
            $('#petty_cash_other_bank').val("");
        }
        else{
            $('.petty-cash-other-bank').hide();
        }
    });
});
function setRegistrations(from,to,$target){
    var registrations = "";

    if(from!="" && to!=""){
        if(from >=0 && to >=0 ){
            registrations  =  to - from;
            if(from > 0 && to > 0)
                registrations += 1;
        }
    }
    $target.val(registrations); 
}
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
    $("#submitbtn").btnSpinner({ disabled: false });
    var caption = "Record aggiornato con successo.";
    if (form_api.getFormMethod() === "post"){
        form_api.$form[0].reset();
        $(".select2.form-control").val("").trigger('change');
        caption = "Record creato con successo.";
        $('#register_date').val(moment().format('DD/MM/YYYY'));
    }
    else{
        $("input[name='purchase_invoice_lines']").val(responseText.data.purchaseInvoiceLines);
        $("input[name='sales_invoice_lines']").val(responseText.data.salesInvoicelines);
    }
    $("#notify").notification({
        caption: caption,
        type: "success",
        sticky: false,
    });
}
