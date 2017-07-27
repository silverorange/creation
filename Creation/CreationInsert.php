<?php

/**
 * Parses an INSERT statement
 *
 * @package   Creation
 * @copyright 2007 silverorange
 */
class CreationInsert extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		return md5($this->sql);
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/insert\s+into\s+([a-zA-Z0-9_]+)/ui';
		$matches = array();

		preg_match($regexp, $this->sql, $matches);

		return array($matches[1]);
	}

	// }}}
}

?>
