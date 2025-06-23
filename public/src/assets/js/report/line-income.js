$(function () {
    $(".date_range").datepicker({
        autoclose:true,
        language:'it-IT',
        orientation:'left bottom',
        format: "mm/yyyy",
        viewMode: "months", 
        minViewMode: "months",
        endDate: '-1m'
    });
});