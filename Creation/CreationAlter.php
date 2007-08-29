<?php

require_once 'Creation/CreationObject.php';

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
		$regexp = '/alter\s+table\s+([a-zA-Z0-9_]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return array($matches[1]);
	}

	// }}}
}

?>
