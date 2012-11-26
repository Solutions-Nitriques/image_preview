<?php

	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');

	require_once(TOOLKIT . '/class.field.php');

	/**
	 *
	 * Field class that will represent an oEmbed resource
	 * @author Nicolas
	 *
	 * Based on @nickdunn's Vimeo field: https://github.com/nickdunn/vimeo_videos/
	 *
	 */
	class FieldImage_Preview extends Field {

		/**
		 *
		 * Name of the field table
		 * @var string
		 */
		const FIELD_TBL_NAME = 'tbl_fields_image_preview';


		/**
		 *
		 * Constructor for the oEmbed Field object
		 * @param mixed $parent
		 */
		public function __construct(){
			// call the parent constructor
			parent::__construct();
			// set the name of the field
			$this->_name = __('Image Preview Settings');
			// permits to make it required
			$this->_required = false;
			// permits the make it show in the table columns
			$this->_showcolumn = true;
			// set as not required by default
			$this->set('required', 'no');
			// set not unique by default
			$this->set('unique', 'no');
			// set to show thumbs in table by default
			$this->set('thumbs', 'yes');
			
		}
		
		public function isSortable(){
			return false;
		}

		public function canFilter(){
			return false;
			}

		public function canImport(){
			return false;
		}

		public function canPrePopulate(){
			return false;
		}

		public function mustBeUnique(){
			return ($this->get('unique') == 'yes');
		}

		public function allowDatasourceOutputGrouping(){
			return false;
		}

		public function requiresSQLGrouping(){
			return false;
		}

		public function allowDatasourceParamOutput(){
			return false;
		}

		/* ********** INPUT AND FIELD *********** */


		/**
		 *
		 * Validates input
		 * Called before <code>processRawFieldData</code>
		 * @param $data
		 * @param $message
		 * @param $entry_id
		 */
		public function checkPostFieldData($data, &$message, $entry_id=NULL){

			// Always valid, since we do not have any
			// entry data
			
			$message = NULL;

			return self::__OK__;
		}


		/**
		 *
		 * Process data before saving into database.
		 * Also,
		 * Fetches oEmbed data from the source
		 *
		 * @param array $data
		 * @param int $status
		 * @param boolean $simulate
		 * @param int $entry_id
		 *
		 * @return Array - data to be inserted into DB
		 */
		public function processRawFieldData($data, &$status, &$message = null, $simulate = false, $entry_id = null) {
			$status = self::__OK__;

			$errorFlag = false;

			return NULL;
		}

		/**
		 * This function permits parsing different field settings values
		 *
		 * @param array $settings
		 *	the data array to initialize if necessary.
		 */
		public function setFromPOST(Array $settings = array()) {

			// call the default behavior
			parent::setFromPOST($settings);

			// declare a new setting array
			$new_settings = array();

			// set new settings
			$new_settings['width'] = 		( isset($settings['width'])    && is_numeric($settings['width'])    ? intval($settings['width']): NULL);
			$new_settings['height'] = 		( isset($settings['height'])   && is_numeric($settings['height'])   ? intval($settings['height']): NULL);
			$new_settings['resize'] = 		( isset($settings['resize'])   && is_numeric($settings['resize'])   ? intval($settings['resize']): NULL);
			$new_settings['position'] = 	( isset($settings['position']) && is_numeric($settings['position']) ? intval($settings['position']): NULL);
			$new_settings['absolute'] = 	( isset($settings['absolute']) && $settings['absolute'] == 'on'     ? 'yes' : 'no');
			
			// save it into the array
			$this->setArray($new_settings);
		}


		/**
		 *
		 * Validates the field settings before saving it into the field's table
		 */
		public function checkFields(Array &$errors, $checkForDuplicates) {
			parent::checkFields($errors, $checkForDuplicates);
			
			
			
			return (!empty($errors) ? self::__ERROR__ : self::__OK__);
		}

		/**
		 *
		 * Save field settings into the field's table
		 */
		public function commit() {

			// if the default implementation works...
			if(!parent::commit()) return false;

			$id = $this->get('id');
			$width = $this->get('width');
			$height = $this->get('height');
			$resize = $this->get('resize');
			$position = $this->get('position');
			$absolute = $this->get('absolute');

			// exit if there is no id
			if($id == false) return FALSE;

			// declare an array contains the field's settings
			$settings = array();

			// the field id
			$settings['field_id'] = $id;

			// the 'width' setting
			$settings['width'] =  empty($width) ? NULL : $width;
			$settings['height'] =  empty($height) ? NULL : $height;
			$settings['resize'] =  empty($resize) ? NULL : $resize;
			$settings['position'] =  empty($position) ? NULL : $position;
			$settings['absolute'] =  empty($absolute) ? 'no' : $absolute;

			
			// DB
			$tbl = self::FIELD_TBL_NAME;

			Symphony::Database()->query("DELETE FROM `$tbl` WHERE `field_id` = '$id' LIMIT 1");

			// return if the SQL command was successful
			return Symphony::Database()->insert($settings, $tbl);

		}




		/* ******* DATA SOURCE ******* */

		/**
		 *
		 * This array will populate the Datasource included elements.
		 * @return array - the included elements
		 * @see http://symphony-cms.com/learn/api/2.2.3/toolkit/field/#fetchIncludableElements
		 */
		public function fetchIncludableElements() {
			return FALSE;
		}

		/**
		 * Appends data into the XML tree of a Data Source
		 * @param $wrapper
		 * @param $data
		 */
		public function appendFormattedElement(&$wrapper, $data) {
			return FALSE;
		}




		/* ********* UI *********** */

		/**
		 *
		 * Builds the UI for the publish page
		 * @param XMLElement $wrapper
		 * @param mixed $data
		 * @param mixed $flagWithError
		 * @param string $fieldnamePrefix
		 * @param string $fieldnamePostfix
		 */
		public function displayPublishPanel(&$wrapper, $data=NULL, $flagWithError=NULL, $fieldnamePrefix=NULL, $fieldnamePostfix=NULL) {

			// only set data-attributes on the wrapper
			
			$wrapper->setAttribute('data-width',    $this->get('width'));
			$wrapper->setAttribute('data-height',   $this->get('height'));
			$wrapper->setAttribute('data-resize',   $this->get('resize'));
			$wrapper->setAttribute('data-position', $this->get('position'));
			$wrapper->setAttribute('data-absolute', $this->get('absolute'));
		}

		/**
		 *
		 * Builds the UI for the field's settings when creating/editing a section
		 * @param XMLElement $wrapper
		 * @param array $errors
		 */
		public function displaySettingsPanel(&$wrapper, $errors=NULL){

			/* first line, label and such */
			parent::displaySettingsPanel($wrapper, $errors);
			
			
			/* new line, settings */
			$set_wrap = new XMLElement('div', NULL, array('class' => 'compact image_preview'));
			$set_wrap->appendChild( new XMLElement('label', __('Preview settings')) );
			
			/* new line, width/height */
			$wh_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
			$wh_wrap->appendChild($this->createInput('Width', 'width'));
			$wh_wrap->appendChild($this->createInput('Height', 'height'));
			
			/* new line, resize/position */
			$rp_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
			$rp_wrap->appendChild($this->createInput('Resize', 'resize'));
			$rp_wrap->appendChild($this->createInput('Position', 'position'));
			
			/* new line, absolute */
			$a_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
			$a_wrap->appendChild($this->createCheckbox('Absolute ?', 'absolute'));
			

			/* append to wrapper */
			$wrapper->appendChild($set_wrap);
			$wrapper->appendChild($wh_wrap);
			$wrapper->appendChild($rp_wrap);
			$wrapper->appendChild($a_wrap);
		}


		private function createInput($text, $key) {
			$id = $this->get('id');
			$lbl = new XMLElement('label', __($text), array('class' => 'column'));
			$input = new XMLElement('input', NULL, array(
				'type' => 'text',
				'value' => $this->get($key),
				'name' => "fields[$id][$key]"
			));
			$input->setSelfClosingTag(true);
			
			$lbl->prependChild($input);
			
			return $lbl;
		}

		private function createCheckbox($text, $key) {
			$id = $this->get('id');
			$lbl = new XMLElement('label', __($text), array('class' => 'column'));
			$input = new XMLElement('input', NULL, array(
				'type' => 'checkbox',
				'name' => "fields[$id][$key]"
			));
			$input->setSelfClosingTag(true);
			
			if ($this->get($key) == 'yes') {
				$input->setAttribute('checked','checked');
			}
			
			$lbl->prependChild($input);
			
			return $lbl;
		}

		/**
		 *
		 * Build the UI for the table view
		 * @param Array $data
		 * @param XMLElement $link
		 * @return string - the html of the link
		 */
		public function prepareTableValue($data, XMLElement $link=NULL){

			$url = $data['url'];
			$thumb = $data['thumbnail_url'];
			$textValue = $this->preparePlainTextValue($data, $data['res_id']);
			$value = NULL;

			// no url = early exit
			if(strlen($url) == 0) return NULL;

			// no thumbnail or the parameter is not set ?
			if (empty($thumb) || $this->get('thumbs') != 'yes') {
				// if not use the title or the url as value
				$value = $textValue;
			} else {
				// create a image
				$img_path = URL . '/image/1/0/40/1/' .  str_replace('http://', '',$thumb);

				$value = '<img src="' . $img_path .'" alt="' . General::sanitize($data['title']) .'" height="40" />';
			}

			// does this cell serve as a link ?
			if (!!$link){
				// if so, set our html as the link's value
				$link->setValue($value);
				$link->setAttribute('title', $textValue . ' | ' . $link->getAttribute('title'));

			} else {
				// if not, wrap our html with a external link to the resource url
				$link = new XMLElement('a',
					$value,
					array('href' => $url, 'target' => '_blank', 'title' => $textValue)
				);
			}

			// returns the link's html code
			return $link->generate();
		}

		/**
		 *
		 * Return a plain text representation of the field's data
		 * @param array $data
		 * @param int $entry_id
		 */
		public function preparePlainTextValue($data, $entry_id = null) {
			return NULL;
		}





		/* ********* SQL Data Definition ************* */

		/**
		 *
		 * Creates table needed for entries of invidual fields
		 */
		public function createTable(){
			return FALSE;
		}

		/**
		 * Creates the table needed for the settings of the field
		 */
		public static function createFieldTable() {

			$tbl = self::FIELD_TBL_NAME;

			return Symphony::Database()->query("
				CREATE TABLE IF NOT EXISTS `$tbl` (
					`id` 			int(11) unsigned NOT NULL auto_increment,
					`field_id` 		int(11) unsigned NOT NULL,
					`width` 		int(11) unsigned NULL,
					`height` 		int(11) unsigned NULL,
					`resize` 		int(11) unsigned NULL,
					`position` 		int(11) unsigned NULL,
					`absolute`		enum('yes','no') NOT NULL DEFAULT 'no',
					PRIMARY KEY (`id`),
					KEY `field_id` (`field_id`)
				)  ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
			");
		}


		/**
		 *
		 * Drops the table needed for the settings of the field
		 */
		public static function deleteFieldTable() {
			$tbl = self::FIELD_TBL_NAME;

			return Symphony::Database()->query("
				DROP TABLE IF EXISTS `$tbl`
			");
		}

	}