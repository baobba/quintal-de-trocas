<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsPageActions extends AbstractEntity {

	const TABLE_NAME = 'cms_page_actions';

	protected $id;
	protected $cms_pages_id;
	protected $action;
	protected $name;
	protected $is_custom;
}