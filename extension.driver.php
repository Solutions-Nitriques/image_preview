<?php

	if(!defined("__IN_SYMPHONY__")) die("<h2>Error</h2><p>You cannot directly access this file</p>");

	/*
	License: MIT
	*/
	
	require_once(EXTENSIONS . '/image_preview/fields/field.image_preview_settings.php');
	
	class extension_image_preview extends Extension {

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
		private function startsWith($haystack, $needle) {
			$length = strlen($needle);
			return (substr($haystack, 0, $length) === $needle);
		}

		public function appendJS($context){
			$c = Administration::instance()->getPageCallback();
			$c = $c['pageroot'];
			
			// Only add when editing a section
			if ($this->startsWith($c, '/publish/')) {
				Administration::instance()->Page->addScriptToHead(URL.'/extensions/image_preview/assets/image_preview.js',time()+1);
			}
		}
		
		
		
		/* ********* INSTALL/UPDATE/UNISTALL ******* */

		/**
		 * Creates the table needed for the settings of the field
		 */
		public function install() {
			return FieldImage_Preview_Settings::createFieldTable();
		}
		
		
		/**
		 * Creates the table needed for the settings of the field
		 */
		public function update($previousVersion) {
			$ret = true;

			// are we updating from lower than 2.0 ?
			if ($ret && version_compare($previousVersion,'2.0') == -1) {
				$ret = FieldImage_Preview_Settings::createFieldTable();
			}
			return $ret;
		}
		
		/**
		 *
		 * Drops the table needed for the settings of the field
		 */
		public function uninstall() {
			return FieldImage_Preview_Settings::deleteFieldTable();
		}
	}
