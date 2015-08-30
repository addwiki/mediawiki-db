<?php

namespace Mediawiki\Db\Service;

use Mediawiki\DataModel\Log;
use Mediawiki\DataModel\LogList;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Title;
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
				new PageIdentifier(
					new Title( $row['log_title'], $row['log_namespace'] ),
					$row['log_page']
				),
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
		return "SELECT log_id,log_type,log_action,log_timestamp,log_user,log_namespace,log_title,log_comment,log_page,log_comment,log_params
FROM logging
WHERE log_type = 'delete'
AND log_action = 'delete'
AND log_namespace = :namespace";
	}

}
