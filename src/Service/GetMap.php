<?php
namespace Mf\Kontakt\Service;

/*
сервис обработки прерывания GetMap simba.sitemap

*/
use Exception;

class GetMap 
{
    protected $Router;
    protected $type="sitemapindex";
    protected $locale="ru_RU";
    protected $name;


public function __construct($Router, array $options) 
{
    $this->Router=$Router;
    if(isset($options["type"])){
          $this->type=$options["type"];
    }
    if(isset($options["locale"])){
        $this->locale=$options["locale"];
    }
    if(isset($options["name"])){
          $this->name=$options["name"];
    }
}


/**
    * сам обработчик
    */
public function GetMap()
{
        if ($this->type=="sitemapindex"){
            return ["name"=>"kontakt"];
        }
        /*получение списка всех страниц и генерация URL*/
        if ($this->type=="sitemap"){
            if ($this->name!="kontakt"){
                /*если запрос не принадлежит этому модулю то выход*/
                return [];
            }
            $rez=[];

            foreach ($this->Router->getRoutes() as $k=>$r){
                if( false!==strpos ($k,"kontakt" )){
                    $rez[]=[
                        "uri"=>$this->Router->assemble([], ['name' => $k]),
                        "changefreq"=>"weekly"
                    ];

                }
            }
            return $rez;
        }
        throw new  Exception("Недопустимый тип sitemap");
}

}
