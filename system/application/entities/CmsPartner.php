<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsPartner extends AbstractEntity {

	const TABLE_NAME = 'cms_partner';

	protected $id;
	protected $name;
	protected $url;
	protected $cover_image;
	protected $active;
	protected $ordering;
}