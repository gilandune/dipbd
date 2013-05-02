/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function clear_form_elements(form) {
    $(form).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });
}

function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}


(function( $ ){
  // Simple wrapper around jQuery animate to simplify animating progress from your app
  // Inputs: Progress as a percent, Callback
  // TODO: Add options and jQuery UI support.
  $.fn.animateProgress = function(progress, callback) {    
    return this.each(function() {
      $(this).animate({
        width: progress+'%'
      }, {
        duration: 2000, 
        
        // swing or linear
        easing: 'swing',

        // this gets called every step of the animation, and updates the label
        step: function( progress ){
          var labelEl = $('.ui-label', this),
              valueEl = $('.value', labelEl);
          
          if (Math.ceil(progress) < 20 && $('.ui-label', this).is(":visible")) {
            labelEl.hide();
          }else{
            if (labelEl.is(":hidden")) {
              labelEl.fadeIn();
            };
          }
          
          if (Math.ceil(progress) == 100) {
            labelEl.text('Completed');
            setTimeout(function() {
              labelEl.fadeOut();
            }, 1000);
          }else{
            valueEl.text(Math.ceil(progress) + '%');
          }
        },
        complete: function(scope, i, elem) {
          if (callback) {
            callback.call(this, i, elem );
          };
        }
      });
    });
  };
})( jQuery );


function fake_load(title) {
    var cur_value = 1,
        progress;

    // Make a loader.
    var loader = $.pnotify({
        title: title,
        text: "<div class=\"progress_bar\" />",
        icon: 'picon picon-throbber',
        hide: false,
        closer: false,
        sticker: false,
        history: false,
        before_open: function(pnotify) {
            progress = pnotify.find("div.progress_bar");
            progress.progressbar({
                value: cur_value
            });
            // Pretend to do something.
            var timer = setInterval(function() {
                if (cur_value >= 100) {
                    // Remove the interval.
                    window.clearInterval(timer);
                    loader.pnotify_remove();
                    return;
                }
                //cur_value += Math.ceil(3 * ((100 - cur_value) / 100));
                cur_value += .3;
                progress.progressbar('option', 'value', cur_value);
            }, 2);
        }
    });
    
    return loader;
}


// Automatically cancel unfinished ajax requests 
// when the user navigates elsewhere.
//(function($) {
    var xhrPool = [];
    $(document).ajaxSend(function(e, jqXHR, options){
        xhrPool.push(jqXHR);

    });
    
    $(document).ajaxComplete(function(e, jqXHR, options) {
        xhrPool = $.grep(xhrPool, function(x){return x!=jqXHR});
    });
    
    var abort_xhrcallbacks = function() {
        $.each(xhrPool, function(idx, jqXHR) {
            jqXHR.abort();
        });
    };

    //sin usar
    var oldbeforeunload = window.onbeforeunload;
    window.onbeforeunload = function() {
        var r = oldbeforeunload ? oldbeforeunload() : undefined;
        if (r == undefined) {
            // only cancel requests if there is no prompt to stay on the page
            // if there is a prompt, it will likely give the requests enough time to finish
            abort();
        }
        return r;
    }
//})(jQuery);

function analysis_month(month) {
    switch(month) {
        case '1':
            return "&nbsp;&nbsp;Mes de Analisis: Febrero 2013";
            break;
        case '2':
            return "&nbsp;&nbsp;Mes de Analisis: Marzo 2013";
            break;
        case '3':
            return "&nbsp;&nbsp;Mes de Analisis: Abril 2013";
            break;
    }
}

function ha_analysis_month(month) {
    switch(month) {
        case '1':
            return "&nbsp;&nbsp;Mes de Analisis: Marzo 2013";
            break;
        case '2':
            return "&nbsp;&nbsp;Mes de Analisis: Abril 2013";
            break;
        case '3':
            return "&nbsp;&nbsp;Mes de Analisis: Mayo 2013";
            break;
        case '4':
            return "&nbsp;&nbsp;Mes de Analisis: Junio 2013";
            break;
    }
}
