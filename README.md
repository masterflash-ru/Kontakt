Выводит страницу контактов и форму обратной связи
Использует сервис statpage в которой должна быть страница с системным именем KONTAKT и иметь статус "Для внутренних целей".
Форма обратной связи просто отправляется на почту менеджеру. Адрес менеджера указывается в config  с ключем admin_emails, в виде массива.
Обратный адрес хранится в ключе email_robot общего конфига

Стили вывода шаблона контактов находятся в папке public, скопируйте в общий файл стилей приложения.

Установка
composer require masterflash-ru/kontakt