<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsProfilePages extends AbstractEntity {

	const TABLE_NAME = 'cms_profile_pages';

	protected $cms_profiles_id;
	protected $cms_pages_id;
	protected $cms_page_actions_id;
}