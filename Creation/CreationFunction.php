<?php

require_once 'Creation/CreationObject.php';

class CreationFunction extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create(\s+or\s+replace)?\s+function\s+([a-zA-Z0-9_]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[2];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/returns\s+([a-zA-Z0-9_]+)\(/ui';
		preg_match_all($regexp, $this->sql, $matches);

		$deps = array();
		$primative_types = array('varchar', 'char', 'numeric', 'int');

		foreach ($matches[1] as $dep)
			if (!in_array(strtolower($dep), $primative_types))
				$deps[] = $dep;

		return $deps;
	}

	// }}}
}

?>
