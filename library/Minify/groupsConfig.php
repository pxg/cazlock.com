<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

return array(
     'js' => array(
		'//_includes/js/lib/manager/manager.js',
		'//_includes/js/lib/links/links.js',
		
		'//_includes/js/lib/flash/swfobject.js',
		'//_includes/js/lib/jquery-tools/jquery.tools.min.js',
		
		'//_includes/js/lib/cookies/jquery.cookie.js',
		'//_includes/js/lib/debug/jquery.gridoverlay.js',
		
		'//_includes/js/site/home-hero.js',
		'//_includes/js/site/project-navigation.js',

		'//_includes/js/site/init.js',		
	),
    
 	'css_screen' => array(
		'//_includes/css/lib/reset.css',
		'//_includes/css/lib/blackflag.css',
		'//_includes/css/site/screen.css',
		'//_includes/css/site/mobile.css',
	),

	'css_print' => array(
		'//_includes/css/site/print.css'
	),
);