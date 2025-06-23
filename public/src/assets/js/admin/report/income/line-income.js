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
})
function updateStatus(){
    var status = $("#bulk-status").val();
    if(status==''){
        alert('Seleziona qualsiasi azione.');
        return false;
    }
    if(status== 'create-devpos-invoice'){
        $("#exchangeRateModal").modal('show');
        // Prevent form submission for now
        return false;
    }
    return true;
}