<?php

namespace Mediawiki\Db\Service;

use FluentPDO;
use Mediawiki\DataModel\Log;
use Mediawiki\DataModel\LogList;
use Mediawiki\DataModel\PageIdentifier;
use Mediawiki\DataModel\Title;
use PDO;

class DeletedLogListGetter {

	/**
	 * @var FluentPDO
	 */
	protected $db;

	/**
	 * @param PDO $db
	 */
	public function __construct( PDO $db ) {
		$this->db = new FluentPDO( $db );
	}

	/**
	 * @todo more options.....
	 *
	 * @param int $namespace the namespace you want to get possibly deleted titles from
	 *
	 * @return LogList
	 */
	public function getTitleStrings( $namespace = 0 ) {
		$query = $this->db->from( 'logging' )
			->select( 'log_id' )
			->select( 'log_type' )
			->select( 'log_action' )
			->select( 'log_timestamp' )
			->select( 'log_user' )
			->select( 'log_namespace' )
			->select( 'log_title' )
			->select( 'log_comment' )
			->select( 'log_page' )
			->select( 'log_params' )
			->where( 'log_type = \'delete\'' )
			->where( 'log_action = \'delete\'' )
			->where( 'log_namespace = ' . $this->db->getPdo()->quote( $namespace ) );

		$rows = $query->fetchAll();

		$logList = new LogList();
		foreach( $rows as $row ) {
			$logList->addLog( new Log(
				intval( $row['log_id'] ),
				$row['log_type'],
				$row['log_action'],
				$row['log_timestamp'],
				$row['log_user'],
				new PageIdentifier(
					new Title( $row['log_title'], intval( $row['log_namespace'] ) ),
					intval( $row['log_page'] )
				),
				$row['log_comment'],
				$row['log_params']
			) );
		}

		return $logList;
	}

}
