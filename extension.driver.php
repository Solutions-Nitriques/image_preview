<?php

	if(!defined("__IN_SYMPHONY__")) die("<h2>Error</h2><p>You cannot directly access this file</p>");

	/*
	Copyight: Solutions Nitriques 2011
	License: MIT
	*/
	class extension_image_preview extends Extension {

		public function about() {
			return array(
				'name'			=> 'Image Preview',
				'version'		=> '1.0',
				'release-date'	=> '2011-06-13',
				'author'		=> array(
					'name'			=> 'Solutions Nitriques',
					'website'		=> 'http://www.nitriques.com/',
					'email'			=> 'nico@nitriques.com'
				),
				'description'	=> 'Really simple ext that shows a preview of an file upload field if it is an image',
				'compatibility' => array(
					'2.2.1' => true,
					'2.2' => true
				)
	 		);
		}

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/backend/',
					'delegate' => 'InitaliseAdminPageHead',
					'callback' => 'appendJS'
				)
			);
		}
		
		// FROM: http://stackoverflow.com/questions/834303/php-startswith-and-endswith-functions
		function startsWith($haystack, $needle) {
			$length = strlen($needle);
			return (substr($haystack, 0, $length) === $needle);
		}

		public function appendJS($context){
			$c = Administration::instance()->getPageCallback();
			$c = $c['pageroot'];
			
			// Only add when editing a section
			if ($this->startsWith($c, '/publish/')) {
				Administration::instance()->Page->addScriptToHead('/extensions/image_preview/assets/image_preview.js',time()+1);
			}
		}
	}
	
?>