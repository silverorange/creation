<?php

require_once 'Creation/CreationObject.php';

/**
 * Parses a CREATE INDEX statement
 *
 * @package   Creation
 * @copyright 2006 silverorange
 */ 
class CreationIndex extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create\s+index\s+([a-zA-Z0-9_]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/on\s+([a-zA-Z0-9_]+)/ui';
		preg_match_all($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
}

?>
