(function($) {
    $.fn.notification = function(options) {
        var opts = $.extend({}, $.fn.notification.defaults, options);
        return this.each(function() {
            $this = $(this);
            var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
            $this.hide();
            $($this).removeAttr("class");
            $this.html('');
            if (o.closebutton) {
                $this.append('<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>');
            }
            if (o.type == "warning") {
                $this.addClass("alert alert-warning alert-dismissible has-icon");
                $this.append('<i class="ri-alert-line me-2 align-middle" aria-hidden="true"></i>');
            } else if (o.type == "information") {
                $this.addClass("alert alert-info alert-dismissible has-icon");
                $this.append('<i class=" ri-information-line me-2 align-middle"></i>');
            } else if (o.type == "error") {
                $this.addClass("alert alert-danger alert-dismissible has-icon");
                $this.append('<i class="ri-alert-line me-2 align-middle"></i>');
            } else if (o.type == "success") {
                $this.addClass("alert alert-success alert-dismissible has-icon");
                $this.append('<i class="ri-checkbox-circle-line me-2 align-middle"></i>');
            } else {
                $this.addClass("alert alert-primary alert-dismissible has-icon");
                $this.append('<i class="mdi-bell-alert-outline me-2 align-middle"></i>');
            }
            $this.append("<strong>" + o.caption + "</strong>");        

            if (o.messages.length > 0) {
                $this.append("<ul class='mb-0 p-0' style='list-style-position:inside;'></ul>");
                for (var msg = 0; msg < o.messages.length; msg++) {
                    $this.find("ul").append("<li id='" + o.messages[msg].id + "'>" + o.messages[msg].message + "</li>");
                }
            }
            if (o.sticky) {
                $this.fadeIn(400, o.onshow);
            } else {
                $this.fadeIn(400, function() {
                    var $obj = $(this);
                    o.onshow();
                    setTimeout(function() {
                        $obj.fadeOut(500, o.onhide);
                    }, o.hidedelay);
                });
            }
        });
    };

    function debug($obj) {
        if (window.console && window.console.log)
            window.console.log('hilight selection count: ' + $obj.size());
    };

    function onnotify_show() {};

    function onnotify_hide() {};
    $.fn.notification.defaults = {
        caption: "Warning!",
        sticky: false,
        hidedelay: 4000,
        type: "error",
        messages: [],
        helpmessage: "Do not show this help again.",
        closebutton: false,
        onshow: onnotify_show,
        onhide: onnotify_hide,
        showhelp: function() {}
    };
})(jQuery);
