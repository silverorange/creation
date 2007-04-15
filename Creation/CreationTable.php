<?php

require_once 'Creation/CreationObject.php';

/**
 * Parses a CREATE TABLE statement
 *
 * @package   Creation
 * @copyright 2006 silverorange
 */ 
class CreationTable extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create\s+table\s+([a-zA-Z0-9_]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/references\s+([a-zA-Z0-9_]+)\(/ui';
		preg_match_all($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
}

?>
