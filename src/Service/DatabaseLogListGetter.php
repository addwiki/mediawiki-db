<?php

namespace Addwiki\Mediawiki\Db\Service;

use Addwiki\Mediawiki\DataModel\Log;
use Addwiki\Mediawiki\DataModel\LogList;
use Addwiki\Mediawiki\DataModel\PageIdentifier;
use Addwiki\Mediawiki\DataModel\Title;
use FluentPDO;
use PDO;

/**
 * @author Addshore
 * @author Bene* < benestar.wikimedia@gmail.com >
 */
class DatabaseLogListGetter {

	/**
	 * @var FluentPDO
	 */
	private $db;

	/**
	 * @param PDO $db
	 */
	public function __construct( PDO $db ) {
		$this->db = new FluentPDO( $db );
	}

	/**
	 * @param string|null $type filter log entries by type
	 * @param string|null $action filter log entries by action, overrides $type
	 * @param int $namespace filter log entries in the given namespace
	 *
	 * @return LogList
	 */
	public function getLogList( $type = null, $action = null, $namespace = 0 ) {
		$query = $this->db->from( 'logging' )
			->select( [
				'log_id', 'log_type', 'log_action', 'log_timestamp', 'log_user',
				'log_namespace', 'log_title', 'log_comment', 'log_page', 'log_params'
			] )
			->where( 'log_type', $type )
			->where( 'log_action', $action )
			->where( 'log_namespace', $namespace );

		$rows = $query->fetchAll();
		$logList = new LogList();

		foreach ( $rows as $row ) {
			$logList->addLog( $this->getLogFromRow( $row ) );
		}

		return $logList;
	}

	private function getLogFromRow( array $row ) {
		return new Log(
			(int)$row['log_id'],
			$row['log_type'],
			$row['log_action'],
			$row['log_timestamp'],
			$row['log_user'],
			new PageIdentifier(
				new Title( $row['log_title'], (int)$row['log_namespace'] ),
				(int)$row['log_page']
			),
			$row['log_comment'],
			$row['log_params']
		);
	}

}
