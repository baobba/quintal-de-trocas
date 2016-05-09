<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsProfiles extends AbstractEntity {

	const TABLE_NAME = 'cms_profiles';

	protected $id;
	protected $name;
}