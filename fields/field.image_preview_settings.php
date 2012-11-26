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
	class FieldImage_Preview_Settings extends Field {

		/**
		 *
		 * Name of the field table
		 * @var string
		 */
		const FIELD_TBL_NAME = 'tbl_fields_image_preview_settings';


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
			$new_settings['table-width'] = 		( isset($settings['table-width'])    && is_numeric($settings['table-width'])    ? intval($settings['table-width']): NULL);
			$new_settings['table-height'] = 	( isset($settings['table-height'])   && is_numeric($settings['table-height'])   ? intval($settings['table-height']): NULL);
			$new_settings['table-resize'] = 	( isset($settings['table-resize'])   && is_numeric($settings['table-resize'])   ? intval($settings['table-resize']): NULL);
			$new_settings['table-position'] = 	( isset($settings['table-position']) && is_numeric($settings['table-position']) ? intval($settings['table-position']): NULL);
			$new_settings['table-absolute'] = 	( isset($settings['table-absolute']) && $settings['table-absolute'] == 'on'     ? 'yes' : 'no');
			
			$new_settings['entry-width'] = 		( isset($settings['entry-width'])    && is_numeric($settings['entry-width'])    ? intval($settings['entry-width']): NULL);
			$new_settings['entry-height'] = 	( isset($settings['entry-height'])   && is_numeric($settings['entry-height'])   ? intval($settings['entry-height']): NULL);
			$new_settings['entry-resize'] = 	( isset($settings['entry-resize'])   && is_numeric($settings['entry-resize'])   ? intval($settings['entry-resize']): NULL);
			$new_settings['entry-position'] = 	( isset($settings['entry-position']) && is_numeric($settings['entry-position']) ? intval($settings['entry-position']): NULL);
			$new_settings['entry-absolute'] = 	( isset($settings['entry-absolute']) && $settings['entry-absolute'] == 'on'     ? 'yes' : 'no');
			
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
			if(!parent::commit()) return FALSE;
			
			$id = $this->get('id');

			// exit if there is no id
			if($id == false) return FALSE;

			// declare an array contains the field's settings
			$settings = array();
			
			$t_width = $this->get('table-width');
			$t_height = $this->get('table-height');
			$t_resize = $this->get('table-resize');
			$t_position = $this->get('table-position');
			$t_absolute = $this->get('table-absolute');
			
			$e_width = $this->get('entry-width');
			$e_height = $this->get('entry-height');
			$e_resize = $this->get('entry-resize');
			$e_position = $this->get('entry-position');
			$e_absolute = $this->get('entry-absolute');

			// the field id
			$settings['field_id'] = $id;

			// the 'table' settings
			$settings['table-width']    =  empty($t_width) ? NULL : $t_width;
			$settings['table-height']   =  empty($t_height) ? NULL : $t_height;
			$settings['table-resize']   =  empty($t_resize) ? NULL : $t_resize;
			$settings['table-position'] =  empty($t_position) ? NULL : $t_position;
			$settings['table-absolute'] =  empty($t_absolute) ? 'no' : $t_absolute;
			
			// the 'entry' settings
			$settings['entry-width']    =  empty($e_width) ? NULL : $e_width;
			$settings['entry-height']   =  empty($e_height) ? NULL : $e_height;
			$settings['entry-resize']   =  empty($e_resize) ? NULL : $e_resize;
			$settings['entry-position'] =  empty($e_position) ? NULL : $e_position;
			$settings['entry-absolute'] =  empty($e_absolute) ? 'no' : $e_absolute;
			
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
			
			$wrapper->setAttribute('data-width',    $this->get('table-width'));
			$wrapper->setAttribute('data-height',   $this->get('table-height'));
			$wrapper->setAttribute('data-resize',   $this->get('table-resize'));
			$wrapper->setAttribute('data-position', $this->get('table-position'));
			$wrapper->setAttribute('data-absolute', $this->get('table-absolute'));
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
			
			$prefixes = array('Table' => 'table-', 'Entry' => 'entry-');
			
			foreach ($prefixes as $key => $prefix) {
				/* new line, settings */
				$set_wrap = new XMLElement('div', NULL, array('class' => 'compact image_preview'));
				$set_wrap->appendChild( new XMLElement('label', __($key . ' Preview settings')) );
				
				/* new line, width/height */
				$wh_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
				$wh_wrap->appendChild($this->createInput('Width <i>JIT image manipulation width parameter</i>', $prefix.'width'));
				$wh_wrap->appendChild($this->createInput('Height <i>JIT image manipulation height parameter</i>', $prefix.'height'));
				
				/* new line, resize/position */
				$rp_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
				$rp_wrap->appendChild($this->createInput('Resize <i>JIT image manipulation resize mode [1-3]</i>', $prefix.'resize'));
				$rp_wrap->appendChild($this->createInput('Position <i>JIT image manipulation position parameter [1-9]</i>', $prefix.'position'));
				
				/* new line, absolute */
				$a_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
				$a_wrap->appendChild($this->createCheckbox('Absolute ? <i>Makes the image absolute</i>', $prefix.'absolute'));
				
	
				/* append to wrapper */
				$wrapper->appendChild($set_wrap);
				$wrapper->appendChild($wh_wrap);
				$wrapper->appendChild($rp_wrap);
				$wrapper->appendChild($a_wrap);
			}

			
		}


		private function createInput($text, $key) {
			$order = $this->get('sortorder');
			$lbl = new XMLElement('label', __($text), array('class' => 'column'));
			$input = new XMLElement('input', NULL, array(
				'type' => 'text',
				'value' => $this->get($key),
				'name' => "fields[$order][$key]"
			));
			$input->setSelfClosingTag(true);
			
			$lbl->prependChild($input);
			
			return $lbl;
		}

		private function createCheckbox($text, $key) {
			$order = $this->get('sortorder');
			$lbl = new XMLElement('label', __($text), array('class' => 'column'));
			$input = new XMLElement('input', NULL, array(
				'type' => 'checkbox',
				'name' => "fields[$order][$key]"
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


		/**
		 *
		 * This function allows Fields to cleanup any additional things before it is removed
		 * from the section.
		 * @return boolean
		 */
		public function tearDown() {
			// do nothing
			// this field has no data
			return true;
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
					`id` 				int(11) unsigned NOT NULL auto_increment,
					`field_id` 			int(11) unsigned NOT NULL,
					`table-width` 		int(11) unsigned NULL,
					`table-height` 		int(11) unsigned NULL,
					`table-resize` 		int(11) unsigned NULL,
					`table-position` 	int(11) unsigned NULL,
					`table-absolute` 	enum('yes','no') NOT NULL DEFAULT 'no',
					`entry-width` 		int(11) unsigned NULL,
					`entry-height` 		int(11) unsigned NULL,
					`entry-resize` 		int(11) unsigned NULL,
					`entry-position` 	int(11) unsigned NULL,
					`entry-absolute`	enum('yes','no') NOT NULL DEFAULT 'no',
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