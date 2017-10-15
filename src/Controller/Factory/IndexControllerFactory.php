<?php
namespace Kontakt\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Kontakt\Controller\IndexController;


use Zend\I18n\Translator\TranslatorInterface;
use Zend\Validator\Translator\TranslatorInterface as ValidatorTranslatorInterface;

/**
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

		$config = $container->get('Config');
		$translator = $container->get(TranslatorInterface::class);
		$validatortranslator = $container->get(ValidatorTranslatorInterface::class);
		return new IndexController($config,$translator,$validatortranslator);
    }

}
