<?php

require_once 'Creation/CreationRelation.php';

class CreationFunction extends CreationRelation
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create( or replace)? function ([a-zA-Z0-9]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[2];
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
