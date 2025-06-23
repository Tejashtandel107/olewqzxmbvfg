(function($) {
    $.fn.btnSpinner = function(options) {
        var opts = $.extend({}, $.fn.btnSpinner.defaults, options);
        return this.each(function() {
            $this = $(this);
            var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
            if(o.disabled){
                var $spinner = $(o.spinner).addClass("has-spinner");
                if(o.prepend){
                    $this.prepend($spinner[0].outerHTML + " ");
                }        
                else{
                    $this.append(" " + $spinner[0].outerHTML);
                }
            }
            else{
                $this.find(".has-spinner").remove();
                $this.html($this.html().trim());
            }
            $this.prop('disabled', o.disabled);
        });
    };
    $.fn.btnSpinner.defaults = {
        prepend: false,
        spinner:"<span class='spinner-border spinner-border-sm me-1' role='status' aria-hidden='true'></span>",
        disabled: true,
    };
})(jQuery);