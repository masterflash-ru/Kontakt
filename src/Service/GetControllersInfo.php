<?php
namespace Kontakt\Service;

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
		
		$this->Router=$Router;
		$this->options=$options;
		$this->config=$config;
    }
    
	
	public function GetDescriptors()
	{
		//данный модуль содержит только сайтовские лписатели описатели
		if ($this->options["name"]) {return [];}
		if (!isset($this->options["locale"])) {$this->options["locale"]=$this->config["locale_default"];}

		$info["page"]["description"]="Контакты с формой обратной связи";
		$rez['name']=[];
		$rez['url']=[];
		$rez['mvc']=[];

		$locale=$this->options["locale"];

		if ($locale==$this->config["locale_default"]) {$locale="";}
		$url = $this->Router->assemble(["locale"=>$locale], ['name' => 'kontakt']);
				$mvc=[
						"route"=>"kontakt",
						'params'=>["locale"=>$locale]
					];
		if(empty($locale)) {$locale=" локаль по умолчанию - ".$this->config["locale_default"];}
		$rez["name"][]="Страница - {$locale}";
		$rez["mvc"][]= serialize($mvc);
		$rez["url"][]=$url;
		$info["page"]["urls"]=$rez;
		
		return $info;
	}
	
}



