<?php

/**
 * Test: Nette\Config\Configurator and services inheritance and overwriting.
 *
 * @author     David Grudl
 * @package    Nette\Config
 */

use Nette\Config\Configurator;



require __DIR__ . '/../bootstrap.php';



class MyApp extends Nette\Application\Application
{
}



$configurator = new Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(TEMP_DIR);
$container = $configurator->addConfig('files/config.inheritance1.neon', Configurator::NONE)
	->createContainer();


Assert::type( 'MyApp', $container->getService('application') );
Assert::true( $container->getService('application')->catchExceptions );
Assert::same( 'Error', $container->getService('application')->errorPresenter );

Assert::type( 'MyApp', $container->getService('app2') );
Assert::true( $container->getService('app2')->catchExceptions );
Assert::same( 'Error', $container->getService('app2')->errorPresenter );
