<?php

require_once 'PEAR.php';
require_once 'MDB2.php';
require_once 'SwatDB/SwatDB.php';
require_once 'SwatDB/exceptions/SwatDBException.php';
require_once 'Swat/exceptions/SwatException.php';
require_once 'Creation/CreationFile.php';

/**
 * Runnable application that processes a list of SQL files and outputs
 * all SQL CREATE statements in the correct order
 *
 * If circular dependencies are encountered, an exception is thrown.
 *
 * @package   Creation
 * @copyright 2006 silverorange
 */
class CreationProcess
{
	// {{{ public properties

	public $dsn = null;
	public $db = null;

	// }}}
	// {{{ private properties

	private $objects = array();
	private $processed_objects = array();
	private $stack = array();
	private $db_types = array(
		'mysqli' => 'mysql',
		'mysql'  => 'mysql',
		'mssql'  => 'mssql',
		'pgsql'  => 'pgsql',
	);

	/*
	 * The type of database currently in use
	 *
	 * @var string
	 */
	private $db_type;

	// }}}
	// {{{ public function __construct()

	public function __construct($dsn = null)
	{
		$this->dsn = $dsn;

		if ($this->dsn !== null) {
			$dsn_info = MDB2::parseDSN($this->dsn);
			$this->db_type = $this->db_types[$dsn_info['phptype']];
		}
	}

	// }}}
	// {{{ public function run()

	public function run()
	{
		$this->connectDB();

		// if table A depends on table B, make any inserts to table A depend on
		// insert to table B.
		foreach ($this->getObjectsByType('CreationInsert') as $insert) {
			$dep_table = $this->findObject($insert->deps[0]);
			foreach ($dep_table->deps as $dep) {
				foreach ($this->getObjectsByType('CreationInsert') as $other_insert) {
					if ($dep === $other_insert->deps[0])
						$insert->deps[] = $other_insert->name;
				}
			}
		}

		// make selects on table A depend on inserts to table A
		foreach ($this->getObjectsByType('CreationSelect') as $select) {
			foreach ($this->getObjectsByType('CreationInsert') as $insert) {
				if ($select->deps[0] = $insert->deps[0])
					$select->deps[] = $insert->name;
			}
		}

		foreach ($this->objects as $object)
			$this->runMethod($object, 'create', array($this->db));
	}

	// }}}
	// {{{ public function addFile()

	public function addFile($filename)
	{
		if ($this->db_type === null) {
			$dsn_info = MDB2::parseDSN($this->dsn);
			$this->db_type = $this->db_types[$dsn_info['phptype']];
		}

		$pattern = '/^\.?[^\.]+(\.'.$this->db_type.')?\.sql$/';
		// use only the files with the specific extension and the generic files
		if (preg_match($pattern, $filename)) {
			echo "Adding file ", $filename, "\n";
			$file = new CreationFile($filename);
			$objects = $file->getObjects();

			foreach (array_keys($objects) as $object)
				echo '    '.$object. "\n";

			$this->objects = array_merge($this->objects, $objects);
		}
	}

	// }}}
	// {{{ protected function connectDB()

	protected function connectDB()
	{
		printf("Connecting to DB (%s)... ", $this->dsn);

		if ($this->dsn === null)
			throw new SwatException('No DSN specified.');

		$this->db = MDB2::connect($this->dsn);

		if (PEAR::isError($this->db))
			throw new SwatDBException($this->db);

		$this->db->options['result_buffering'] = false;

		echo "success\n";
	}

	// }}}
	// {{{ private function runMethod()

	private function runMethod(CreationObject $object, $method, $args = array())
	{
		if (in_array($object->name, $this->processed_objects))
			return;

		if (in_array($object->name, $this->stack)) {
			ob_start();
			echo 'Circular dependency on object ', $object->name, ".\n";
			print_r($object->deps);
			$message = ob_get_clean();
			throw new SwatException($message);
		}

		array_push($this->stack, $object->name);

		foreach ($object->deps as $dep) {
			$dep_object = $this->findObject($dep);

			if ($dep_object === null)
				printf("Warning: dependent object '$dep' not found, skipping\n");
			else
				$this->runMethod($dep_object, $method, $args);
		}

		call_user_func_array(array($object, $method), $args);
		array_pop($this->stack);
		$this->processed_objects[] = $object->name;
	}

	// }}}
	// {{{ private function findObject()

	private function findObject($name)
	{
		if (isset($this->objects[$name]))
			return $this->objects[$name];

		return null;
	}

	// }}}
	// {{{ private function getObjectsByType()

	private function getObjectsByType($type)
	{
		$objects = array();

		foreach ($this->objects as $object)
			if ($object instanceof $type)
				$objects[] = $object;

		return $objects;
	}

	// }}}
}

?>
