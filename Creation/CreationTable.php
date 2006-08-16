<?php

require_once 'Creation/CreationRelation.php';

class CreationTable extends CreationRelation
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create table ([a-zA-Z0-9]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/references ([a-zA-Z0-9]+)\(/ui';
		preg_match_all($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
}

?>
