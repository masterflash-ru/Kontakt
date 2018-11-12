Выводит страницу контактов и форму обратной связи
Использует сервис statpage в которой должна быть страница с системным именем KONTAKT и иметь статус "Для внутренних целей".
Форма обратной связи просто отправляется на почту менеджеру. Адрес менеджера указывается в config  с ключем admin_emails, в виде массива.
Обратный адрес хранится в ключе email_robot общего конфига

Стили вывода шаблона контактов находятся в папке public, скопируйте в общий файл стилей приложения.
Для мультиязычных сайтов все в зачаточном уровне, поэтому пока не работает.

Установка
composer require masterflash-ru/kontakt

Конфигурация полей хранится config/forma.config.php, при необходимости вы можете добавить новые поля. Важно имена captcha, submit, security не менять.
Все поля просто отправляются на почту, подписи полей берутся из меток.

Модуль имеет вызовы для генерации карты сайта sitemap.xml, возвращает информацию для модуля masterflash-ru/sitemap для генерации индексного файла и для самой карты, пока только для ru_RU.
Принцип поиска маршрутов производится по начальному слову kontakt в имени маршрута.

для мультиязычных сайтов все готово для работы, и как правило не требуется измнений:
1 - добавить маршрут по аналогии с дефолтным, например,
```php
            'kontakt_en_US' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/en/kontakt',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'    => 'index',
                        'locale'    => 'en_US'
                    ],
                ],
            ],
```
2 - добавить в конфиг приложения секцию транслятора, наподобие:
```php
  'translator' => [
    'locale' => 'en_US',
    'translation_file_patterns' => [
        [
            'type'     => 'phparray',
            'base_dir' => __DIR__ .  '/../locale',
            'pattern'  => '%s.php',
        ],
    ],
  ],
```
3 - добавить языковой перевод в файл, например, en_US.php, наподобие:
```php
return [
  "Ваш email адрес"=>"Your email address",
  
];
```
