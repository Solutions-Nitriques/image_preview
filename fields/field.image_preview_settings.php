<?php

	if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');

	require_once(TOOLKIT . '/class.field.php');

	/**
	 *
	 * Field class that will represent settings for image previews
	 * @author Deux Huit Huit
	 *
	 */
	class FieldImage_Preview_Settings extends Field {

		/**
		 *
		 * Name of the field table
		 * @var string
		 */
		const FIELD_TBL_NAME = 'tbl_fields_image_preview_settings';

		private $prefixes = array('Table' => 'table-', 'Entry' => 'entry-');


		/**
		 *
		 * Constructor for the Field object
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
		 * Process entries data before saving into database.
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

			// always display in table mode
			$new_settings['show_column'] = 'yes';


			// set new settings
			$new_settings['field-classes'] = 	( $settings['field-handles'] );

			//var_dump(isset($settings['table-width']));die;
			$new_settings['table-width'] = 		( isset($settings['table-width'])    ? $settings['table-width'] : NULL);
			$new_settings['table-height'] = 	( isset($settings['table-height'])   ? $settings['table-height'] : NULL);
			$new_settings['table-resize'] = 	( isset($settings['table-resize'])   ? $settings['table-resize'] : NULL);
			$new_settings['table-position'] = 	( isset($settings['table-position']) ? $settings['table-position'] : NULL);
			$new_settings['table-absolute'] = 	( isset($settings['table-absolute']) && $settings['table-absolute'] == 'on' ? 'yes' : 'no');

			$new_settings['entry-width'] = 		( isset($settings['entry-width'])    ? $settings['entry-width'] : NULL);
			$new_settings['entry-height'] = 	( isset($settings['entry-height'])   ? $settings['entry-height'] : NULL);
			$new_settings['entry-resize'] = 	( isset($settings['entry-resize'])   ? $settings['entry-resize'] : NULL);
			$new_settings['entry-position'] = 	( isset($settings['entry-position']) ? $settings['entry-position'] : NULL);
			$new_settings['entry-absolute'] = 	( isset($settings['entry-absolute']) && $settings['entry-absolute'] == 'on' ? 'yes' : 'no');

			// save it into the array
			$this->setArray($new_settings);
		}


		/**
		 *
		 * Validates the field settings before saving it into the field's table
		 */
		public function checkFields(array &$errors, $checkForDuplicates = true) {
			parent::checkFields($errors, $checkForDuplicates);

			$field_handles = $this->get('field-handles');

			if (empty($field_handles)) {
				$errors['field-handles'] = __('You must set at least one field handle or * to enable those settings for all fields in this section');
			}

			foreach ($this->prefixes as $key => $prefix) {
				$width = $this->get($prefix.'width');
				$height = $this->get($prefix.'height');
				$resize = $this->get($prefix.'resize');
				$position = $this->get($prefix.'position');

				if (!empty($width) && (!is_numeric($width) || intval($width) < 0)) {
					$errors[$prefix.'width'] = __('Width must be a positive integer');
				}
				if (!empty($height) && (!is_numeric($height) || intval($height) < 0)) {
					$errors[$prefix.'height'] = __('Height must be a positive integer');
				}
				if (!empty($resize) && (!is_numeric($resize) || intval($resize) < 1 || intval($resize) > 3)) {
					$errors[$prefix.'resize'] = __('Resize must be a positive integer between 1 and 3');
				}
				if (!empty($position) && (!is_numeric($position) || intval($position) < 1 || intval($position) > 9)) {
					$errors[$prefix.'position'] = __('Position must be a positive integer between 1 and 9');
				}
			}

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

			// the related fields handles
			$settings['field-handles'] = $this->get('field-handles');

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
			return array();
		}

		/**
		 * Appends data into the XML tree of a Data Source
		 * @param $wrapper
		 * @param $data
		 */
		public function appendFormattedElement(XMLElement &$wrapper, $data, $encode = false, $mode = NULL, $entry_id = NULL) {
			return FALSE;
		}




		/* ********* UI *********** */

		private function convertHandlesIntoIds($handles) {
			$ids = '';

			if (!empty($handles) && $handles != '*' ) {
				$aHandles = explode(',', $handles);
				$parent_section = $this->get('parent_section');

				foreach ($aHandles as $handle) {
					$where = "AND t1.`element_name` = '$handle'";
					$field = FieldManager::fetch(NULL, $parent_section, 'ASC', 'sortorder', NULL, NULL, $where);
					$fieldId = array_keys($field);
					$fieldId = $fieldId[0];

					if (!empty($fieldId)) {
						$ids .= 'field-' . $field[$fieldId]->get('id') . ',';
					}
				}
			} else {
				$ids = '*'; // valid for all fields
			}

			return $ids;
		}

		/**
		 *
		 * Builds the UI for the publish page
		 * @param XMLElement $wrapper
		 * @param mixed $data
		 * @param mixed $flagWithError
		 * @param string $fieldnamePrefix
		 * @param string $fieldnamePostfix
		 */
		public function displayPublishPanel(XMLElement &$wrapper, $data = NULL, $flagWithError = NULL, $fieldnamePrefix = NULL, $fieldnamePostfix = NULL, $entry_id = NULL) {

			// only set data-attributes
			$params = new XMLElement('div');

			$params->setAttribute('data-field-classes', $this->convertHandlesIntoIds($this->get('field-handles')));
			$params->setAttribute('data-width',    $this->get('entry-width'));
			$params->setAttribute('data-height',   $this->get('entry-height'));
			$params->setAttribute('data-resize',   $this->get('entry-resize'));
			$params->setAttribute('data-position', $this->get('entry-position'));
			$params->setAttribute('data-absolute', $this->get('entry-absolute'));

			$wrapper->appendChild($params);
		}

		/**
		 *
		 * Builds the UI for the field's settings when creating/editing a section
		 * @param XMLElement $wrapper
		 * @param array $errors
		 */
		public function displaySettingsPanel(XMLElement &$wrapper, $errors=NULL){

			/* first line, label and such */
			parent::displaySettingsPanel($wrapper, $errors);

			$handles_wrap = new XMLElement('div', NULL, array('class' => 'image_preview'));
			$handles_wrap->appendChild( $this->createInput('Fields handles <i>Type * for all fields; Comma separated list for multiple fields</i>', 'field-handles', $errors) );
			$wrapper->appendChild($handles_wrap);

			foreach ($this->prefixes as $key => $prefix) {
				/* new line, settings */
				$set_wrap = new XMLElement('div', NULL, array('class' => 'compact image_preview'));
				$set_wrap->appendChild( new XMLElement('label', __($key . ' Preview settings')) );

				/* new line, width/height */
				$wh_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
				$wh_wrap->appendChild($this->createInput('Width <i>JIT image manipulation width parameter</i>', $prefix.'width', $errors));
				$wh_wrap->appendChild($this->createInput('Height <i>JIT image manipulation height parameter</i>', $prefix.'height', $errors));


				/* new line, resize/position */
				$rp_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
				$rp_wrap->appendChild($this->createInput('Resize <i>JIT image manipulation resize mode [1-3]</i>', $prefix.'resize', $errors));
				$rp_wrap->appendChild($this->createInput('Position <i>JIT image manipulation position parameter [1-9]</i>', $prefix.'position', $errors));

				/* new line, absolute */
				$a_wrap = new XMLElement('div', NULL, array('class' => 'two columns'));
				$a_wrap->appendChild($this->createCheckbox('Absolute ? <i>Makes the image absolute</i>', $prefix.'absolute', $errors));


				/* append to wrapper */
				$wrapper->appendChild($set_wrap);
				$wrapper->appendChild($wh_wrap);
				$wrapper->appendChild($rp_wrap);
				$wrapper->appendChild($a_wrap);
			}


		}


		private function createInput($text, $key, $errors=NULL) {
			$order = $this->get('sortorder');
			$lbl = new XMLElement('label', __($text), array('class' => 'column'));
			$input = new XMLElement('input', NULL, array(
				'type' => 'text',
				'value' => $this->get($key),
				'name' => "fields[$order][$key]"
			));
			$input->setSelfClosingTag(true);

			$lbl->prependChild($input);

			//var_dump($errors[$key]);

			if (isset($errors[$key])) {
				$lbl = Widget::Error($lbl, $errors[$key]);
			}

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


		private $tableValueGenerated = FALSE;

		/**
		 *
		 * Build the UI for the table view
		 * @param Array $data
		 * @param XMLElement $link
		 * @return string - the html of the link
		 */
		public function prepareTableValue($data, XMLElement $link = NULL, $entry_id = NULL){

			if (!$this->tableValueGenerated) {

				$this->tableValueGenerated = TRUE;

				// does this cell serve as a link ?
				if (!$link){
					// if not, wrap our html with a external link to the resource url
					$link = new XMLElement('div');
				}

				$link->setAttribute('data-field-classes', $this->convertHandlesIntoIds($this->get('field-handles')));
				$link->setAttribute('data-width',    $this->get('table-width'));
				$link->setAttribute('data-height',   $this->get('table-height'));
				$link->setAttribute('data-resize',   $this->get('table-resize'));
				$link->setAttribute('data-position', $this->get('table-position'));
				$link->setAttribute('data-absolute', $this->get('table-absolute'));

				// returns the link's html code
				return $link->generate();


			}
			return NULL;
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
			return false;
		}


		/* ********* SQL Data Definition ************* */

		/**
		 *
		 * Creates table needed for entries of invidual fields
		 */
		public function createTable(){

			return Symphony::Database()->query(

				"CREATE TABLE IF NOT EXISTS `tbl_entries_data_" . $this->get('id') . "` (
					`id` int(11) unsigned NOT NULL auto_increment,
					`entry_id` int(11) unsigned NOT NULL,
					PRIMARY KEY  (`id`),
					KEY `entry_id` (`entry_id`)
				) TYPE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"

			);

			//return FALSE;
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
					`field-handles`		varchar(255) NOT NULL,
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