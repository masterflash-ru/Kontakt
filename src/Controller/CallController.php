<?php
/**
 */

namespace Mf\Kontakt\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Tovar;
use ADO\Service\RecordSet;
use ADO\Service\Command;
use Locale;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
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
        $this->admin_emails=$config["admin_emails"];
        $this->enable=$config["kontakt"]["enables"]["call"];
        $this->locale_default=$config["locale_default"];
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

                $mess="Сообщение с сайта (заявка обратного завонка):\n\n";
                foreach ($form as $k=>$f){
                    if (in_array($k,['captcha','security',"submit"])){continue;}
                    $mess.=$f->getLabel();
                    $mess.=": ".$data[$f->getName()];
                    $mess.= "\n";
                }

                $mail = new Mail\Message();
                $mail->setEncoding("UTF-8");
                $mail->setBody($mess);
                $mail->setFrom($this->email_robot);
                $mail->addTo($this->admin_emails);
                $mail->setSubject("Сообщение с сайта (заявка обратного завонка) ".$_SERVER["SERVER_NAME"]);
                $transport = new Mail\Transport\Sendmail();
                $transport->send($mail);
            } 

            $view->setVariables(["form"=>$form,"locale"=>$locale]);
            return $view;

}

}
