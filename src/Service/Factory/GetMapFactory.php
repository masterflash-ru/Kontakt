<?php
namespace Mf\Kontakt\Service\Factory;

use Interop\Container\ContainerInterface;

/*
Фабрика 
сервис обработки прерывания GetMap simba.sitemap
нужен для генерации карты сайта

$options - массив с ключами


*/

class GetMapFactory
{

public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
{
    $Router=$container->get("Application")->getMvcEvent()->getRouter();

    return new $requestedName($Router,$options);
 }
}

