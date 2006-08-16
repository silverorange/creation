<?php

require_once 'Creation/CreationObject.php';

class CreationTrigger extends CreationObject
{
	// {{{ protected function parseName()

	protected function parseName()
	{
		$regexp = '/create\s+trigger\s+([a-zA-Z0-9_]+)/ui';
		preg_match($regexp, $this->sql, $matches);

		return $matches[1];
	}

	// }}}
	// {{{ protected function parseDeps()

	protected function parseDeps()
	{
		$regexp = '/create\s+trigger\s+([a-zA-Z0-9_]+)\s+on\s+([a-zA-Z0-9_]+)/ui';
		preg_match_all($regexp, $this->sql, $matches);

		return $matches[2];
	}

	// }}}
}

?>
