<?php

/**
 * Test: Nette\DI\Container dynamic usage.
 *
 * @author     David Grudl
 * @package    Nette\DI
 */

use Nette\DI\Container;



require __DIR__ . '/../bootstrap.php';



class Service
{
}

$one = new Service;
$two = new Service;


$container = new Container;
$container->addService('one', $one);
$container->addService('two', $two);

Assert::true( $container->hasService('one') );
Assert::true( $container->isCreated('one') );
Assert::true( $container->hasService('two') );
Assert::false( $container->hasService('undefined') );

Assert::same( $one, $container->getService('one') );
Assert::same( $two, $container->getService('two') );


// class name
$builder = $container->addService('three', 'Service');

Assert::true( $container->hasService('three') );
Assert::type( 'Service', $container->getService('three') );
Assert::same( $container->getService('three'), $container->getService('three') ); // shared


// factory
$container->addService('four', function($container){
	Assert::type( 'Nette\DI\Container', $container );
	return new Service;
});

Assert::true( $container->hasService('four') );
Assert::false( $container->isCreated('four') );
Assert::type( 'Service', $container->getService('four') );
Assert::true( $container->isCreated('four') );
Assert::same( $container->getService('four'), $container->getService('four') ); // shared


// bad factory
try {
	$container->addService('five', function($container){});
	$container->getService('five');
	Assert::fail('Expected exception');
} catch (Exception $e) {
	Assert::type( 'Nette\UnexpectedValueException', $e );
	Assert::match("Unable to create service 'five', value returned by factory '%a%' is not object.", $e->getMessage());
}
