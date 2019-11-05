<?php
/**
работа с контактами
 */

namespace Mf\Kontakt;

use Zend\Router\Http\Literal;

/*
 для работы с мультиязычными сайтами, дополните в глобальной конфигурации новые маршруты по примеру:

*/

return [
    //маршруты
    'router' => [
        'routes' => [
        /*маршрут для варианта с одним языком
        для других языков добавьте маршруты по аналогии в конфиге вашего приложения*/
            'kontakt_ru_RU' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/kontakt',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'    => 'index',
                        'locale'    => 'ru_RU'
                    ],
                ],
            ],
            'subs_ru_RU' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/subs',
                    'defaults' => [
                        'controller' => Controller\SubsController::class,
                        'action'    => 'index',
                        'locale'    => 'ru_RU'
                    ],
                ],
            ],
            'call_ru_RU' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/call',
                    'defaults' => [
                        'controller' => Controller\CallController::class,
                        'action'    => 'index',
                        'locale'    => 'ru_RU'
                    ],
                ],
            ],
      ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\SubsController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\CallController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\GetControllersInfo::class => Service\Factory\GetControllersInfoFactory::class,
            Service\GetMap::class => Service\Factory\GetMapFactory::class,

        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

  "kontakt"=>[
        "config"=>[
            "database"  =>  "DefaultSystemDb",
            "cache"     =>  "DefaultSystemCache",
        ],
        /*конфиг элементов ленты*/
        "categories"=>[
            'kontakt_page' =>[
                'description'=>'Страница контактов + форма',
                'tpl' => [
                    'index' => 'kontakt/index/index',     //шаблон вывода страницы
                    'ok' => 'kontakt/index/ok',           //шаблон вывода страницы после отправки формы
                ],
                'layout' => null,                       //макет вывода, по умолчанию текущий
                /*конфигурация формы*/
                "forma"=>__DIR__."/forma.kontakt.config.php",
            ],
            'subs' =>[
                'description'=>'Всплывающее окно для подписок',
                'tpl' => [
                    'index' => 'kontakt/subs/index',     //шаблон вывода страницы
                    'ok' => 'kontakt/subs/ok',           //шаблон вывода страницы после отправки формы
                ],
                /*конфигурация формы*/
                "forma"=>__DIR__."/forma.subs.config.php",
            ],
            'call' =>[
                'description'=>'Всплывающее окно для обратного звонка',
                'tpl' => [
                    'index' => 'kontakt/call/index',     //шаблон вывода страницы
                    'ok' => 'kontakt/call/ok',           //шаблон вывода страницы после отправки формы
                ],
                /*конфигурация формы*/
                "forma"=>__DIR__."/forma.call.config.php",
            ],
        ],
      "enables"=>[ 
          "kontakt_page"=>true,
          "subs"        =>false,
          "call"        =>false,
      ],
  ],

  //локали сайта - перезаписываются в глобальном конфиге
  "locale_default"=>"ru_RU",

  //адреса получателей формы обратной связи по умолчанию
  //переопределите этот параметр в global.php, он заменит текущий
  "admin_emails"=>[],
  //обратный адрес
  "email_robot"=>"robot@".trim($_SERVER["SERVER_NAME"],"w."),

];