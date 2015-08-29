<?php

namespace Mediawiki\Db\Service;

use PDO;

class ProbablyDeletedTitleListGetter {

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
	 * @todo more options.....
	 * @todo this should probably require datamodel and return log entries....
	 *
	 * @param int $namespace the namespace you want to get possibly deleted titles from
	 *
	 * @return string[]
	 */
	public function getTitleStrings( $namespace = 0 ) {
		$statement = $this->db->prepare( $this->getQuery() );
		$statement->execute( array( ':namespace' => $namespace ) );
		$rows = $statement->fetchAll();

		$titles = array();
		foreach( $rows as $row ) {
			$titles[] = $row['log_title'];
		}

		return $titles;
	}

	/**
	 * @todo we probably want to build the queries rather than having then so hardcoded....
	 *
	 * @return string
	 */
	private function getQuery() {
		return "SELECT log_title,log_comment
FROM logging
WHERE log_type = 'delete'
AND log_action = 'delete'
AND log_namespace = :namespace";
	}

}
