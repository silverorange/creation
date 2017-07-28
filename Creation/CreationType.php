<?php

/**
 * Parses a CREATE TYPE statement
 *
 * @package   Creation
 * @copyright 2006 silverorange
 */
class CreationType extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create\s+type\s+([a-zA-Z0-9_]+)/ui';
		$matches = array();

		preg_match($regexp, $this->sql, $matches);

		return $matches[1];
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
