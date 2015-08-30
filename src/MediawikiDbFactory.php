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

	/**
	 * @since 0.1
	 *
	 * @param string $fileExtension
	 *
	 * @return PageFileExtensionListGetter
	 */
	public function newPageFileExtensionListGetter( $fileExtension ) {
		return new PageFileExtensionListGetter( $this->db, $fileExtension );
	}

	/**
	 * @since 0.1
	 *
	 * @return DeletedLogListGetter
	 */
	public function newDeletedLogListGetter() {
		return new DeletedLogListGetter( $this->db );
	}

	/**
	 * @since 0.1
	 * 
	 * @return RedirectListGetter
	 */
	public function newRedirectListGetter() {
		return new RedirectListGetter( $this->db );
	}

}
