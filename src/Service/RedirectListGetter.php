<?php

namespace Mediawiki\Db\Service;

use Mediawiki\Db\DataObjects\Redirect;
use PDO;

class RedirectListGetter {

	/**
	 * @var PDO
	 */
	protected $db;

	/**
	 * @param PDO $db
	 */
	public function __construct( PDO $db ) {
		$this->db = $db;
	}

	/**
	 * @todo option to and from namespaces!
	 *
	 * @param int $namespace the namespace you want to get redirect from
	 *
	 * @return Redirect[]
	 */
	public function getRedirects( $namespace = 0 ) {
		$statement = $this->db->prepare( $this->getQuery() );
		$statement->execute( array( ':namespace' => $namespace ) );
		$rows = $statement->fetchAll();

		$redirects = array();
		foreach( $rows as $row ) {
			$redirects[] = new Redirect( $row['title'], $row['rd_title'] );
		}

		return $redirects;
	}

	/**
	 * @todo we probably want to build the queries rather than having then so hardcoded....
	 *
	 * @return string
	 */
	private function getQuery() {
		return "SELECT p1.page_title AS title, rd_title
FROM page as p1
LEFT JOIN redirect ON ((rd_from=p1.page_id))
LEFT JOIN page as p2 ON ((p2.page_namespace=rd_namespace) AND (p2.page_title=rd_title))
WHERE p1.page_is_redirect = '1'
AND p1.page_namespace = :namespace";
	}

}
