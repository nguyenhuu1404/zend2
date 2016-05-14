<?php
namespace Training\Form;
use Zend\Form\Form;
class AccessForm extends Form {
	public function __construct($name) {
		parent::__construct($name);
		$this->setAttribute('method', 'post');
		$this->addElements();
	}
	public function addElements() {
		$this->add(
			array(
				'type' => 'CheckBox',
				'name' => 'usercontroller',
				'attributes' => array(
					'id' => 'user',
				),
				'options' => array(
					'checked_value' => 'user',
					'unchecked_value' => 'OFF'
				)
			)
		);
		
		$this->add(
			array(
				'name' => 'user',
				'type' => 'MultiCheckBox',
				'attributes' => array(
					'class' => 'useraction',
				),
				'options' => array(
					'label' => 'User controller',
					'value_options' => array(
						'index' => 'Index Action',
						'insert' => 'Insert Action',
						'edit' => 'Edit Action',
						'del' => 'Delete Action'
					)
				)
			)
		);
		$this->add(array(
				'type' => 'submit',
				'name' => 'submit',
				'attributes' => array(
						'value' => 'Submit',
						'class' => 'btn btn-primary'
					),
			));	
	}
}