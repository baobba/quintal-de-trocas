<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsFaq extends AbstractEntity {

	const TABLE_NAME = 'cms_faq';

	protected $id;
	protected $question;
	protected $answer;
	protected $active;
	protected $ordering;
}