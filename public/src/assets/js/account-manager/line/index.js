$(function () {

    $("#client-id").on("change", function () {
        $company = $("#company-id");
        if (this.value == "") {
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
                                    value.companyName +
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
    /* Register from_date and date-range Date Picker */
    var start = $('#from_date').val();
    var end = $('#to_date').val();
    const date_format="DD/MM/YYYY";

    $('#date_range').daterangepicker({
        autoUpdateInput:true,
        startDate: start,
        endDate: end,
        maxDate: moment(),
        locale: {
            format: date_format,
            cancelLabel:"Annulla",
            applyLabel:'Seleziona',
        },
        ranges: {
            'Today': [moment(), moment()],
            'This Month': [moment().startOf('month'), moment()],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
        $("#from_date").val(picker.startDate.format(date_format));
        $("#to_date").val(picker.endDate.format(date_format));
        $(this).find("#selected_date_range").text(picker.startDate.format(date_format) + ' - ' + picker.endDate.format(date_format));
    });
});

function deleteRecord(url) {
    if(confirm("Sei sicuro di voler eliminare questo azienda?")){
        showLoader();
        $.ajax({
            type: "DELETE",
            url: url,
            dataType: "json",
            success: deleteResponse,
            error: onAjaxCallError
        });
    }
}
function onAjaxCallError(requestObject, error, errorThrown){
    hideLoader();
    showErrorsNotification(requestObject.responseJSON.message,requestObject.responseJSON.errors);
}
function deleteResponse(responseText, statusText) {
    hideLoader();
    $("#notify").notification({caption: "Task eliminata con successo.", type:"success", sticky:false, onhide:function(){
        RefreshLocation();
    }});
}

