$(function () {
    $('#bulk-status').change(function () {
        var selectedValue = $(this).val();
        if (selectedValue === 'paid') {
            $('.total-paid').removeClass('d-none');  
            $('.bank-fees').removeClass('d-none');  
        } else {
            $('.total-paid').addClass('d-none');  
            $('.bank-fees').addClass('d-none');  
        }
    });
    
    // When select-all checkbox is clicked
    $('#select-all').on('change', function () {
        let isChecked = $(this).prop('checked'); // Check if "Select All" is checked
        $('.form-check-input:not(:disabled)').prop('checked', isChecked);
    });
    
    // When any individual checkbox is clicked
    $('.form-check-input:not(#select-all)').on('change', function () {
        let allChecked = $('.form-check-input:not(:disabled)').length === $('.form-check-input:not(:disabled):checked').length;
        $('#select-all').prop('checked', allChecked);
    });
    
    $(".date_range").datepicker({
        autoclose:true,
        language:'it-IT',
        orientation:'left bottom',
        format: "mm/yyyy",
        viewMode: "months", 
        minViewMode: "months",
        endDate: '+0d'
    });

    $("#account-manager-id").on("change", function () {
        var clientFilterElement = $('#user_id');
        $.ajax({
            url: "/api/account-managers/"+this.value+"?includeClients=1",
            method: "get",
            dataType: "json",
            beforeSend:function(){
                clientFilterElement.empty().trigger("change");
                var option = new Option("Seleziona", "", false, false);
                clientFilterElement.append(option).trigger('change');
            },
            success: function (response,textStatus) {
                if(textStatus=="success"){
                    if(!isUndefined(response.data)){
                        $.each(response.data.clients, function (key, item) {
                            var option = new Option(item.name, item.userId, false, false);
                            clientFilterElement.append(option).trigger('change');
                        });
                    }
                }
            },
            error: function (requestObject, error, errorThrown) {},
        });
    });
    document.getElementById("saveExchangeRate").addEventListener("click", function () {
        let exchangeRate = document.getElementById("modal-exchange-rate").value.trim();
         
        if (exchangeRate === "") {
            document.getElementById("errorName").innerHTML = "È richiesto il tasso di cambio"; 
            return false;
        }
        // Store the exchange rate in the hidden input field
        document.getElementById("exchange-rate-input").value = exchangeRate;
        // Hide the modal
        $("#exchangeRateModal").modal('hide');
    
        // Submit the form
        document.getElementById("bulk-status-form").submit();
    });
    
});
//Single Bulk update action

function updateStatus(){
    if(confirm("sei sicuro di voler aggiornare lo stato?")){
       return true;
    }
    return false;
}
//Multiple Bulk update action

function bulkUpdateStatus() {
    let container = document.getElementById("hiddenInputsContainer");
    container.innerHTML = ""; 
    let totalSelected = 0;

    $('.form-check-input:checked:not(#select-all)').each(function () {
        let hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "ids[]"; 
        hiddenInput.value = $(this).val();
        container.appendChild(hiddenInput);
        totalSelected++;
    });                
 
    if (totalSelected === 0) {
        alert('Seleziona almeno uno Studio.');
        return false;
    }

    // Open modal instead of prompt
    $("#exchangeRateModal").modal('show');

    // Prevent form submission for now
    return false;
}


// Function to handle exporting csv/excel selected checkboxes
function exportExcel(){
    let innerContainer = document.getElementById("hiddenContainer");
    innerContainer.innerHTML = ""; 
    let count =0;
    $('.form-check-input:checked:not(#select-all)').each(function () {
        let hiddenInput = document.createElement("input");
        hiddenInput.type = "hidden";
        hiddenInput.name = "id[]"; 
        hiddenInput.value = $(this).val();
        innerContainer.appendChild(hiddenInput);
        count++;
    });  
    if (count === 0) {
        alert('Seleziona almeno uno Studio.');
        return false;
    } 
}