<?php

/**
 * Parses a CREATE TRIGGER statement
 *
 * @package   Creation
 * @copyright 2006-2010 silverorange
 */
class CreationTrigger extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		// pgsql and mysql trigger syntax
		$regexp = '/create\s+trigger\s+([a-zA-Z0-9_]+)\s+.*\s+on\s+([a-zA-Z0-9_]+)/ui';
		if (preg_match($regexp, $this->sql, $matches) === 1) {
			$name = $matches[2].'__'.$matches[1];
		}

		// mssql trigger syntax
		$regexp = '/create\s+trigger\s+([a-zA-Z0-9_]+)\s+on\s+([a-zA-Z0-9_]+)\s+.*\s+as/ui';
		if (preg_match($regexp, $this->sql, $matches) === 1) {
			$name = $matches[2].'__'.$matches[1];
		}

		return $name;
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$deps = array();

		// pgsql and mysql trigger syntax
		$regexp = '/create\s+trigger\s+([a-zA-Z0-9_]+).* on\s+([a-zA-Z0-9_]+)/ui';
		if (preg_match_all($regexp, $this->sql, $matches) === 1) {
			$deps = $matches[2];
		}

		// mssql trigger syntax
		$regexp = '/create\s+trigger\s+([a-zA-Z0-9_]+)\s+on\s+([a-zA-Z0-9_]+)\s+.*\s+as/ui';
		if (preg_match($regexp, $this->sql, $matches) === 1) {
			$deps = $matches[2];
		}

		return $deps;
	}

	// }}}
}

?>
