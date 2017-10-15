<?php
/**
работа с контактами
 */

namespace Kontakt;

use Zend\Router\Http\Segment;

/*
 для работы с мультиязычными сайтами, дополните в глобальной конфигурации новые маршруты по примеру:

*/

return [
	//маршруты
    'router' => [
        'routes' => [
			//маршрут для варианта с одним языком
            'kontakt_ru_RU' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/kontakt',
					'constraints' => [
                               	'locale' => '[a-zA-Z_]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
						'locale'	=> 'ru_RU'
                    ],
                ],
			],
	    ],
    ],
	//контроллеры
    'controllers' => [
        'factories' => [
			Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,	
        ],
	],
	'service_manager' => [
			'factories' => [//сервисы-фабрики
				Service\GetControllersInfo::class => Service\Factory\GetControllersInfoFactory::class,
			],
		],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
	
	
	
	'translator' => [
    	'locale' => 'ru_RU',
	    'translation_file_patterns' => [
    		[
				'type'     => 'phparray',
				'base_dir' => __DIR__ . '/../locale',
				'pattern'  => '%s/messages.php',
       		 ],
    	],
	],


	'validator_translator' => [
    	//'locale' => 'ru_RU',			//можно не указывать, если есть стандартный переводчик с настройками
	    'translation_file_patterns' => [
    		[
				'type'     => 'phparray',
				'base_dir' => __DIR__ . '/../locale',
				'pattern'  => '%s/validate_messages.php',
       		 ],
    	],
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
