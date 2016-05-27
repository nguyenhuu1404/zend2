<?php
//cac file viet trong src phai khai bao namespace
namespace Blog\Form;
use Zend\Form\Form;
//dung de validate form
use Zend\InputFilter\InputFilter;
class PostForm extends Form{
	public function __construct($name){
		parent::__construct($name);
		//set method cho form
		$this->setAttribute('method','POST');
		//set cac phan tu cho form
		$this->addElements();
		//set validator cho form
		$this->addInputFilter();
	}
	public function addElements(){
		$this->add(array(
				'name' => 'title',//name ung voi ten cot
				'type' => 'text',
				'attributes' => array(
						'class' => 'form-control',
					),
				'options' => array(
						'label' => ' Tiêu đề '
					)
			));
		$this->add(array(
				'name' => 'info',
				'type' => 'textarea',
				'attributes' => array(
						'class' => 'form-control',
						'rows' => 5,
						'id' => 'infoEditor'							
					),
				'options' => array(
						'label' => ' Mô tả '
					)
			));	
		$this->add(array(
				'name' => 'content',
				'type' => 'textarea',
				'attributes' => array(
						'class' => 'form-control',
						'rows' => 15,	
						'id' => 'contentEditor'										
					),
				'options' => array(
						'label' => ' Chi tiết '
					)
			));	
		$this->add(array(
				'name' => 'status',
				'type' => 'Radio',
				'attributes' => array(
						'value' => 1,
					),
				'options' => array(
						'label' => ' Tình trạng ',
						'value_options' => array(
								'2' => ' Duyệt ',
								'1' => ' Chưa duyệt'
							)
					)
			));	
		// khai bao chon danh muc, de rong sau do truyen tu controller vao
		$this->add(array(
				'name' => 'cate_id',
				'type' => 'select',
				'attributes' => array(
						'class' => 'form-control',
					),
				'options' => array(
						'label' => ' Chuyên mục'
					)
			));
		$this->add(array(
				'name' => 'tags',
				'type' => 'text',
				'attributes' => array(
						'class' => 'form-control',						
					),
				'options' => array(
						'label' => ' Từ khóa'
					)
			));			
		$this->add(array(
				'name' => 'submit',
				'type' => 'submit',
				'attributes'=> array(
					'class' => 'btn btn-primary',
					'value' => 'Submit'
					),
			));		
	}
	public function addInputFilter(){
		$input=new InputFilter;
		$this->setInputFilter($input);
		$input->add(array(
				'name' => 'title',
				'required' => true,
				'filters' => array(
					array('name'=>'StringTrim'),
					array('name'=>'StripTags')
				),
				'validators' => array(
					array('name' => 'NotEmpty')
				)
			));
		$input->add(array(
				'name' => 'info',
				'required' => true,
				'filters' => array(
					array('name'=>'StringTrim'),
				),
				'validators' => array(
					array('name' => 'NotEmpty')
				)
			));
		$input->add(array(
				'name' => 'content',
				'required' => true,
				'filters' => array(
					array('name'=>'StringTrim'),
				),
				'validators' => array(
					array('name' => 'NotEmpty')
				)
			));	
		$input->add(array(
				'name' => 'tags',
				'required' => true,
				'filters' => array(
					array('name'=>'StringTrim'),
					array('name'=>'StripTags')
				),
				'validators' => array(
					array('name' => 'NotEmpty')
				)
			));							
	}
}