<?php
/**
 * конфигурация формы контактов
 * для изменения создайте новую конфигурацию по аналогии см. https://docs.zendframework.com/zend-form/quick-start/#creation-via-factory
 * создается при помощи фабрики Laminas
 */

namespace Mf\ Kontakt;
use Laminas\Form\Element;
use Laminas\Form\Factory;
use Laminas\Hydrator\ArraySerializable;
use Laminas\Validator\Hostname;


return [
    'elements' => [
        [
            'spec' => [
                'type' => Element\Text::class,
                'name' => 'email',
                'options' => [
                    'label' => 'Ваш email адрес',
                ]
            ],
        ],
        [
            'spec' => [
                'type' => Element\Textarea::class,
                'name' => 'message',
                'options' => [
                    'label' => 'Сообщение',
                ]
            ],
        ],
        [
            /*фиктивная капча, будет перезаписана из глобального конфига приложения, обязательно имя должно быть captcha
            *если ее удалить, то на форме не будет капчи вообще*/
            'spec' => [
                'type' => Element\Captcha::class,
                'name' => 'captcha',
                'options' => [
                    'label' => 'Символы с картинки',
                    'captcha' => [
                        'class' => 'Dumb',
                    ],
                ],
            ],
        ],
        [
            'spec' => [
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => [
                    'value' => 'Отправить',
                ],
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
        "message" => [
            'required' => true,
            'filters' => [
                [ 'name' => 'StringTrim' ],
                [ 'name' => 'StripTags' ],
            ],
        ],
    ],

];