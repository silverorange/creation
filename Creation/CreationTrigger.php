<?php

require_once 'Creation/CreationRelation.php';

class CreationTrigger extends CreationRelation
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create( or replace)? trigger ([a-zA-Z0-9]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[2];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/create( or replace)? trigger ([a-zA-Z0-9]+) on ([a-zA-Z0-9]+)/ui';
		preg_match_all($regexp, $this->sql, $matches);

		return $matches[3];
	}

	// }}}
}

?>
