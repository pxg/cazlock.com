/**
 * @fileoverview Project Navigation
 * 
 */
var projectNavigation = function(){

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
		
		// only add scroller if there are enough projects!
		var projectNavigationLists = $("#work-navigation-list ul");
		if (projectNavigationLists.length > 1) {
		
			// start the scroller
			var projectNavigation = $("#work-navigation-list").scrollable({
					items: "#work-navigation-list-clip",
					circular: false,
					speed: 500,
					keyboard: false,
					vertical:true
				});
			var projectNavigationApi = projectNavigation.scrollable();
		
			/*
			<ul class="navigation">
				<li><a href="#">1</a></li>
				<li class="row-end"><a href="#">2</a></li>
			</ul>
			*/
			var projectNavigationNav = $("<ul />").addClass('navigation').insertBefore('#work-navigation-list');
			projectNavigationLists.each(function(counter){
				var item = $("<a />")
					.attr('href', '#')
					.text(counter+1)
					.appendTo(projectNavigationNav)
					.wrap('<li />')
					.bind('click', function(e){
						e.preventDefault();
						this.blur();
						projectNavigationApi.seekTo($(this).text()-1);
					});
			});
			var projectNavigationNavLinks = $(projectNavigationNav).find('a');
			projectNavigationNav.find('li:last-child').addClass('row-end');

		
			// update the navigation links when the scroller moves
			projectNavigationApi.onBeforeSeek(function(e, i) {
				projectNavigationNavLinks.removeClass('selected');
				projectNavigationNavLinks.filter(":eq("+i+")").addClass('selected');
			});
			
			// if the selected project is on a later page, scroll to it already
			var $selectedProject = $("#work-navigation-list").find('li a.selected');
			if ($selectedProject.length > 0) {
				// find the index of the parent UL
				var parentIndex = $selectedProject.parent().parent().index();
				if (parentIndex != 0) {
					projectNavigationApi.seekTo(parentIndex,0);
				}
			}
		}
	};
	
	
	
	/*
	 * 
	 */
	return {
		init: init
	};
	
}();

