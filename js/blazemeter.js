var hints = [];

jQuery(document).ready(function($) {

    // sliders for max concurrent users

    $( "#blaze-anon-slide" ).slider({
        animate: true,
        range: "min",
        value: $("#slider-anon-max-users-result").text(),
        min: 0,
        max: 5000,
        step: 10,

        //this gets a live reading of the value and prints it on the page
        slide: function( event, ui ) {
            $("#slider-anon-max-users-result").html(ui.value);
        },

        //this updates the hidden form field so we can submit the data using a form
        change: function(event, ui) {
            $("#blaze-anon-max-users").attr("value", ui.value);
        }

    });

    $( "#blaze-auth-slide" ).slider({
        animate: true,
        range: "min",
        value: $("#slider-auth-max-users-result").text(),
        min: 0,
        max: 5000,
        step: 10,

        //this gets a live reading of the value and prints it on the page
        slide: function( event, ui ) {
            $("#slider-auth-max-users-result").html(ui.value);
        },

        //this updates the hidden form field so we can submit the data using a form
        change: function(event, ui) {
            $("#blaze-auth-max-users").attr('value', ui.value);
        }

    });

    // add the "Add Page" buttons for new pages

    $( '#blaze-anon-pages-add' ).on("click", function(e) {
	var no = $(".blaze_anon_pages").length + 1;
	
	var input = '<input name="blaze_setting[blaze_anon_pages][]" id="blaze_anon_pages_' + no + '" '
	    + 'type="text" class="blaze_anon_pages" value="" '
	    + 'onkeyup="showPageHints(this)" />';
	$( '#blaze-anon-pages-wrapper' ).append(input);
    });


    $( '#blaze-auth-pages-add' ).on("click", function(e) {
	var no = $(".blaze_auth_pages").length + 1;
	
	var input = '<input name="blaze_setting[blaze_auth_pages][]" id="blaze_auth_pages_' + no + '" '
	    + 'type="text" class="blaze_auth_pages" value=""'
	    + 'onkeyup="showPageHints(this)" />';
	$( '#blaze-auth-pages-wrapper' ).append(input);
    });

    // Handle modal dialogs in config page

    $( '#blaze-signup' ).click( function() {
        $('#blazemeter-signup-modal').modal({
            closeHTML: '<a href="#" title="Close" class="modal-close">X</a>',
            height: 680,
            minHeight: 680
        });
        
        
        window.setTimeout(function() {
            blazemeter_user_key_check();
        }, 5000);
    });

    $( '#blaze-login' ).click( function() {        
        $('#blazemeter-login-modal').modal({
            closeHTML: '<a href="#" title="Close" class="modal-close">X</a>',
            height: 450,
            minHeight: 450
        });
        
        window.setTimeout(function() {
            blazemeter_user_key_check();
        }, 5000);
    });
    
    function blazemeter_user_key_check() {
        $.ajax({
            type: "get",
            url: ajaxurl,
            data: {
                action: "blazemeter_user_key"
            },
            dataType: "json",
            success: function(data) {
                if(data.status) {
                    //Userkey is sucessfully stored
                    $('.modal-close').click();
                    $('#blaze-signup').hide();
                    $('#blaze-login').hide();
                    $('#edit-meta-userkey').val('user key is stored');
                    $('#edit-meta-userkey-holder').val('user key is stored');
		    
		    window.location.href = window.location;
                    
                    return;
                }
                 
                // Failed or still in progress: Retry below with recursion.
                //Login/registration is not finished yet, check again after 2 sec.
                window.setTimeout(function() {
                    blazemeter_user_key_check();
                }, 2000);
            }
        });
    }
    
    jQuery("#slider-anon-max-users-result").html(jQuery("#blaze-anon-slide").slider("value"));
    jQuery("#blaze-anon-max-users").attr("value", jQuery("#blaze-anon-slide").slider("value"));
    
    jQuery("#slider-auth-max-users-result").html(jQuery("#blaze-auth-slide").slider("value"));
    jQuery("#blaze-auth-max-users").attr('value', jQuery("#blaze-auth-slide").slider("value"));
    
    jQuery.ajax({
       type: "post",
       url: ajaxurl,
       data: {
	   action: "blazemeter_datalist"
       },
       dataType: "json",
       success: onLoadHints,
       error: onBlazemeterError
   });
});


function showCover() {
    jQuery("body").css("overflow", "hidden");
    
    jQuery("#blaze_cover").width = jQuery("body").width();
    jQuery("#blaze_cover").height = jQuery("body").height();
    
    jQuery("#blaze_loader").css("left", jQuery(window).width() / 2 - jQuery("#blaze_loader").width() / 2);
    jQuery("#blaze_loader").css("top", jQuery(window).height() / 2 - jQuery("#blaze_loader").height() / 2);

    jQuery("#blaze_loader").show();
    jQuery("#blaze_cover").show();
}

function hideCover() {
    jQuery("body").css("overflow", "auto");
    
    jQuery("#blaze_loader").hide();
    jQuery("#blaze_cover").hide();
}



function blazemeter_submit() {
    showCover();
    
    jQuery.ajax({
        type: "post",
        async: false,
        url: "options.php",
        data: jQuery("#blazemeter-form").serialize(),
        success: onBlazemeterSave,
        error: onBlazemeterError
    });
}

function onBlazemeterSave() {
    
    jQuery.ajax({
        type: "post",
        url: ajaxurl,
        data: {
            action: "blazemeter_submit"
        },
        dataType: "json",
        success: onBlazemeterSubmit,
        error: onBlazemeterError
    });
    
    hideCover();
}

