/**
* Theme: Hyper - Responsive Bootstrap 5 Admin Dashboard
* Author: Coderthemes
* Module/App: Main Js
*/


(function ($) {

    'use strict';

    // Bootstrap Components
    function initComponents() {
        // Popovers
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

        // Tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    }

    // Portlet Widget (Card Reload, Collapse, and Delete)
    function initPortletCard() {

        var portletIdentifier = ".card"
        var portletCloser = '.card a[data-bs-toggle="remove"]'
        var portletRefresher = '.card a[data-bs-toggle="reload"]'
        let self = this

        // Panel closest
        $(document).on("click", portletCloser, function (ev) {
            ev.preventDefault();
            var $portlet = $(this).closest(portletIdentifier);
            var $portlet_parent = $portlet.parent();
            $portlet.remove();
            if ($portlet_parent.children().length == 0) {
                $portlet_parent.remove();
            }
        });

        // Panel Reload
        $(document).on("click", portletRefresher, function (ev) {
            ev.preventDefault();
            var $portlet = $(this).closest(portletIdentifier);
            // This is just a simulation, nothing is going to be reloaded
            $portlet.append('<div class="card-disabled"><div class="card-portlets-loader"></div></div>');
            var $pd = $portlet.find('.card-disabled');
            setTimeout(function () {
                $pd.fadeOut('fast', function () {
                    $pd.remove();
                });
            }, 500 + 300 * (Math.random() * 5));
        });
    }

    //  Multi Dropdown
    function initMultiDropdown() {
        $('.dropdown-menu a.dropdown-toggle').on('click', function () {
            var dropdown = $(this).next('.dropdown-menu');
            var otherDropdown = $(this).parent().parent().find('.dropdown-menu').not(dropdown);
            otherDropdown.removeClass('show')
            otherDropdown.parent().find('.dropdown-toggle').removeClass('show')
            return false;
        });
    }

    // Left Sidebar Menu (Vertical Menu)
    function initLeftSidebar() {
        var self = this;

        if ($(".side-nav").length) {
            var navCollapse = $('.side-nav li .collapse');
            var navToggle = $(".side-nav li [data-bs-toggle='collapse']");
            navToggle.on('click', function (e) {
                return false;
            });

            // open one menu at a time only
            navCollapse.on({
                'show.bs.collapse': function (event) {
                    var parent = $(event.target).parents('.collapse.show');
                    $('.side-nav .collapse.show').not(event.target).not(parent).collapse('hide');
                }
            });

            // activate the menu in left side bar (Vertical Menu) based on url
            $(".side-nav a").each(function () {
                var pageUrl = window.location.href.split(/[?#]/)[0];
                if (pageUrl.indexOf(this.href)>-1) {
                    $(this).addClass("active");
                    $(this).parent().addClass("menuitem-active");
                    $(this).parent().parent().parent().addClass("show");
                    $(this).parent().parent().parent().parent().addClass("menuitem-active"); // add active to li of the current link

                    var firstLevelParent = $(this).parent().parent().parent().parent().parent().parent();
                    if (firstLevelParent.attr('id') !== 'sidebar-menu') firstLevelParent.addClass("show");

                    $(this).parent().parent().parent().parent().parent().parent().parent().addClass("menuitem-active");

                    var secondLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (secondLevelParent.attr('id') !== 'wrapper') secondLevelParent.addClass("show");

                    var upperLevelParent = $(this).parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
                    if (!upperLevelParent.is('body')) upperLevelParent.addClass("menuitem-active");
                }
            });


            setTimeout(function () {
                var activatedItem = document.querySelector('li.menuitem-active .active');
                if (activatedItem != null) {
                    var simplebarContent = document.querySelector('.leftside-menu .simplebar-content-wrapper');
                    var offset = activatedItem.offsetTop - 300;
                    if (simplebarContent && offset > 100) {
                        scrollTo(simplebarContent, offset, 600);
                    }
                }
            }, 200);

            // scrollTo (Left Side Bar Active Menu)
            function easeInOutQuad(t, b, c, d) {
                t /= d / 2;
                if (t < 1) return c / 2 * t * t + b;
                t--;
                return -c / 2 * (t * (t - 2) - 1) + b;
            }
            function scrollTo(element, to, duration) {
                var start = element.scrollTop, change = to - start, currentTime = 0, increment = 20;
                var animateScroll = function () {
                    currentTime += increment;
                    var val = easeInOutQuad(currentTime, start, change, duration);
                    element.scrollTop = val;
                    if (currentTime < duration) {
                        setTimeout(animateScroll, increment);
                    }
                };
                animateScroll();
            }
        }
    }

    // Topbar Menu (HOrizontal Menu)
    function initTopbarMenu() {
        if ($('.navbar-nav').length) {
            $('.navbar-nav li a').each(function () {
                var pageUrl = window.location.href.split(/[?#]/)[0];
                if (this.href == pageUrl) {
                    $(this).addClass('active');
                    $(this).parent().parent().addClass('active'); // add active to li of the current link
                    $(this).parent().parent().parent().parent().addClass('active');
                    $(this).parent().parent().parent().parent().parent().parent().addClass('active');
                }
            });

            // Topbar - main menu
            $('.navbar-toggle').on('click', function () {
                $(this).toggleClass('open');
                $('#navigation').slideToggle(400);
            });
        }
    }

    // Show/Hide Password
    function initShowHidePassword() {
        $("[data-password]").on('click', function () {
            if ($(this).attr('data-password') == "false") {
                $(this).siblings("input").attr("type", "text");
                $(this).attr('data-password', 'true');
                $(this).addClass("show-password");
            } else {
                $(this).siblings("input").attr("type", "password");
                $(this).attr('data-password', 'false');
                $(this).removeClass("show-password");
            }
        });
    }

    // Form Advance
    function initFormAdvance() {
        // Select2
        if (jQuery().select2) {
            $('[data-toggle="select2"]').select2();
        }
    }
    function init() {
        initComponents();
        initPortletCard();
        initMultiDropdown();
        initLeftSidebar()
        initTopbarMenu();
        initShowHidePassword();
        initFormAdvance();
    }
    init();
})(jQuery)

function isUndefined(val){
	return (typeof val === 'undefined');
}
function showModal() {
    var $modal = $('#modalwrp #modalmain').modal();
    $modal.modal('show');
}
function hideModal() {
    $('#modalmain').modal('hide');
}
function showLoader() {
    $(".preloader-backdrop").show();
}
function hideLoader() {
    $(".preloader-backdrop").hide();
}
function RefreshLocation() {
    location.reload();
}
function redirectAfter(url, miliseconds) {
    setTimeout(function() {
        redirectToUrl(url);
    }, miliseconds);
}
function redirectToUrl(url){
    window.location.href = url;
}
function openPopCenter(url) {
    var leftPosition, topPosition;
    var screenwidth = window.screen.width;
    var screenheight = window.screen.height;
    var width = (screenwidth / 2);
    var height = (screenheight / 2);
    leftPosition = (screenwidth / 2) - ((width / 2) + 10);
    topPosition = (screenheight / 2) - ((height / 2) + 50);
    window.open(url, "Window2", "height=" + height + ",width=" + width + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition);
}
function numberFormat(num){
    return (Math.round(num * 100) / 100).toFixed(2);
}

function scrollTop(){
    window.scrollTo(0, 0);
}
function showErrorsNotification(caption,errors){
    var msgs    =   [];
    var caption =   (!isUndefined(caption)) ? caption: "Siamo spiacenti, abbiamo riscontrato un errore. Per favore riprova.";
    
    if(!isUndefined(errors)){
        if(Object.keys(errors).length>1){
            jQuery.each(errors, function(i, val) {
                msgs.push({ message: val });
            });
            caption = "Trovati uno o più input non validi.";
        }
    }
    $("#notify").notification({caption: caption,messages:msgs,sticky:true});
}

/* Ajax Functions Starts */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function ajaxCall(interfaceUrl, interfaceID, loaderID, callBack) {
    showLoader();
    $.ajax({
        type: "GET",
        url: interfaceUrl,
        cache: false,
        dataType: "html",
        success: function(data, textStatus) {
            if (typeof(interfaceID) == 'string') {
                $("#" + interfaceID).hide();
                $("#" + interfaceID).html(data);
                $("#" + interfaceID).fadeIn();
            } else {
                $(interfaceID).html(data);
            }
            hideLoader();
            if (callBack == undefined) {
                ajaxCallback(interfaceID);
            } else {
                callBack(interfaceID);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            hideLoader();
            console.log("ERROR: " + XMLHttpRequest.statusText + " " + Url);
            console.log("ERROR: " + XMLHttpRequest.errorThrown + " " + Url);
        }
    });
}
function ajaxUpdate(Url, data, callBack, method) {
    if (method == undefined || method == '') {
        method = "POST";
    }
    showLoader();
    $.ajax({
        type: method,
        url: Url,
        cache: false,
        data: data,
        dataType: "json",
        success: callBack,
        
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            hideLoader();
            console.log("ERROR: " + XMLHttpRequest.statusText + " " + Url);
            console.log("ERROR: " + XMLHttpRequest.errorThrown + " " + Url);
        }
    });
}
function ajaxUpdateSimple(Url, data, callBack, method) {
    if (method == undefined || method == '') {
        method = "POST";
    }
    $.ajax({
        type: method,
        url: Url,
        cache: false,
        data: data,
        dataType: "json",
        success: callBack,
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            hideLoader();
            console.log("ERROR: " + XMLHttpRequest.statusText + " " + Url);
            console.log("ERROR: " + XMLHttpRequest.errorThrown + " " + Url);
        }
    });
}
function ajaxPopup(interfaceUrl, callBack,data,method) {
    showLoader();
    if (method == undefined || method == '') {
        method = "GET";
    }
    $.ajax({
        type: method,
        url: interfaceUrl,
        cache: false,
        data: data,
        dataType: "html",
        success: function(data, textStatus) {
            $("#modalwrp").html(data);
            hideLoader();
            showModal();
            if (callBack) callBack();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            hideLoader();
            console.log("ERROR: " + XMLHttpRequest.statusText + " " + interfaceUrl);
        }
    });
}
function ajaxFetch(interfaceUrl, data, callBack, errorCallback, method, loader) {
    if (loader == undefined || loader == true) {
        showLoader();
    }
    if (method == undefined || method == '') {
        method = "POST";
    }
    $.ajax({
        type: method,
        url: interfaceUrl,
        data: data,
        dataType: "html",
        success: callBack,
        error: errorCallback
    });
}
