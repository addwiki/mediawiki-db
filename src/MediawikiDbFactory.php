<?php

namespace Mediawiki\Db;

use Mediawiki\Db\Service\PageFileExtensionListGetter;
use Mediawiki\Db\Service\DeletedLogListGetter;
use Mediawiki\Db\Service\RedirectListGetter;
use PDO;

class MediawikiDbFactory {

	private $db;

	public function __construct( PDO $db ) {
		$this->db = $db;
	}

	public function newPageFileExtensionListGetter( $fileExtension ) {
		return new PageFileExtensionListGetter( $this->db, $fileExtension );
	}

	public function newProbablyDeletedTitleListGetter() {
		return new DeletedLogListGetter( $this->db );
	}

	public function newRedirectListGetter() {
		return new RedirectListGetter( $this->db );
	}

}
