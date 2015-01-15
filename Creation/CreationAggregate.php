<?php

require_once 'Creation/CreationObject.php';

/**
 * Parses a CREATE AGGREGATE statement
 *
 * @package   Creation
 * @copyright 2015 silverorange
 */
class CreationAggregate extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create\s+aggregate\s+([a-zA-Z0-9_]+)\s*\(/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$deps = array();

		// get dependent types
		$primative_types = array('varchar', 'char', 'numeric', 'int');

		$regexp = '/create\s+aggregate\s+[a-zA-Z0-9_]+\s*\(([a-zA-Z0-9_]+)/ui';
		preg_match_all($regexp, $this->sql, $matches);
		foreach ($matches[1] as $dep) {
			if (!in_array(strtolower($dep), $primative_types)) {
				$deps[] = $dep;
			}
		}

		$regexp = '/stype\s*=\s*([a-zA-Z0-9_]+),/ui';
		preg_match_all($regexp, $this->sql, $matches);
		foreach ($matches[1] as $dep) {
			if (!in_array(strtolower($dep), $primative_types)) {
				$deps[] = $dep;
			}
		}

		// get dependent sfunc
		$regexp = '/sfunc\s*=\s*([a-zA-Z0-9_]+),/ui';
		preg_match_all($regexp, $this->sql, $matches);
		foreach ($matches[1] as $dep) {
			$deps[] = $dep;
		}

		return $deps;
	}

	// }}}
}

?>
