<?php
namespace Kontakt\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Kontakt\Controller\IndexController;
use Statpage\Service\Statpage;

use Zend\I18n\Translator\TranslatorInterface;
use Zend\Validator\Translator\TranslatorInterface as ValidatorTranslatorInterface;

/**
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
		$statpage_service=$container->get(Statpage::class);
		$config = $container->get('Config');
		$translator = $container->get(TranslatorInterface::class);
		$validatortranslator = $container->get(ValidatorTranslatorInterface::class);
		return new IndexController($statpage_service,$config,$translator,$validatortranslator);
    }

}
