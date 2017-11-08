<?php
namespace Kontakt\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Hostname;
use Zend\Form\Element;

use Zend\Validator\AbstractValidator;

class KontaktForm extends Form
{

protected $captcha;
protected $translator;

public function __construct($captcha,$translator)
 {
	 	AbstractValidator::setDefaultTranslator($translator);
		$this->translator=$translator;
		
        parent::__construct('kontakt-form');
     	$this->captcha = $captcha;
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', '#');
       
        
		$this->addElements();
       $this->addInputFilter();          
}
    

    protected function addElements() 
    {
		
        $this->add([            
            'type'  => 'email',
            'name' => 'email',
            'options' => [
               'label' => 'Email',
            ],
            'attributes' => [                
               // 'placeholder'=>'Email',
            ],

        ]);
        
        $this->add([            
            'type'  => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Ваше имя',
            ],
            'attributes' => [                
               // 'placeholder'=>'Ваше имя',
            ],

        ]);
        
        $this->add([            
            'type'  => 'textarea',
            'name' => 'message',
            'options' => [
                'label' => 'Сообщение',
            ],
            'attributes' => [                
               // 'placeholder'=>'Сообщение',
            ],

        ]);
       
		 $this->add([
					'type' =>"Captcha",
					'name' => 'captcha',
					'options' => [
						'label' => '',
						'captcha' =>$this->captcha,
					],
					'attributes' => [                
						'placeholder'=>'Символы с картинки',
					],
				]);

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Отправить',
                'id' => 'submit',
            ],
        ]);
		$this->add(new Element\Csrf('security'));
    }
    

    private function addInputFilter() 
    {

        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);

               
        //email
        $inputFilter->add([
                'name'     => 'email',	//соответсвует имени элемента
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],  
					['name' => 'StripTags'],                 
                ],                
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => Hostname::ALLOW_DNS,
                            'useMxCheck'    => false,                            
                        ],
                    ],
                ],
            ]);                     
        
		//имя
        $inputFilter->add([
                'name'     => 'name',	//соответсвует имени элемента
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],  
					['name' => 'StripTags'],                 
                ],                
            ]);                     
    
	        $inputFilter->add([
                'name'     => 'message',	//соответсвует имени элемента
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],  
					['name' => 'StripTags'],                 
                ],                
            ]);                     

	}        
}

