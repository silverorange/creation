<?php

namespace Silverorange\Autoloader;

$package = new Package('silverorange/creation');

$package->addRule(new Rule('', 'Creation'));

Autoloader::addPackage($package);

?>
