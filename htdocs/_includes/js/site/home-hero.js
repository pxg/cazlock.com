/**
 * @fileoverview Collection Accordion
 * 
 */
var homeHero = function(){

	/**
	 * The options passed through to this function
	 *
	 * @var Object
	 * @private
	 */
	var options = {

	};
	
	
	/**
	 * Initialise the functionality
	 * @param {Object} options The initialisation options
	 * @return void
	 * @public
	 */
	var init = function(initOptions) {
		
		// start the scroller
		var homeScroller = $("#featured-projects").scrollable({
				items: "#featured-projects-clip",
				circular: false,
				speed: 500,
				keyboard: false
			}).autoscroll({ 
				autoplay: true,
				interval: 5000 
			});
		var homeScrollerApi = homeScroller.scrollable();
		
		// get the navigation links and set listeners to update the scroller
		var homeScrollerNavLinks = $("#featured-project-nav li a");
		homeScrollerNavLinks.bind('click', function(e){
			e.preventDefault();
			this.blur();
			homeScrollerApi.seekTo($(this).text()-1);
			homeScrollerApi.stop();
		});

		
		// update the navigation links when the scroller moves
		homeScrollerApi.onSeek(function(e, i) {
			homeScrollerNavLinks.removeClass('selected');
			homeScrollerNavLinks.filter(":eq("+homeScrollerApi.getIndex()+")").addClass('selected');
		});
	};
	
	
	
	/*
	 * 
	 */
	return {
		init: init
	};
	
}();

