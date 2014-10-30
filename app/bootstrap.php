<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

//$configurator->setDebugMode(TRUE);  // debug mode MUST NOT be enabled on production server
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../vendor/others')
	->addDirectory(__DIR__ . '/../vendor/dibi')
	->addDirectory(__DIR__ . '/../vendor/menu')
	->addDirectory(__DIR__ . '/../vendor/EventCalendar')
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/menu.neon');

$configurator->onCompile[] = function ($configurator, $compiler) {
    $compiler->addExtension('dibi', new DibiNette21Extension());
};

$container = $configurator->createContainer();

return $container;
