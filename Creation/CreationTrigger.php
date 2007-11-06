<?php

require_once 'Creation/CreationObject.php';

/**
 * Parses a CREATE TRIGGER statement
 *
 * @package   Creation
 * @copyright 2006 silverorange
 */
class CreationTrigger extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create\s+trigger\s+([a-zA-Z0-9_]+)\s+.*\s+on\s+([a-zA-Z0-9_]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[2].'__'.$matches[1];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/create\s+trigger\s+([a-zA-Z0-9_]+).* on\s+([a-zA-Z0-9_]+)/ui';
		preg_match_all($regexp, $this->sql, $matches);

		return $matches[2];
	}

	// }}}
}

?>
