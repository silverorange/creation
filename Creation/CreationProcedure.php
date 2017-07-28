<?php

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
		$matches = array();

		preg_match($regexp, $this->sql, $matches);

		return $matches[2];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/
			(?:
				delete\s+from\+s([a-zA-Z0-9_]+)
				|
				select\s+.*\s+from\s+([a-zA-Z0-9_]+)
				|
				update\s+([a-zA-Z0-9_]+)
				|
				exec\s+([a-zA-Z0-9_]+)
			)/uix';

		$matches = array();

		preg_match_all($regexp, $this->sql, $matches, PREG_SET_ORDER);

		$deps = array();
		foreach ($matches as $match) {
			for ($i = 1; $i <= 4; $i++) {
				if (isset($match[$i]) && $match[$i]) {
					$deps[] = $match[$i];
				}
			}
		}
		return $deps;
	}

	// }}}
}

?>
