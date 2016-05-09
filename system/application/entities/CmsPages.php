<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsPages extends AbstractEntity {

	const TABLE_NAME = 'cms_pages';

	protected $id;
	protected $name;
	protected $file;
}