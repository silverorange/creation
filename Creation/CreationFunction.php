<?php

/**
 * Parses a CREATE FUNCTION statement
 *
 * @package   Creation
 * @copyright 2006 silverorange
 */
class CreationFunction extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create(\s+or\s+replace)?\s+function\s+([a-zA-Z0-9_]+)/ui';
		$matches = array();

		preg_match($regexp, $this->sql, $matches);

		return $matches[2];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/returns\s+([a-zA-Z0-9_]+)\(/ui';
		$matches = array();

		preg_match_all($regexp, $this->sql, $matches);

		$deps = array();
		$primative_types = array('varchar', 'char', 'numeric', 'int');

		foreach ($matches[1] as $dep) {
			if (!in_array(mb_strtolower($dep), $primative_types)) {
				$deps[] = $dep;
			}
		}

		return $deps;
	}

	// }}}
}

?>
