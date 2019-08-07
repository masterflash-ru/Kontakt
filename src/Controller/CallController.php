<?php
/**
 */

namespace Mf\Kontakt\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ADO\Service\RecordSet;
use ADO\Service\Command;
use Locale;
use Zend\Form\Factory;
use Exception;
use Zend\Validator\AbstractValidator;


class CallController extends AbstractActionController
{

    protected $config;
    protected $translator;
    protected $email_robot;
    protected $admin_emails=[];
    protected $enable;

    public function __construct( $config,$translator)
    {
        $this->translator=$translator;
        AbstractValidator::setDefaultTranslator($translator);
        $this->config=$config["kontakt"]["categories"]["call"];
        $this->email_robot=$config["email_robot"];
        $this->enable=$config["kontakt"]["enables"]["call"];
        $this->locale_default=$config["locale_default"];
        if (!empty($config["admin_emails"])){
            $this->admin_emails=$config["admin_emails"];
        } else {
            $this->admin_emails="local@localhost";
        }
    }
    

/**
* подписка
*/
public function indexAction()
{
    try {
            if (!$this->enable){
                throw new  Exception("call запрещен к использованию в конфиге");
            }

            $locale=$this->params('locale',$this->locale_default);
            //переключим транслятор на нужную локаль
            $this->translator->setLocale(Locale::getPrimaryLanguage($locale));

            $request=$this->getRequest();
            if (!$request->isXmlHttpRequest()) {
                throw new  Exception("Не верное обращение к контроллеру ");
            }
    } catch (Exception $e) {
        //любое исключение - 404
        $this->getResponse()->setStatusCode(404);
        return;
        }

            $view=new ViewModel();
            $view->setTerminal(true);
            $view->setTemplate($this->config["tpl"]["index"]);

            $prg = $this->prg();
            if ($prg instanceof Response) {
                //сюда попадаем когда форма отправлена, производим редирект
                return $prg;
            }

            //форма авторизации
            $factory = new Factory();
            $form    = $factory->createForm(include $this->config["forma"]);


            if ($prg === false){
              //вывод страницы и формы
              $view->setVariables(["form"=>$form,"locale"=>$locale]);
              return $view;
            }

            $form->setData($prg);        
            //данные валидные?
            if($form->isValid()) {
                $data = $form->getData();
                $view->setTemplate($this->config["tpl"]["ok"]);

                $mess="Сообщение с сайта (заявка обратного завонка):<br>\n\n";
                foreach ($form as $k=>$f){
                    if (in_array($k,['captcha','security',"submit"])){continue;}
                    $mess.=$f->getLabel();
                    $mess.=": ".$data[$f->getName()];
                    $mess.= "<br>\n";
                }
                $v=new ViewModel(["message"=>$mess]);
                $v->setTemplate("kontakt/emailer/index");
                $this->Emailer($v,null, $this->admin_emails, ["mailfrom"=>$this->email_robot,"subject"=>"Сообщение с сайта (заявка обратного завонка) ".$_SERVER["SERVER_NAME"]]);
            } 

            $view->setVariables(["form"=>$form,"locale"=>$locale]);
            return $view;

}

}
