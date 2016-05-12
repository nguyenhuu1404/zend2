<?php
namespace Training\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class FileForm extends Form{
	public function __construct($name){
		parent::__construct($name);
		$this->setAttribute('method','post');
		$this->setAttribute('enctype','multipart/form-data');
		$this->addElements();
		$this->addInputFilter();
	}
	public function addElements(){
		$this->add(array(
			'name' => 'label',
			'type' => 'text',
			'attributes' => array(
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Tên tập tin',
			)
		));
		$this->add(array(
			'name' => 'filename',
			'type' => 'file',
			'attributes' => array(
				'class' => 'form-control'
			),
			'options' => array(
				'label' => 'Tập tin',
			)
		));	
		$this->add(array(
			'name' => 'submit',
			'type' => 'submit',
			'attributes' => array(
				'class' => 'btn btn-primary',
				'value' => 'Submit'
			)
		));	
	}
	public function addInputFilter(){
		$input=new InputFilter;
		$this->setInputFilter($input);
		$input->add(array(
			'name' => 'label',
			'required' => true,
			'filters' => array(
				array('name' => 'StringTrim'),
				array('name' => 'StripTags'),
			),
			'validators' => array(
				array('name' => 'NotEmpty')
			)
		));
		$input->add(array(
			'name' => 'filename',
			'required' => true,
		));
	}
}