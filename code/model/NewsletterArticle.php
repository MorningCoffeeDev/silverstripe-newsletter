<?php
/**
 * @package  newsletter
 */

/**
 * Single newsletter article instance. 
 */
class NewsletterArticle extends DataObject {
	private static $db = array(
		'Title'    => 'Varchar(255)',
		'Content'  => 'HTMLText',
		// Automatically generated for use as an id or class in templates
		'HTMLID'   => 'Varchar(255)'
	);

	private static $has_one = array(
		'Newsletter' => 'Newsletter'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('HTMLID');
		return $fields;
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();

		if ($this->isChanged('Title')) {
			$extension = 0;
			$this->HTMLID = $HTMLID = preg_replace('/[^\w\-]/', '', str_replace(' ', '-', strtolower($this->Title)));

			$result = true;
			while ($result) {
				$sql = 'SELECT * FROM "NewsletterArticle" ' .
					"WHERE \"HTMLID\" = '{$HTMLID}' " .
					"AND \"NewsletterID\" = '{$this->NewsletterID}'";
				$query = DB::query($sql, E_USER_ERROR);

				if ($result = $query->nextRecord()) {
					$extension++;
					$HTMLID = substr($this->HTMLID, 0, 255 - strlen('-' . $extension)) . '-' . $extension;
				}
				else {
					$this->HTMLID = $HTMLID;
				}
			}
		}
	}
}