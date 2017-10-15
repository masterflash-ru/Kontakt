<?php
/**
контроллер работы со статичными страницами

 */

namespace Kontakt\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Exception;
use Statpage\Service\Statpage;
use Kontakt\Form\KontaktForm;
use Zend\Captcha;
use Zend\Mail;


class IndexController extends AbstractActionController
{

	protected $config;
	protected $translator;

public function __construct ($config,$translator,$validatortranslator)
	{

		$this->config=$config;
		$this->translator=$translator;
		$this->validatortranslator=$validatortranslator;
	}


public function indexAction()
{
	$locale=$this->params('locale',NULL);
	
	try
	{
		//получим дефолтную локаль, что бы проверить передана ли она в URL
		//это нужно для исключения дубляжей URL
		$default_locale=$this->config["locale_default"];	//разрешенные локали
		
		if ($locale && $this->isMultiLocale() && $default_locale==$locale) {throw new Exception("Запрещено использовать в URL локаль, которая установлена по умолчанию, для исключения дубляжей URL");}
		if ($locale && !$this->isMultiLocale()) {throw new Exception("Запрещено использовать в URL локаль для моноязычного сайта");}

		if (empty($locale)) {$locale=$default_locale;}
		//переключим транслятор на нужную локаль
		$this->translator->setLocale($locale);
		
		//форма
		//здесь создаем капчу исходя из настроек 
		$options=$this->config["captcha"]["options"][$this->config["captcha"]["adapter"]];
		$adapter= "\\".$this->config["captcha"]["adapter"];
		$captcha=new $adapter($options);
		
		$view=new ViewModel();
		
		$form = new KontaktForm($captcha,$this->validatortranslator);
		if ($this->getRequest()->isPost())
			{
				$form->setData($this->params()->fromPost());
				if ($form->isValid()) 
					{
						//данные в норме
						$info=$form->getData();
						if ($_SERVER["REQUEST_TIME"]-$_SESSION["_kontakt_form_"] > 0)
							{
								$mess="Сообщение с сайта:\n\n". $info['message'].
							        "\n Имя: ". $info['name'].
							        "\n E_mail: ".$info['email'];
								//отправляем на почту сообщение
								  if (mb_strpos($mess,"://",0,"UTF-8")===false)
											{
												$mail = new Mail\Message();
												$mail->setEncoding("UTF-8");
												$mail->setBody($mess);
												$mail->setFrom($this->config["email_robot"]);
												$mail->addTo($this->config["admin_emails"]);
												$mail->setSubject("Сообщение с сайта (из контактов) ".$_SERVER["SERVER_NAME"]);
												
												$transport = new Mail\Transport\Sendmail();
												$transport->send($mail);
											}							
							}
						$this->flashMessenger()->addMessage("Сообщение успешно отправлено");
						if ($default_locale!=$locale) 
							{
								return $this->redirect()->toRoute('kontakt',["locale"=>$locale]);
							} 
						return $this->redirect()->toRoute('kontakt');
					}
			}
		
		//если был редирект, то будет сообщение в сессии, тогда выводим другой шаблон, без формы
		$flashMessenger = $this->flashMessenger();
		if ($flashMessenger->hasMessages()) 
			{
				$view->setVariables(["page"=>$page,"message"=>implode("",$flashMessenger->getMessages())]);
				$view->setTemplate("kontakt/index/ok.phtml");
				return $view;
			}		
		
		//вывод страницы и формы
		$view->setVariables(["locale"=>$locale,"form"=>$form]);
		$_SESSION["_kontakt_form_"]=time();
		return $view;
	}
	catch (\Exception $e) 
		{
			//любое исключение - 404
			$this->getResponse()->setStatusCode(404);
		}
}


	
/*мультиязычность разрешена?
возвращает true|false
если опция "locale_enable_list" массив больше 1 элемента, то мультиязычность ДА
*/  
protected function isMultiLocale()
{
	if (!isset($this->config["locale_enable_list"])) {return false;}
	if (isset($this->config["locale_enable_list"]) && is_array($this->config["locale_enable_list"]) 
		&& count($this->config["locale_enable_list"])>1) {return true;}
	return false;
}

}
