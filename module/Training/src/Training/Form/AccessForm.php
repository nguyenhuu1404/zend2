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
						'add' => 'Add Action',
						'edit' => 'Edit Action',
						'del' => 'Delete Action'
					)
				)
			)
		);
		
		$this->add(array(
				'type' => 'CheckBox',
				'name' => 'filecontroller',
				'attributes' => array(
						'id' => 'file',
					),
				'options' => array(
						'checked_value' => 'file',
						'unchecked_value' => 'OFF'
					)
			));
		$this->add(array(
				'name' => 'file',
				'type' => 'MultiCheckBox',
				'attributes' => array(
					'class' => 'fileaction',
				),
				'options' => array(
						'label' => 'File Controller',
						'value_options' => array(
								'index' => 'Index Action',
								'add' => 'Add Action',
								'edit'   => 'Edit Action',
								'del'    => 'Delete Action',
								'download' => 'Download Action',
								'share'   => 'Share Action',
								'removeShare' => "Remove Share Action",
								'requestShare' => 'Request Share Action'
							)
					)
			));

		$this->add(array(
				'type' => 'CheckBox',
				'name' => 'chatcontroller',
				'attributes' => array(
						'id' => 'chat',
					),
				'options' => array(
						'checked_value' => 'chat',
						'unchecked_value' => 'OFF'
					)
			));
		$this->add(array(
				'name' => 'chat',
				'type' => 'MultiCheckBox',
				'attributes' => array(
					'class' => 'chataction',
				),
				'options' => array(
						'label' => 'Chat Controller',
						'value_options' => array(
								'index' => 'Index Action',
								'listMessage' => 'List Message Action',
							)
					)
			));

		$this->add(array(
				'type' => 'CheckBox',
				'name' => 'bookcontroller',
				'attributes' => array(
						'id' => 'book',
					),
				'options' => array(
						'checked_value' => 'book',
						'unchecked_value' => 'OFF'
					)
			));
		$this->add(array(
				'name' => 'book',
				'type' => 'MultiCheckBox',
				'attributes' => array(
					'class' => 'bookaction',
				),
				'options' => array(
						'label' => 'Book Controller',
						'value_options' => array(
								'index' => 'Index Action',
								'addItem' => 'Add Item Action',
								'cart'   => 'Cart Action',
								'updateItem' => 'Update Item Action',
								'removeItem' => 'Remove Item Action',
								'removeAll'  => 'Remove All Action',
								'checkout'   => 'CheckOut Action',
								'paymentConfirm' => 'Payment Confirm Action',
								'historyPayment' => 'History Payment Action',
								'viewOrder'   => 'View Order Action'
							)
					)
			));

		
		
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