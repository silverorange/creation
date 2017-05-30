Creation
========
Database initialization tools using MDB2. This parses SQL definitions from
source files, creates a dependency graph, and runs the SQL in the correct
order.

Usage
-----
```php
<?php

$process = new CreationProcess();
$process->dsn = 'MY-MDB2-DSN';

// Add multiple SQL source files
$process->addFile( ... );
$process->addFile( ... );
$process->addFile( ... );

// Parse files and run in correct order
$process->run();

?>
```

Installation
------------
Make sure the silverorange composer repository is added to the `composer.json`
for the project and then run:

```sh
composer require silverorange/creation
```
