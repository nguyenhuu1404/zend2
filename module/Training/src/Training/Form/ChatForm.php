<?php
namespace Training\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
class ChatForm extends Form{
	public function __construct($name){
		parent::__construct($name);
		$this->setAttribute('method','post');
		$this->addElements();
		$this->addInputFilter();
	}
	public function addElements(){
		$this->add(array(
				'name' => 'mess',
				'type' => 'text',
				'attributes' => array(
					'size' => 60,
				),
				'options' => array(
						'label' => 'Nội dung'
					)

			));
		$this->add(array(
				'type' => 'submit',
				'name' => 'submit',
				'attributes' => array(
						'value' => 'Submit',
						'class' => 'btn btn-primary'
					)
			));
		$this->add(array(
				'name' => 'refresh',
				'attributes' => array(
						'type' => 'button',					
						'value' => 'Refresh',
						'class' => 'btn btn-info',
						'id'    => 'Refresh'
					)
			));		
	}
	public function addInputFilter(){
		$input=new InputFilter;
		$this->setInputFilter($input);
		$input->add(array(
				'name' => 'mess',
				'required' => true,
				'filters' => array(
					array('name'=>'StringTrim'),
					array('name' => 'StripTags'),
				),
				'validators' => array(
					array('name' => 'NotEmpty')
				)
			));
	}

}