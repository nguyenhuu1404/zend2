<?php
namespace Training\Model;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User implements InputFilterAwareInterface{
	public $id;
	public $username;
	public $password;
	public $email;
	public $level;
	public $name;
	public $access;
	protected $inputFilter;
	public function exchangeArray($data){
		if(isset($data['id'])){
			$this->id=$data['id'];
		}
		if(isset($data['username'])){
			$this->username=$data['username'];
		}
		if(isset($data['email'])){
			$this->email=$data['email'];
		}
		if(isset($data['level'])){
			$this->level=$data['level'];
		}
		if(isset($data['name'])){
			$this->name=$data['name'];
		}
		if(isset($data['access'])){
			$this->access=$data['access'];
		}
		if(isset($data['password'])){
			$this->setPassword($data['password']);
		}										
	}
	public function setPassword($pass){
		$this->password=md5($pass);
	}
	public function setInputFilter(InputFilterInterface $inputFilter){
		throw new Exception("Not used");
		
	}
	public function getArrayCopy(){
		return get_object_vars($this);
	}
	public function getInputFilter(){
		if(!$this->inputFilter){
			$input=new InputFilter();
			$factory=new InputFactory();
			$input->add(
					$factory->createInput(
							array(
								'name' => 'username',
								'required' => true,
								'filters' => array(
										array('name'=>'StringTrim'),
										array('name'=>'StripTags'),
									),
								'validators' => array(
					           			array(
						                    'name'    => 'Db\NoRecordExists',
						                    'options' => array(
						                        'table' => 'users',
						                        'field' => 'username',
						                        'adapter' => \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::getStaticAdapter(),
						                        'exclude' => array(
						                        		'field' => 'id',
						                        		'value' => !is_null($this->id) && !empty($this->id) ? $this->id : 0,
						                        		/*if(!is_null($this->id) && !empty($this->$id)){
						                        			value=$this->id;
						                        		}else{
						                        			value=0;
						                        		}*/
						                        	),
						                        'messages' => array(
					                                'recordFound' => 'Tên truy cập đã tồn tại'		
						                         ),	
						                     ),
					                	 ),					
										array('name' => 'StringLength',
											'options'=> array(
													'min' => 3,
													'max' => 20,
													'messages' => array(
														'stringLengthTooShort' => 'Tên truy cập không ít hơn %min% ký tự',
														'stringLengthTooLong' => 'Tên truy cập không được nhiều hơn $max ký tự'
													)
												)),

										array('name'=>'NotEmpty',
											'options'=> array(
													'messages' => array(
															'isEmpty' => 'Tên truy cập không được rỗng'
														)
												)),
									),
							)
						)
				);
			$input->add(
					$factory->createInput(
						array(
							'name' => 'email',
							'required' => true,
							'filters' => array(
									array('name'=>'StringTrim'),
									array('name'=>'StripTags'),
								),
							'validators' => array(
									array('name' => 'EmailAddress'),
									array('name'=>'NotEmpty'),
								),
						))
				);

			$input->add(
					$factory->createInput(
							array(
								'name' => 'password',
								'required' => true,
								'filters' => array(
										array('name'=>'StringTrim'),
										array('name'=>'StripTags'),
									),
								'validators' => array(
										array('name' => 'StringLength',
											'options'=> array(
													'min' => 5,
													'max' => 20,
												)),					
										array('name'=>'NotEmpty'),
									),
							)
						)
				);

			$input->add(
					$factory->createInput(
							array(
								'name' => 'repassword',
								'required' => true,
								'filters' => array(
										array('name'=>'StringTrim'),
										array('name'=>'StripTags'),
									),
								'validators' => array(
										array('name' => 'Identical',
											'options'=> array(
													'token' => 'password'
												)),					
									),
							)
						)
				);

			$input->add(
					$factory->createInput(
							array(
								'name' => 'name',
								'required' => true,
								'filters' => array(
										array('name'=>'StringTrim'),
										array('name'=>'StripTags'),
									),
								'validators' => array(
										array('name'=>'NotEmpty'),
									),
							)
						)

				);			

			$this->inputFilter=$input;
		}
		return $this->inputFilter;
	}
}