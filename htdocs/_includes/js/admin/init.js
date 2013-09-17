/**
 * Admin global JS file
 * @author Richard Hallows
 */

/**
 * Javascript CSS namespace
 */
$('html').addClass('js');

/**
 * Outside of jQuery on Dom ready
 */
	tinyMCE.init({

		// General options
	 	mode: "textareas",
	 	theme: "advanced",
	 	width: 320,
	 	height: 120,
	 	cleanup: true,
	 	plugins: "tabfocus,style,paste,fullscreen,inlinepopups",
	 	valid_elements: "a[href|target|rel|title],strong,span,em,p,br,h2,h3,ul,li,ol",
	 	object_resizing: false,
	 	inline_styles: false,
	 	convert_urls: false,
	 	accessibility_focus: false,


	 	// Theme options
	 	theme_advanced_buttons1: "fullscreen, separator,bold,italic,formatselect,separator,link,unlink,bullist,numlist",
	 	theme_advanced_buttons2: "",
	 	theme_advanced_buttons3: "",
	 	theme_advanced_toolbar_location: "bottom",
	 	theme_advanced_toolbar_align: "left",
	 	theme_advanced_resizing: false,
	 	theme_advanced_blockformats: "p,h2,h3",

	 	// styles
	 	content_css: "/assets/styles/admin/tinymce-content.css"

	});

/**
 * jQuery on Dom ready functionality
 */
