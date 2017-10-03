<?php

//глобальный переводчик валидатора

return [
	"Invalid type given. String expected"=>"Не допопустимый адрес",
	"Value is required and can't be empty"=>"Поле обязательно для заполения",
	"The input is not a valid email address. Use the basic format local-part@hostname"=>"Не допустимый формат адреса",
	"'%hostname%' is not a valid hostname for the email address"=>"'%hostname%' - не допустимое имя домена",
	"'%hostname%' does not appear to have any valid MX or A records for the email address"=>"Не допустимая запись MX домена '%hostname%'",
	"'%hostname%' is not in a routable network segment. The email address should not be resolved from public network"=>"Домен '%hostname%' расположен не в публичной сети",
	"The input exceeds the allowed length"=>"Слишком длинный адрес",
	
	"The input does not match the expected structure for a DNS hostname"=>"Не допустимый домен",
	"The input appears to be a local network name but local network names are not allowed"=>"Домен представляется именем локальной сети, но имена локальной сети не разрешены",
	
	"Captcha value is wrong"=>"Не верные символы",
	
];