/**
 * @fileoverview Toggle the default text of a text field on click
 * 
 * @author PG @ Pirata [piratalondon.com]
 * 
 */
var toggleDefaultText = function(){
	
	
	/*
	 * Array to contain the default text values of all fields
	 * 
	 * @var Array
	 */
	var defaultTextValues = [];
    
    
    /**
     * Initialise the functionality
	 * 
     * @return void
     * @public
     */
    var init = function(elementArray) {
	
		elementArray = $(elementArray);
	
        // add default text remover for each text area
		$(elementArray).each(function(counter){
			
			var el = elementArray[counter];
			defaultTextValues[counter] = $(el).attr('value');
			
			$(el).addClass('inactive');
			
	        $(el).bind('focus', function(e){
	            toggle(counter, el, true);
	        });
	        $(el).bind('blur', function(e){
	            toggle(counter, el);
	        });
		});
	};



    /**
     * Handle click event - toggle default text for form field
     * @return void
     * @private
     */
    var toggle = function(counter, textField, focus) {
	
        // find the text field in question
        var textField = $(textField);

		// condition : if we are focusing, hide default text
		if(!!focus) {
		    
		    // condition : if default text is set, hide it
		    if (textField.attr('value') == defaultTextValues[counter]) {
		        textField.attr('value', '');
		    }
			
			textField.removeClass('inactive');
		
		} else {
		    
		    // condition : if default text is set, hide it
		    if (textField.attr('value') == '' || textField.attr('value') == defaultTextValues[counter]) {
		        textField.attr('value', defaultTextValues[counter]).addClass('inactive');
		    }
		}
    };	

    
    
    /**
     * Return value, expose certain methods above
     */
    return {
        init: init
    };
}();


/*
 * Example call:

 $(document).ready(function(){
     
        toggleDefaultText.init('');
  });

 */