jQuery(function($) {

	// init

		// extra data toggle
		if ($("#is-extra-data").is(":checked")) {
			$("#fieldset-dataextra").show();
		} else {
			$("#fieldset-dataextra").hide();
		}

		// date picker
		$('input.date-picker').datepicker({ showAnim: 'slideDown', dateFormat: 'yy-mm-dd' });

		$('input.date-picker-full').datepicker({ showAnim: 'slideDown', dateFormat: 'yy-mm-dd 00:00:00' });

		// upload element
		if ($(".file-upload .asset .file-image").width() > 150) {
			$(".file-upload .asset .file-image").attr("height", "150");
		}


		// sortable table
		$("tbody.sortable").sortable({
			handle : '.handle',
			update : function () {
				var controller = $("tbody.sortable").attr('id');
				jQuery.post(
					"/admin/"+controller+"/update-priorities",
					$("tbody.sortable").sortable('serialize'),
					function(data){

					},
					"json"
				);
			}
		});

		// sortable table
		$("ul.sortable").sortable({
			handle : '.handle',
			update : function () {
				var controller = $("ul.sortable").attr('id');
				jQuery.post(
					"/admin/"+controller+"/update-priorities",
					$("ul.sortable").sortable('serialize'),
					function(data){

					},
					"json"
				);
			}
		});



		// handle checkboxes (for live flag updating)
		$('input.checkbox.liveupdate').bind('change', function(e){

			// fetch necessary info on selected link
			var $el = $(this);
			var controller = $("tbody.sortable").attr('id');
			var value = $el.is(":checked");
			var details = $el.attr('id').split('-');
			var field = details[0];
			var id = details[1];

			updateField({
				controller : controller,
				action : "update-field",
				field : field,
				id : id,
				value : value,
				el : $el
			});
		});


		// handle bookmarking function (for live updating)
		$('input.checkbox.bookmark').bind('change', function(e){

			// fetch necessary info on selected link
			var $el = $(this);
			var $parent = $(this).parent();
			var controller = $("tbody.sortable").attr('id');
			var value = $el.is(":checked");
			var details = $el.attr('id').split('-');
			var field = details[0];
			var id = details[1];

			// visually show only this row as checked
			$("input.bookmark").attr('checked', false);
			$("label.bookmarker").removeClass('selected');

			if (!!value) {
				$el.attr('checked', true);
				$parent.addClass('selected');
			}

			updateField({
				controller : controller,
				action : "update-bookmark",
				field : field,
				id : id,
				value : value,
				el : $el
			});
		});


		// handle 'trash' calls (for live updating)
		$('a.trash').bind('click', function(e){

			e.preventDefault();
			this.blur();

			// seek confirmation before trashing an item
			var answer = confirm("Are you sure you want to trash this item?  You could just hide it.  (You can restore it later from the trash can).");
			if (answer){

				// fetch necessary info on selected link
				var $el = $(this);
				var controller = $("tbody.sortable").attr('id');
				var value = 1;
				var details = $el.attr('id').split('-');
				var field = details[0];
				var id = details[1];

				updateField({
					controller : controller,
					action : "update-field",
					field : field,
					id : id,
					value : value,
					el : $el,
					callback : function() {
						// remove table row
						var $tr = $("tr#item-"+id);
						$tr.find('td').wrapInner("<div />").children("div").slideUp(function() { $tr.remove();});
					}
				});
			}
		});


		// handle selects (for live category updating)
		$('select.liveupdate').bind('change', function(e){

			// fetch necessary info on selected link
			var $el = $(this);
			var controller = $("tbody.sortable").attr('id');
			var value = $el.val();
			var details = $el.attr('id').split('-');
			var field = details[0];
			var id = details[1];

			updateField({
				controller : controller,
				action : "update-field",
				field : field,
				id : id,
				value : value,
				el : $el
			});
		});



		// handle manually flagged elements
		$('span.remove-flag').each(function(counter){

			// create link
			var $span = $(this);
			var removeLink = $('<span class="remove">Remove flag</span>').insertAfter($span);

			removeLink.bind('click', function(e){

				$el = $(this);

				// fetch necessary info on selected link
				updateField({
					controller : $("tbody.sortable").attr('id'),
					action : "update-field",
					field : 'flagged_manually',
					id : $span.attr('id').split('-')[1],
					value : 0,
					el : $el,
					callback : function() {
						$el.remove();
						$span.html('<span class="unpublished">No</span>');
					}
				});
			});
		});



		/*
		 * ajax method to update the content of the DB without refreshing current page
		 */
		function updateField(options) {

			// set parameters
			var ajaxPath = "/admin/"+options.controller+"/"+options.action;
			var postData = 'field='+options.field+'&id='+options.id+'&value='+options.value;

			// prevent ie caching bug
			postData += '&random='+Math.random();

			// submit request
			var response = $.post(
				ajaxPath,
				postData,
				function(data){

					var result = $.parseJSON(data);

					// condition : if the data has been retrieved successfully, show notification
					if (result.success === true) {

						// condition : callback specified?
						if (!!options.callback) {
							options.callback();

						// else default behaviour
						} else {

							// condition : remove current 'saved' element if found
							var currentSavedElement = options.el.parent().find('em.saved');
							if (currentSavedElement.length > 0) {
								currentSavedElement.remove();
							}

							// add 'saved' element
							var saved = $('<em class="saved ui-icon ui-icon-check">Saved!</em>');
							saved.insertAfter(options.el);
							saved.delay(1000).animate({
								opacity:0
							}, 500, function(){
								$(this).remove();
							});
						}
					}
				}
			);
		}


		// data table

			// setup class names
			$.fn.dataTableExt.oJUIClasses.sWrapper = 'dt-wrapper';

			$.fn.dataTableExt.oJUIClasses.sInfo = 'info';
			$.fn.dataTableExt.oJUIClasses.sFilter = 'filter';
			$.fn.dataTableExt.oJUIClasses.sPaging = 'pagination ';

			$.fn.dataTableExt.oJUIClasses.sSortable = '';
			$.fn.dataTableExt.oJUIClasses.sSortableAsc = '';
			$.fn.dataTableExt.oJUIClasses.sSortableDesc = '';
			$.fn.dataTableExt.oJUIClasses.sSortDesc = '';
			$.fn.dataTableExt.oJUIClasses.sSortAsc = '';

			$.fn.dataTableExt.oJUIClasses.sRowEmpty = 'empty';

			// make init call
			oTable = $('table.data-table').dataTable({
				"iDisplayLength": 20,
				"bSort": false, // remove column sort functionality
				"bFilter": false, // remove search functionality
				"bJQueryUI": true,
				"bLengthChange": false,
				"bStateSave": true,
				"bSortClasses": false,
				"sDom": '<"clearfix toolbar"pif>rt'
			});

	// events

		// icons
		$('ul.icons li').hover(
			function() {
				$(this).addClass('ui-state-hover');
			},
			function() {
				$(this).removeClass('ui-state-hover');
			}
		);

		// delete asset
		$(".file-delete input").click(function(){

			// grab the asset dom ob
			$asset = $(this).parents('.file-upload').find('.asset');

			// condition: checked?
		    if ($(this).is(":checked")) {
				$asset.hide(1, function () {
					$asset.after('<div class="message">Deleted on save</div>');
		      	});
		    } else {
		        //otherwise, hide it
				$asset.show(1, function () {
					$asset.parent().find('.message').remove();
		      	});
		    }
		});

		// show full size asset
		$(".file-upload .asset .file-image").click(function(){

			// grab asset dom
			$asset = $(this).parent();

			// condition : hide or show scroll
			if ($asset.css('overflow') == 'scroll') {
				$asset.css('overflow', 'hidden');
				// condition: if bigger than 150 then shrink
				if ($(".file-upload .asset .file-image").width() > 150) {
					$(".file-upload .asset .file-image").attr("height", "150");
				}
			} else {
				$(".file-upload .asset .file-image").removeAttr("width").removeAttr("height");
				$asset.css('overflow', 'scroll');
			}
		});



	/*
	 * project-specific code
	 */
		// automatically create a slug
		$('#admin.project form #project_title').bind('blur',function(){
			// assign value of title to value of slug
			$('#admin.project form #project_slug').val(
				$(this).val()
					.toLowerCase()
					.replace(/[^\w ]+/g,'') // remove hyphens
					.replace(/ +/g,'-') // convert spaces to hypens
					// see http://stackoverflow.com/questions/1053902/how-to-convert-a-title-to-a-url-slug-in-jquery
			);
		});


		// only show the correct project image upload field
		var showHideImageOptions = function(){
			$('#img_src, #flash_src, #vimeo_url, #iframe_url').closest('li').css({'display':'none'});
			var el, selected = $('select#image_type_id option:selected').text();
			switch(selected) {
				case 'image' :
					el = '#img_src';
				break;
				case 'flash' :
					el = '#flash_src';
				break;
				case 'vimeo' :
					el = '#vimeo_url';
				break;
				case 'iframe' :
					el = '#iframe_url';
				break;

			}
			$(el).closest('li').css({'display':'block'});
		};
		$('#image_type_id').bind('change', showHideImageOptions);
		showHideImageOptions();
});