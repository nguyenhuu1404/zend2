<?php
namespace Training\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
class UserForm extends Form{
	public function __construct($name){
		parent::__construct($name);
		$this->setAttribute('method','post');
		$this->addElements();
	}
	public function addElements(){
		$this->add(array(
				'name' => 'username',
				'type' => 'text',
				'attributes' => array(
						'class' => 'form-control'
					),
				'options' => array(
						'label' => 'Username'
					),
			));
		$this->add(array(
				'name' => 'email',
				'type' => 'text',
				'attributes' => array(
						'class' => 'form-control'
					),
				'options' => array(
						'label' => 'Email'
					),
			));	
		$this->add(array(
				'name' => 'name',
				'type' => 'text',
				'attributes' => array(
						'class' => 'form-control'
					),
				'options' => array(
						'label' => 'Full Name'
					),
			));	
		$this->add(array(
				'name' => 'password',
				'type' => 'password',
				'attributes' => array(
						'class' => 'form-control'
					),
				'options' => array(
						'label' => 'Password'
					),
			));		
		$this->add(array(
				'name' => 'repassword',
				'type' => 'password',
				'attributes' => array(
						'class' => 'form-control'
					),
				'options' => array(
						'label' => 'Re-password'
					),
			));	
		$this->add(array(
				'name' => 'level',
				'type' => 'select',
				'attributes' => array(
						'class' => 'form-control'
					),
				'options' => array(
						'label' => 'Level',
						'value_options' => array(
								'1' => 'Member',
								'2' => 'Administrator'
							),
					)
			));	
		$this->add(array(
				'name' => 'submit',
				'type' => 'submit',
				'attributes' => array(
						'class' => 'btn btn-primary',
						'value' => 'Submit'
					),
			));									
	}
}