<?php

require_once 'Creation/CreationObject.php';

/**
 * Parses a CREATE PROCEDURE statement
 *
 * @package   Creation
 * @copyright 2008 silverorange
 */
class CreationProcedure extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create(\s+or\s+replace)?\s+procedure\s+([a-zA-Z0-9_]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[2];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		return array();
	}

	// }}}
}

?>
