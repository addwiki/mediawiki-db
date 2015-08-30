<?php

namespace Mediawiki\Db\Service;

use Mediawiki\DataModel\Log;
use Mediawiki\DataModel\LogList;
use PDO;

class DeletedLogListGetter {

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
	 *
	 * @param int $namespace the namespace you want to get possibly deleted titles from
	 *
	 * @return LogList
	 */
	public function getTitleStrings( $namespace = 0 ) {
		$statement = $this->db->prepare( $this->getQuery() );
		$statement->execute( array( ':namespace' => $namespace ) );
		$rows = $statement->fetchAll();

		$logList = new LogList();
		foreach( $rows as $row ) {
			$logList->addLog( new Log(
				$row['log_id'],
				$row['log_type'],
				$row['log_action'],
				$row['log_timestamp'],
				$row['log_user'],
				$row['log_page'],
				$row['log_comment'],
				$row['log_params']
			) );
		}

		return $logList;
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
