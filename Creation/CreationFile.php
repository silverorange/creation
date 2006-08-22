<?php

require_once 'Creation/CreationTable.php';
require_once 'Creation/CreationView.php';
require_once 'Creation/CreationFunction.php';
require_once 'Creation/CreationTrigger.php';
require_once 'Creation/CreationIndex.php';
require_once 'Creation/CreationType.php';

class CreationFile
{
	// {{{ private properties

	private $objects = array();

	// }}}
	// {{{ public function __construct()

	public function __construct($filename)
	{
		$sql = file_get_contents($filename);
		$sql = self::cleanSql($sql);
		$create_statements = $this->parseCreateStatements($sql);

		foreach ($create_statements as $statement) {
			$object = $this->parseObject($statement);

			if ($object !== null)
				$this->objects[$object->name] = $object;
		}
	}

	// }}}
	// {{{ public static function cleanSql()

	public static function cleanSql($sql)
	{
		$regexp = '/--.*/';
		$sql = preg_replace($regexp, '', $sql);

		$regexp = '|/\*.*\*/|uUs';
		$sql = preg_replace($regexp, '', $sql);

		return $sql;
	}

	// }}}
	// {{{ private function parseCreateStatements()

	/**
	 * Parses create statements out of a block of SQL
	 *
	 * @param string $sql ithe SQL to parse.
	 *
	 * @return array the array of create statements parsed from the givne SQL.
	 */
	private function parseCreateStatements($sql)
	{
		$lines = explode("\n", $sql);

		$in_string = false;
		$new_statement = false;
		$current_statement = null;
		$create_statements = array();

		foreach ($lines as $line) {
			$new_statement = false;

			$tokens = preg_split('/\s+/i', strtolower($line), -1,
				PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

			foreach ($tokens as $token) {
				// check for new create statement
				if ($token == 'create' && $in_string === false) {
					if ($current_statement !== null)
						$create_statements[] = $current_statement;

					$new_statement = true;
				}

				if ($token == "'" || $token == '$$')
					$in_string = !$in_string;
			}

			if ($new_statement)
				$current_statement = $line."\n";
			elseif ($current_statement !== null)
				$current_statement.= $line."\n";
		}

		$create_statements[] = $current_statement;

		return $create_statements;
	}

	// }}}
	// {{{ private function parseObject()

	private function parseObject($sql) {
		$regexp = '/create( or replace)? (table|view|function|trigger|index|type)/ui';

		if (preg_match($regexp, $sql, $matches)) {
			$type =  strtolower($matches[2]);

			switch ($type) {
			case 'table':
				$object = new CreationTable($sql);
				break;
			case 'view':
				$object = new CreationView($sql);
				break;
			case 'function':
				$object = new CreationFunction($sql);
				break;
			case 'trigger':
				$object = new CreationTrigger($sql);
				break;
			case 'index':
				$object = new CreationIndex($sql);
				break;
			case 'type':
				$object = new CreationType($sql);
				break;
			default:
				print_r($matches);
				exit;
			}
		} else {
			echo "Could not create an object for:\n", $sql;
			exit;
		}

		return $object;
	}

	// }}}
	// {{{ public function getObjects()

	public function getObjects()
	{
		return $this->objects;
	}

	// }}}
}

?>