function onBlazemeterSubmit(res) {
//    alert(res.type + ": " + res.message);
    
    jQuery("#message").removeClass("error");
    jQuery("#message").removeClass("success");
    
    jQuery("#message").hide();
    jQuery("#message").addClass(res.type);
    jQuery("#message").html(res.message);
    
    jQuery("#message").show();
    jQuery("#message").scroll();
    
    if(res.response.response_code != "200" || !!res.response.error)
    {
        hideCover();
        return;
    }

    jQuery("#blaze_meta_test_id").val(res.response.test_id);
    hideCover();
}

function onBlazemeterError(jqXHR, textStatus, errorThrown) {
    hideCover();
    
    //alert(textStatus + ': ' + errorThrown);
}



function blazemeter_cleanup() {
    showCover();
    jQuery.ajax({
        type: "post",
        async: false,
        url: ajaxurl,
        data: {
            action: "blazemeter_clear"
        },
        dataType: "json",
        success: onBlazemeterCleanup,
        error: onBlazemeterError
    });
}

function onBlazemeterCleanup(res) {
    if(!res)
        return;
    
    jQuery("#message").removeClass("error");
    jQuery("#message").removeClass("success");
    
    jQuery("#message").hide();
    jQuery("#message").addClass(res.type);
    jQuery("#message").html(res.message);
    
    jQuery("#message").show();
    jQuery("#message").scroll();
    
    hideCover();
    
    window.setTimeout(function() {
        var anchor = window.location.href.indexOf("#");
        
        window.location.href = anchor == -1 ? window.location : window.location.href.substr(0, anchor);
    }, 2000);
}



function blazemeter_open_test(blazemeter_url) {
    var test_id = parseInt(jQuery("#blaze_meta_test_id").val());
    
    if(isNaN(test_id) || test_id <= 0)
    {
        window.open(blazemeter_url + '/cloud/testing/load/home');
        return;
    }

    window.open(blazemeter_url + "/node/" + test_id + "/find");
}

function blazemeter_change_scenario(btn, scenario) {
    jQuery("#blaze_meta_scenario").val(scenario);
    
    jQuery("#scenario_load").removeClass("selected");
    jQuery("#scenario_stress").removeClass("selected");
    jQuery("#scenario_extreme").removeClass("selected");

    
    jQuery(btn).addClass("selected");
}

function showModifier(sender, state) {
    var value = parseInt(jQuery(sender).html());
    
    if(isNaN(value) || value <= 0 || value > 5000)
        value = 0;
    
    jQuery(sender).hide();
    jQuery("#blaze_" + state + "_max_users_modifier").val(value);
    jQuery("#blaze_" + state + "_max_users_modifier").show();
    jQuery("#blaze_" + state + "_max_users_modifier").trigger("focus");
}

function keyModifier(e, state) {
    var code = e.keyCode ? e.keyCode : e.which;
    
    switch(code) {
        case 13:
            jQuery("#blaze_" + state + "_max_users_modifier").trigger("blur");
            break;
        case 27:	    
            var value = parseInt(jQuery("#slider-" + state + "-max-users-result").html());
    
            if(isNaN(value) || value <= 0 || value > 5000)
                value = 0;
            
            jQuery("#blaze_" + state + "_max_users_modifier").val(value);
            jQuery("#blaze-" + state + "-max-users").val(value);
            jQuery("#blaze_" + state + "_max_users_modifier").trigger("blur");
            break;
    }
}

function storeModifier(sender, state) {
    jQuery(sender).hide();
    
    var value = parseInt(jQuery(sender).val());
    
    if(isNaN(value) || value <= 0 || value > 5000)
        value = 0;
    
    jQuery("#blaze-" + state + "-slide").slider('value', value);    
    jQuery("#slider-" + state + "-max-users-result").html(value);
    jQuery("#blaze-" + state + "-max-users").val(value);
    
    jQuery("#slider-" + state + "-max-users-result").show();
}

function showTip(tipcode) {
    tipcode = tipcode.replace(' ', '-').replace('-', '_');
    
    var url = tip_ajax_url + "tip_" + tipcode + '.html';
    
    jQuery.ajax({
	type: "get",
	url: url,
	success: onTipShow,
	error: onBlazemeterError
    });
}

function onTipShow(res) {
    if(res.length == 0)
	return;
    
    // Setting tip contents
    jQuery("#blaze_tip").html(res);
    
    // Get one of the scenario buttons top, they are all on same top
    var pos = jQuery("#scenario_extreme").position();
    var left = Math.round(pos.left) + jQuery("#scenario_extreme").width() + 48;
    var top = Math.round(pos.top) + jQuery("#scenario_extreme").height() / 2;
    
    top -= jQuery("#blaze_tip").height() / 2;
    
    // Correct positioning of the tip
    jQuery("#blaze_tip").css("left", left);
    jQuery("#blaze_tip").css("top", top);
    
    
    jQuery("#blaze_tip").show();
}

function hideTip() {
    jQuery("#blaze_tip").hide();
}

function showPageHints(obj) {
    //var phrase = jQuery(obj).val();

    jQuery(obj).autocomplete({
	source: hints,
	open: function (event, ui) {
	    jQuery('.ui-autocomplete').css('z-index', '9999');
	}
    });
}

function onLoadHints(res) {
    if(!res || res.length == 0)
	return;
    
    hints = [];
    
    jQuery.each(res, function() {
	hints.push(this.url + ' - ' + this.title + ' (' + this.type + ')');
    });
}