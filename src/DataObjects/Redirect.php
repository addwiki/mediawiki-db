<?php

namespace Mediawiki\Db\DataObjects;

/**
 * @todo this should probably be somewhere else, perhaps in datamodel....
 * @todo this should probably specify what types it is from and to?
 */
class Redirect {

	private $from;
	private $to;

	public function __construct( $from, $to ) {
		$this->from = $from;
		$this->to = $to;
	}

	/**
	 * @return mixed
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * @return mixed
	 */
	public function getTo() {
		return $this->to;
	}

}
