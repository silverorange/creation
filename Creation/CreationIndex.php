<?php

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
		$regexp = '/create\s+(?:unique\s+)?index\s+([a-zA-Z0-9_]+)/ui';
		$matches = array();

		preg_match($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/\son\s+([a-zA-Z0-9_]+)/ui';
		$matches = array();

		preg_match_all($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
}

?>
