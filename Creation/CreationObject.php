<?php

abstract class CreationObject
{
	// {{{ public properties

	public $name;
	public $sql;
	public $deps;

	// }}}
	// {{{ public function __construct()

	public function __construct($sql)
	{
		$this->sql = self::cleanSql($sql);
		$this->name = $this->parseName();
		$this->deps = $this->parseDeps();

		foreach ($this->deps as $key => $dep)
			if ($dep === $this->name)
				unset($this->deps[$key]);
	}

	// }}}
	// {{{ public static function cleanSql()

	public static function cleanSql($sql)
	{
		$regexp = '|--.*|';
		$sql = preg_replace($regexp, '', $sql);

		return $sql;
	}

	// }}}
	// {{{ protected function parseName()

	abstract protected function parseName();

	// }}}
	// {{{ protected function parseDeps()

	abstract protected function parseDeps();

	// }}}
}

?>
