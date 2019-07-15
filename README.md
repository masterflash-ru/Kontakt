Выводит страницу контактов и форму обратной связи
Использует сервис statpage в которой должна быть страница с системным именем KONTAKT и иметь статус "Для внутренних целей".
Форма обратной связи просто отправляется на почту менеджеру. Адрес менеджера указывается в config  с ключем admin_emails, в виде массива.
Обратный адрес хранится в ключе email_robot общего конфига

Как дополнительные опции предоставляет работу всплывающих окон для обратного звонка и подписки на чего-либо

Установка
composer require masterflash-ru/kontakt

Конфигурация полей хранится config/forma.ХХХХ.config.php, при необходимости вы можете добавить новые поля, указав новый файл с конфигом формы.
Важно! имена captcha, submit, security не менять.
Все поля просто отправляются на почту, подписи полей берутся из меток. ХХХХ - имя элемента: kontakt, call, subs

Модуль имеет вызовы для генерации карты сайта sitemap.xml, возвращает информацию для модуля masterflash-ru/sitemap для генерации индексного файла и для самой карты, пока только для ru_RU.
Принцип поиска маршрутов производится по начальному слову kontakt в имени маршрута.

для мультиязычных сайтов все готово для работы, и как правило не требуется измнений (ПОКА НЕ ТЕСТИЛ!!!):
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

Настройки модуля (ниже по умолчанию), определите новые опции в своем приложении.
Все поля кроме 'captcha','security',"submit" отправляются на почту. Имя поля берется из опции label конфига формы
```php
  "kontakt"=>[
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
      /*какие элементы разрешено использовать, укажите в конфиге своего приложения */
      "enables"=>[ 
          "kontakt_page"=>true,
          "subs"        =>false,
          "call"        =>false
      ],
  ],
```

Сценарий вывода форм использует bootstrap4, при необходимости используйте свои, указав в сценарии имена в формате Zend
Для работы со всплыващими окнами:

1 - подключите в макете файл jquery.form.min.js для обрабоки асинхронных запросов

2 - ипользуйте диалоги bootstrap4:
```HTML
<button class="btn btn-primary subs mt-2 btn-sm" data-toggle="modal" data-target="#subsModal">Подписаться на рассылку</button>



<div class="modal fade" id="subsModal" tabindex="-1" role="dialog" aria-labelledby="SModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="dialog">
    <div class="modal-content">
      <div class="modal-header">
          <p class="modal-title h5 text-primary" id="exampleModalCenterTitle">Подписаться на рассылку</p>
        <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        </div>
    </div>
  </div>
</div>


<script>
/*диалог подписаться, в момент открытия загружается форма*/
$('#subsModal').on('show.bs.modal', function (event) {
  $(this).find('.modal-body').load("/subs");
})
</script>
```

3 - разумеется все библиотеки bootstrap4 должны быть подключены
