/**
 * Site global JS file
 *
 * @author PG
 */

	/*
	 * Set debug mode
	 */
		LibMan.setDebugMode(false);


	/**
	 * on Dom ready functionality
	 */
		$(document).ready(function() {


			// debug
			LibMan.debug('JS Library loaded');


			// add an extra class to the <body> element for JS-only styling
			$("body").addClass("js");


			// add external rel for blog link
			$("#menu-blog").attr("rel", "external");


			// open links in a new window
			links.init('a[rel="external"]');


			// insert template image overlay for layout testing
			// Development only, remove for live
			var gridSettings = {
				imgExt: "jpg",
				gridPos: "center top",
				combineIdAndClass: false
			};
			//$.gridOverlay(LibMan.path + "../../_templates/img/", gridSettings);


			// print links in footer
			//printFootnoteLinks.init("content","wrapper");


			// toggle default form text
			//toggleDefaultText.init('input.default');


			// Fix PNGs for IE6
			//$('img.png-24, .png-fix').supersleight({shim: LibMan.path + '/lib/png/x.gif'});


			// project navigation
			projectNavigation.init();

			/*
			 * page-specific JS
			 */
			var $body = $("body");
			if ($body.attr('id') == "home") {
				homeHero.init();
			}
		});


	/*
	 * Window load calls for all pages
	 */
		$(window).load(function() {
		});