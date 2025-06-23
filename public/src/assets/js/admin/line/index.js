$(function () {

    $("#client-id").on("change", function () {
        var client_select = $('#company-id');
        $.ajax({
            url: "/api/companies?client_id="+ this.value +"&show_all=true",
            method: "get",
            dataType: "json",
            beforeSend:function(){
                client_select.empty().trigger("change");
                var option = new Option("Seleziona", "", false, false);
                client_select.append(option).trigger('change');
            },
            success: function (response) {
                $.each(response.data, function (key, item) {
                    var option = new Option(item.companyName, item.companyId, false, false);
                    client_select.append(option).trigger('change');
                });
            },
            error: function (requestObject, error, errorThrown) {},
        });
    });
    /* Register from_date and date-range Date Picker */
    const start = $('#from_date').val();
    const end = $('#to_date').val();
    const date_format="DD/MM/YYYY";

    $('#date_range').daterangepicker({
        autoUpdateInput:false,
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

