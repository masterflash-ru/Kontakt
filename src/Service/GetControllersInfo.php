<?php
namespace Mf\Kontakt\Service;

use Zend\Router\Exception\RuntimeException;
/*
сервис обработки прерывания GetControllersInfoAdmin simba.admin
нужен для генерации ссылок для подстановки в меню сайта или админки для визуализации выбора

*/


class GetControllersInfo 
{
	protected $Router;
	protected $options;
	protected $config;
	
    public function __construct($config,$Router,$options) 
    {
		if (empty($config["locale_enable_list"])){
            $config["locale_enable_list"]=[$config["locale_default"]];
        }
		$this->Router=$Router;
		$this->options=$options;
		$this->config=$config;
    }
    
    /**
    * получить все MVC адреса, разбитые по языкам
    */
    public function getMvc()
    {
        $info["page"]["description"]="Контакты с формой обратной связи";
        $rez['name']=[];
        $rez['url']=[];
        $rez['mvc']=[];
        if ($this->options["category"]!="frontend") {return ;}
        try {
            //данный модуль содержит только сайтовские описатели описатели
            foreach ($this->config["locale_enable_list"] as $k=>$locale){
                $url = $this->Router->assemble([], ['name' => 'kontakt_'.$locale]);
                $mvc=[
                    "route"=>"kontakt_".$locale,
                    'params'=>[]
                ];
                $rez["name"][$locale][]="Страница - {$locale}";
                $rez["mvc"][$locale][]= serialize($mvc);
                $rez["url"][$locale][]=$url;
            }
        
        } catch (RuntimeException $e){
            
        }
        $info["page"]["urls"]=$rez;
        return $info;

    }
    

    /**
    * устаревший вызов, для получения списка MVC адресов, в админ панели
    */
	public function GetDescriptors()
	{
		//данный модуль содержит только сайтовские лписатели описатели
		if ($this->options["name"]) {return [];}
        try {

            $info["page"]["description"]="Контакты с формой обратной связи";
            $rez['name']=[];
            $rez['url']=[];
            $rez['mvc']=[];

            $locale=$this->options["locale"];
            if (!isset($this->options["locale"])) {$this->options["locale"]=$this->config["locale_default"];}

            $url = $this->Router->assemble([], ['name' => 'kontakt_'.$locale]);
                    $mvc=[
                            "route"=>"kontakt_".$locale,
                            'params'=>[]
                        ];
            if($locale==$this->config["locale_default"]) {$locale=" локаль по умолчанию - ".$this->config["locale_default"];}
            $rez["name"][]="Страница - {$locale}";
            $rez["mvc"][]= serialize($mvc);
            $rez["url"][]=$url;
            $info["page"]["urls"]=$rez;

            return $info;
        }catch (RuntimeException $e){
            
        }
	}
	
}



