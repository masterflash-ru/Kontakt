<?php
/**
работа с контактами
 */

namespace Mf\Kontakt;

use Zend\Router\Http\Segment;

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
                'type' => Segment::class,
                'options' => [
                    'route'    => '/kontakt',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
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
        ],
    ],
    'service_manager' => [
        'factories' => [
          Service\GetControllersInfo::class => Service\Factory\GetControllersInfoFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

  "kontakt"=>[
      /*шаблоны вывода контактов с формой обратной связи*/
      "tpl"=>[
          "index"=>"kontakt/index/index",
          "ok"=>"kontakt/index/ok"
      ],
      /*конфигурация формы обратной связи*/
      "forma"=>__DIR__."/forma.kontakt.config.php",
  ],

  //локали сайта - перезаписываются в глобальном конфиге
  "locale_default"=>"ru_RU",
  "locale_enable_list"=>["ru_RU"],


  //адреса получателей формы обратной связи по умолчанию
  //переопределите этот параметр в global.php, он заменит текущий
  "admin_emails"=>["sxq@yandex.ru"],
  //обратный адрес
  "email_robot"=>"robot@".trim($_SERVER["SERVER_NAME"],"w."),
    

];
