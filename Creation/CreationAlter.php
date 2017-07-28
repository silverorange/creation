<?php

/**
 * Parses an ALTER TABLE statement
 *
 * @package   Creation
 * @copyright 2007 silverorange
 */
class CreationAlter extends CreationObject
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
		$deps = array();

		$regexp = '/alter\s+table\s+([a-zA-Z0-9_]+)/ui';
		$matches = array();

		preg_match($regexp, $this->sql, $matches);
		$deps[] = $matches[1];

		$regexp = '/references\s+([a-zA-Z0-9_]+)\s*\(/ui';
		$matches = array();
		if (preg_match($regexp, $this->sql, $matches) === 1) {
			$deps[] = $matches[1];
		}

		return $deps;
	}

	// }}}
}

?>
