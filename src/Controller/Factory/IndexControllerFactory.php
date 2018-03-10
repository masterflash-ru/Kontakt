<?php
namespace Mf\Kontakt\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

use Zend\Validator\Translator\TranslatorInterface;


/**
 */
class IndexControllerFactory implements FactoryInterface
{
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

    $config = $container->get('Config');
    $translator = $container->get(TranslatorInterface::class);
    return new $requestedName($config,$translator);
    }

}
