<?php
/**
* контроллер работы с страницей контакты + форма
*/

namespace Mf\Kontakt\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Exception;
use Zend\Captcha;
use Zend\Http\PhpEnvironment\Response;
use Locale;
use Zend\Form\Factory;
use Zend\Validator\AbstractValidator;

class IndexController extends AbstractActionController
{

    protected $config;
    protected $translator;
    protected $captcha;
    protected $locale_default;
    protected $email_robot;
    protected $admin_emails=[];
    protected $enable;

public function __construct ($config,$translator)
{

    $this->config=$config["kontakt"]["categories"]["kontakt_page"];
    $this->translator=$translator;
    $options=$config["captcha"]["options"][$config["captcha"]["adapter"]];
    $captcha= "\\".$config["captcha"]["adapter"];
    $this->captcha=new $captcha($options);
    AbstractValidator::setDefaultTranslator($translator);
    $this->locale_default=$config["locale_default"];
    $this->email_robot=$config["email_robot"];
    $this->enable=$config["kontakt"]["enables"]["kontakt_page"];
    if (!empty($config["admin_emails"])){
        $this->admin_emails=$config["admin_emails"];
    } else {
        $this->admin_emails="local@localhost";
    }
}


public function indexAction()
{
  $locale=$this->params('locale',$this->locale_default);

  try {
      if (!$this->enable){
          throw new  Exception("kontakt_page запрещен к использованию в конфиге");
      }

    $prg = $this->prg();
    if ($prg instanceof Response) {
        //сюда попадаем когда форма отправлена, производим редирект
        return $prg;
    }

    //переключим транслятор на нужную локаль
    $this->translator->setLocale(Locale::getPrimaryLanguage($locale));

    $view=new ViewModel();
    $view->setTemplate($this->config["tpl"]["index"]);
    if ($this->config["layout"]){
        $this->layout($this->config["layout"]);
    }

    
    $factory = new Factory();
    $form    = $factory->createForm(include $this->config["forma"]);
    if ($form->has("captcha")){
        /*здесь создаем капчу исходя из настроек и перезаписываем в конфиг*/
        $form->get("captcha")->setCaptcha($this->captcha);
    }
    
    if ($prg === false){
      //вывод страницы и формы
      $view->setVariables(["locale"=>$locale,"form"=>$form]);
      $_SESSION["_kontakt_form_"]=time();
      return $view;
    }

    $form->setData($prg);

    if ($form->isValid()) {
        //данные в норме
        $info=$form->getData();
        if ($_SERVER["REQUEST_TIME"]-$_SESSION["_kontakt_form_"] > 10) {
            $mess="Сообщение с сайта:<br><br>\n\n";
            foreach ($form as $k=>$f){
                if (in_array($k,['captcha','security',"submit"])){continue;}
                $mess.=$f->getLabel();
                $mess.=": ".$info[$f->getName()];
                $mess.= "<br>\n";
            }
            //отправляем на почту сообщение, запрещаем пересылать URL
            if (mb_strpos($mess,"://",0,"UTF-8")===false){
                $v=new ViewModel(["message"=>$mess]);
                $v->setTemplate("kontakt/emailer/index");
                $this->Emailer($v,null, $this->admin_emails, ["mailfrom"=>$this->email_robot,"subject"=>"Сообщение с сайта (из контактов) ".$_SERVER["SERVER_NAME"]]);
            }
        }
        
        $view->setTemplate($this->config["tpl"]["ok"]);
    }

    $view->setVariables(["locale"=>$locale,"form"=>$form]);
    return $view;
  }
  catch (\Exception $e) {
    //любое исключение - 404
    $this->getResponse()->setStatusCode(404);
  }
}


}
