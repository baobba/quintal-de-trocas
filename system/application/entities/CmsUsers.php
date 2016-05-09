<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'AbstractEntity.php';

class CmsUsers extends AbstractEntity {

	const TABLE_NAME = 'cms_users';

	protected $id;
	protected $name;
	protected $email;
	protected $desc;
	protected $login;
	protected $pass;
	protected $is_admin;
	protected $created_at;
	protected $updated_at;

	public function getCreated_at($asPtBR = false, $format = 'd-m-Y H:i:s') {

		return $this->formatDate('created_at', $asPtBR, $format);
	}

	public function getUpdated_at($asPtBR = false, $format = 'd-m-Y H:i:s') {

		return $this->formatDate('updated_at', $asPtBR, $format);
	}
}