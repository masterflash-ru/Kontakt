<?php
/**
 * конфигурация формы
 * создается при помощи фабрики Laminas
 */

namespace Mf\Kontakt;
use Laminas\Form\Element;
use Laminas\Validator\Hostname;

return [
    'elements' => [
        [
            'spec' => [
                'type' => Element\Text::class,
                'name' => 'name',
                'options' => [
                    'label' => 'Ваше имя',
                ]
            ],
        ],

        [
            'spec' => [
                'type' => Element\Text::class,
                'name' => 'email',
                'options' => [
                    'label' => 'Ваш Email',
                ]
            ],
        ],
        [
            'spec' => [
                'type' => Element\Submit::class,
                'name' => 'submit',

                'attributes' => [
                    'value' => 'Отправить',
                ]
            ],
        ],
        
        [
            'spec' => [
                'type' => Element\Csrf::class,
                'name' => 'security',
            ],
        ],

    ],

    /* распределение по fieldset элементам, если требуется
     * вы можете сразу распределить https://docs.zendframework.com/zend-form/quick-start/#creation-via-factory
     * смотри 2-й пример
    'fieldsets' => [
    ],
     */

    /*конфигурация фильтров и валидаторов*/
    'input_filter' => [
        "name" => [
            'required' => true,
            'filters' => [
                [ 'name' => 'StringTrim' ],
                [ 'name' => 'StripTags' ],
            ],
        ],
        "email" => [
            'required' => true,
            'filters' => [
                [ 'name' => 'StringTrim' ],
                [ 'name' => 'StripTags' ],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ],
        
    ],

];