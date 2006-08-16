<?php

require_once 'PEAR.php';
require_once 'MDB2.php';
require_once 'SwatDB/SwatDB.php';
require_once 'SwatDB/exceptions/SwatDBException.php';
require_once 'Swat/exceptions/SwatException.php';
require_once 'Creation/CreationTable.php';
require_once 'Creation/CreationView.php';
require_once 'Creation/CreationFunction.php';
require_once 'Creation/CreationTrigger.php';

class CreationProcess
{
	public $dsn = null;
	public $db = null;

	private $relations = array();
	private $processed_relations = array();
	private $stack = array();

	// {{{ public function run()

	public function run()
	{
		$this->connectDB();

		foreach ($this->relations as $relation)
			$this->createRelation($relation);
	}

	// }}}
	// {{{ public function addTable()

	public function addTable($filename)
	{
		$sql = file_get_contents($filename);
		$table = new CreationTable($sql);
		$this->relations[$table->name] = $table;
	}

	// }}}
	// {{{ public function addView()

	public function addView($filename)
	{
		$sql = file_get_contents($filename);
		$view = new CreationView($sql);
		$this->relations[$view->name] = $view;
	}

	// }}}
	// {{{ public function addFunction()

	public function addFunction($filename)
	{
		$sql = file_get_contents($filename);
		$function = new CreationFunction($sql);
		$this->relations[$function->name] = $function;
	}

	// }}}
	// {{{ public function addTrigger()

	public function addTrigger($filename)
	{
		$sql = file_get_contents($filename);
		$trigger = new CreationTrigger($sql);
		$this->relations[$trigger->name] = $trigger;
	}

	// }}}
	// {{{ public static function findSqlFiles()

	public static function findSqlFiles($path)
	{
		$files = scandir($path);

		foreach ($files as $key => $file) {
			if (substr($file, -3) === 'sql')
				$files[$key] = $path.'/'.$file;
			else
				unset($files[$key]);
		}

		return $files;
	}

	// }}}
	// {{{ private function createRelation()

	private function createRelation(CreationRelation $relation)
	{
		if (in_array($relation->name, $this->processed_relations))
			return;

		if (in_array($relation->name, $this->stack)) {
			ob_start();
			echo 'Circular dependency on relation ', $relation->name, ".\n";
			print_r($relation->deps);
			$message = ob_get_clean();
			throw new SwatException($message);
		}

		array_push($this->stack, $relation->name);

		foreach ($relation->deps as $dep) {
			$dep_relation = $this->findRelation($dep);

			if ($dep_relation === null)
				printf("Warning: dependent relation '$dep' not found, skipping\n");
			else
				$this->createRelation($dep_relation);
		}

		array_pop($this->stack);

		echo "Creating relation ", $relation->name, "\n";
		// TOOD: actually execute the SQL
		//SwatDB::exec($this->db, $relation->sql);

		$this->processed_relations[] = $relation->name;
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
	// {{{ private function findRelation()

	private function findRelation($name)
	{
		if (isset($this->relations[$name]))
			return $this->relations[$name];

		return null;
	}

	// }}}
}

?>
