<?php
namespace Statpage\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Statpage\Service\Statpage;


/**
фабрика
 */
class StatpageFactory implements FactoryInterface
{

public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
		 $connection=$container->get('ADO\Connection');
		 $cache = $container->get('FilesystemCache');
		 $config = $container->get('Config');
        
        return new Statpage($connection, $cache,$config);
    }
}

