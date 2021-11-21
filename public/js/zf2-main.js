/**
 * This file is part of the Simple demo web-project with REST Full API for Mobile.
 *
 * This project is no longer maintained.
 * The project is written in Zend Framework 2 Release.
 *
 * @link https://github.com/scorpion3dd
 * @copyright Copyright (c) 2016-2021 Denis Puzik <scorpion3dd@gmail.com>
 */

/**
 * Main
 */
$( function() {
    localStorage.setItem('console.log', 'true');
    Zf2Console.consoleColorWeightLog('Main start');

    // Zf2ConsoleWrite();

    $( ".widget input[type=submit], .widget a, .widget button, .ui-button, #searchbutton, #customercall" ).button();

    $( ".ui-button" ).click( function( event ) {
        Zf2Console.consoleLog('ui-button click');
    } );

    console.log(location.pathname);
    if (location.pathname.indexOf("mobile") != -1) {
        Zf2Console.consoleColorWeightLog('Mobile ready start');

        let location = window.location;
        let gatewayURL = location.origin + '/mobile/r1';

        $('#custlistview li[role!=heading]').remove();
        $('#errorMessage').ajaxError(ajaxErrorHandler);
        jQuery.support.cors = true;
        $('#customers li[role!=heading]').remove();




        $( "#searchbutton" ).parent().click(function() {
            getSearchquery(gatewayURL);
        });
        $( "#getListBtn" ).click(function() {
            getCustomers(gatewayURL);
        });
        $(document).bind('pagebeforechange', function(e, data) {
            Zf2Console.consoleColorWeightLog('bind pagebeforechange');
            pageBeforeChange(data);
        });


        // https://stackoverflow.com/questions/14354040/jquery-1-9-live-is-not-a-function
        // $(":jqmData(role='page')").live('pageshow', function(e) {
        $(":jqmData(role='page')").on('pageshow', 'body', function(e){
            Zf2Console.consoleColorWeightLog('live pageshow');
            grabId(e);
        });

        $.fn.buttonMarkup = function(options) {
            Zf2Console.consoleColorWeightLog('$.fn.buttonMarkup');
            let attachEvents = function() {
                Zf2Console.consoleColorWeightLog('function attachEvents');
                let hoverDelay = $.mobile.buttonMarkup.hoverDelay, hov, foc;

                $(document).bind({
                    "vmousedown vmousecancel vmouseup vmouseover vmouseout focus blur scrollstart" : function(event) {
                        Zf2Console.consoleColorWeightLog('bind vmousedown ...');
                        let theme, $btn = $(closestEnabledButton(event.target)), evt = event.type;

                        if ($btn.length) {
                            theme = $btn.attr("data-" + $.mobile.ns + "theme");

                            if (evt === "vmousedown") {
                                if ($.support.touch) {
                                    hov = setTimeout(function() {
                                        Zf2Console.consoleColorWeightLog('hov');
                                        $btn.removeClass("ui-btn-up-" + theme).addClass("ui-btn-down-" + theme);
                                    }, hoverDelay);
                                } else {
                                    $btn.removeClass("ui-btn-up-" + theme).addClass("ui-btn-down-" + theme);
                                }
                            } else if (evt === "vmousecancel" || evt === "vmouseup") {
                                $btn.removeClass("ui-btn-down-" + theme).addClass("ui-btn-up-" + theme);
                            } else if (evt === "vmouseover" || evt === "focus") {
                                if ($.support.touch) {
                                    foc = setTimeout(function() {
                                        Zf2Console.consoleColorWeightLog('foc');
                                        $btn.removeClass("ui-btn-up-" + theme).addClass("ui-btn-hover-" + theme);
                                    }, hoverDelay);
                                } else {
                                    $btn.removeClass("ui-btn-up-" + theme).addClass("ui-btn-hover-" + theme);
                                }
                            } else if (evt === "vmouseout"
                                || evt === "blur"
                                || evt === "scrollstart") {
                                $btn.removeClass("ui-btn-hover-" + theme + " ui-btn-down-" + theme).addClass("ui-btn-up-" + theme);
                                if (hov) {
                                    clearTimeout(hov);
                                }
                                if (foc) {
                                    clearTimeout(foc);
                                }
                            }
                        }
                    },
                    "focusin focus" : function(event) {
                        Zf2Console.consoleColorWeightLog('bind vmousedown ... focusin focus');
                        $(closestEnabledButton(event.target)).addClass($.mobile.focusClass);
                    },
                    "focusout blur" : function(event) {
                        Zf2Console.consoleColorWeightLog('bind vmousedown ... focusin blur');
                        $(closestEnabledButton(event.target)).removeClass($.mobile.focusClass);
                    }
                });
                attachEvents = null;
            };
            buttonMarkup(options, attachEvents);
        };

        $.fn.buttonMarkup.defaults = {
            corners : true,
            shadow : true,
            iconshadow : true,
            iconsize : 18,
            wrapperEls : "span"
        };

        widget('mobile.tabbar');

        Zf2Console.consoleColorWeightLog('Mobile ready finish');
    }

    Zf2Console.consoleColorWeightLog('Main finish');
} );


(function($) {
    $(document).bind('pagecreate create', function(e) {
        Zf2Console.consoleColorWeightLog('bind pagecreate create');

        return $(e.target).find(":jqmData(role='tabbar')").tabbar();
    });
});