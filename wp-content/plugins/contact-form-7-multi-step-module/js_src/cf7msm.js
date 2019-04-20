var cf7msm_ss;
jQuery(document).ready(function($) {
	var posted_data = cf7msm_posted_data;
	var step_field = $("input[name='cf7msm-step']");
	if ( step_field.length == 0 ) {
		//not a multi step form
		return;
	}
	if ( cf7msm_hasSS() ) {
		cf7msm_ss = sessionStorage.getObject( 'cf7msm' );

		//multi step forms
		if (cf7msm_ss != null && step_field.length > 0) {
			var cf7_form = $(step_field[0].form);
			$.each(cf7msm_ss, function(key, val){
				if (key == 'cf7msm_prev_urls') {
					cf7_form.find('.wpcf7-back, .wpcf7-previous').click(function(e) {
						window.location.href = val[step_field.val()];
						e.preventDefault();
					});
				}
			});
		}
		
/* Premium Code Stripped by Freemius */

	}
	else {
		$("input[name='cf7msm-no-ss']").val(1);
		$(".wpcf7-previous").hide();
	}

	
/* Premium Code Stripped by Freemius */


	if (posted_data) {
		var cf7_form = $(step_field[0].form);
		$.each(posted_data, function(key, val){
			if ( key.indexOf('[]') === key.length - 2 ) {
				key = key.substring(0, key.length - 2 );
			}

			
/* Premium Code Stripped by Freemius */


			if ( ( key.indexOf('_') != 0 || key.indexOf('_wpcf7_radio_free_text_') == 0 || key.indexOf('_wpcf7_checkbox_free_text_') == 0 ) && key != 'cf7msm-step') {
				var field = cf7_form.find('*[name="' + key + '"]:not([data-cf7msm-previous])');
				var checkbox_field = cf7_form.find('input[name="' + key + '[]"]:not([data-cf7msm-previous])'); //value is this or this or tihs
				if (field.length > 0) {
					if ( field.prop('type') == 'radio' || field.prop('type') == 'checkbox' ) {
						field.filter(function(){
								return $(this).val() == val;
						}).prop('checked', true);
					}
					else {
						field.val(val);	
					}
				}

				
/* Premium Code Stripped by Freemius */


				else if ( checkbox_field.length > 0 && val.constructor === Array ) {
					//checkbox
					if ( val != '' && val.length > 0  ) {
						$.each(val, function(i, v){
							checkbox_field.filter(function(){
								return $(this).val() == v;
							}).prop('checked', true);
						});	
					}
				}

				
/* Premium Code Stripped by Freemius */

			}
		});
	}


	
/* Premium Code Stripped by Freemius */


	document.addEventListener( 'wpcf7mailsent', function( e ) {
		if ( cf7msm_hasSS() ) {
			var currStep = 0;
			var totalSteps = 0;
			var names = [];
			var currentInputs = {};
			cf7msm_ss = sessionStorage.getObject('cf7msm');
			if ( !cf7msm_ss ) {
				cf7msm_ss = {};
			}
			$.each(e.detail.inputs, function(i){
				var name = e.detail.inputs[i].name;
				var value = e.detail.inputs[i].value;

				//make it compatible with cookie version
				if ( name.indexOf('[]') === name.length - 2 ) {
					// name = name.substring(0, name.length - 2 );
					if ( $.inArray(name, names) === -1 ) {
						currentInputs[name] = [];
					}
					currentInputs[name].push(value);
				}
				else {
					currentInputs[name] = value;
				}

				//figure out prev url
				if ( name === 'cf7msm-step' ) {
					if ( value.indexOf("-") !== -1 ) {
						var steps_prev_urls = {};
						if ( cf7msm_ss && cf7msm_ss.cf7msm_prev_urls ) {
							steps_prev_urls = cf7msm_ss.cf7msm_prev_urls;
						}
						var stepParts = value.split('-');
						currStep = parseInt( stepParts[0] );
						totalSteps = parseInt( stepParts[1] );
						nextUrl = stepParts[2];
						if ( currStep < totalSteps ) {
							//is this the best way to get current url?
							var nextStep = (1+parseInt(currStep)) + '-' + totalSteps;
							steps_prev_urls[nextStep] = window.location.href;
							// hide the success messages on multi-step forms
							$('#' + e.detail.unitTag).find('div.wpcf7-mail-sent-ok').remove();
						}
						else if ( currStep === totalSteps ) {
							// hide the form on final multi-step form
							$('#' + e.detail.unitTag + ' form').children().not('div.wpcf7-response-output').hide();
						}
						cf7msm_ss.cf7msm_prev_urls = steps_prev_urls;
					}
				}
				else {
					names.push(name);
				}
			});
			
/* Premium Code Stripped by Freemius */

			if ( currStep != 0 && currStep === totalSteps ) {
				cf7msm_ss = {};
			}
			sessionStorage.setObject('cf7msm', cf7msm_ss);
		}
	}, false );
});

/**
 * Given 2 arrays, return a unique array
 * https://codegolf.stackexchange.com/questions/17127/array-merge-without-duplicates
 */
function cf7msm_uniqueArray(i,x) {
   var h = {};
   var n = [];
   for (var a = 2; a--; i=x)
      i.map(function(b){
        h[b] = h[b] || n.push(b);
      });
   return n
}

/**
 * check if local storage is usable.
 */
function cf7msm_hasSS() {
    var test = 'test';
    try {
        sessionStorage.setItem(test, test);
        sessionStorage.removeItem(test);
        return true;
    } catch(e) {
        return false;
    }
}
Storage.prototype.setObject = function(key, value) {
    this.setItem(key, JSON.stringify(value));
}

Storage.prototype.getObject = function(key) {
    var value = this.getItem(key);
    return value && JSON.parse(value);
}

/**
 * Escape values when inserting into HTML attributes
 * From SO: https://stackoverflow.com/questions/7753448/how-do-i-escape-quotes-in-html-attribute-values
 */
function quoteattr(s, preserveCR) {
    preserveCR = preserveCR ? '&#13;' : '\n';
    return ('' + s) /* Forces the conversion to string. */
        .replace(/&/g, '&amp;') /* This MUST be the 1st replacement. */
        .replace(/'/g, '&apos;') /* The 4 other predefined entities, required. */
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        /*
        You may add other replacements here for HTML only 
        (but it's not necessary).
        Or for XML, only if the named entities are defined in its DTD.
        */ 
        .replace(/\r\n/g, preserveCR) /* Must be before the next replacement. */
        .replace(/[\r\n]/g, preserveCR);
        ;
}
/**
 * Escape values when using in javascript first.
 * From SO: https://stackoverflow.com/questions/7753448/how-do-i-escape-quotes-in-html-attribute-values
 */
function escapeattr(s) {
    return ('' + s) /* Forces the conversion to string. */
        .replace(/\\/g, '\\\\') /* This MUST be the 1st replacement. */
        .replace(/\t/g, '\\t') /* These 2 replacements protect whitespaces. */
        .replace(/\n/g, '\\n')
        .replace(/\u00A0/g, '\\u00A0') /* Useful but not absolutely necessary. */
        .replace(/&/g, '\\x26') /* These 5 replacements protect from HTML/XML. */
        .replace(/'/g, '\\x27')
        .replace(/"/g, '\\x22')
        .replace(/</g, '\\x3C')
        .replace(/>/g, '\\x3E')
        ;
}