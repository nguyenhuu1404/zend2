<?php
namespace Training\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class VerifyController extends AbstractActionController{
	protected $authService;
	protected $myAuth;
	protected $reCaptcha;
	protected $smtp;
	public function getAuthService(){
		if(!$this->authService){
			$this->authService = $this->getServiceLocator()->get('AuthService');
		}
		return $this->authService;
	}
	public function getMyAuth(){
		if(!$this->myAuth){
			$this->myAuth= $this->getServiceLocator()->get('MyAuth');
		}
		return $this->myAuth;
	}
	public function getReCaptcha(){
		if(!$this->reCaptcha){
			$sm=$this->getServiceLocator();
			$config=$sm->get('config');
			$this->reCaptcha= new \ZendService\ReCaptcha\ReCaptcha($config['recaptcha']['public'],$config['recaptcha']['private']);
		}
		return $this->reCaptcha;
	}
	public function getSmtpTransport(){
		if(!$this->smtp){
			$config=$this->getServiceLocator()->get('config');
			$transport= new SmtpTransport;

			$option= new SmtpOptions(array(
					'name' => 'smtp.gmail.com',
					'host' => 'smtp.gmail.com',
					'port'  => 465,
					'connection_class' => 'login',
					'connection_config' => $config['smtp_config']
							));
			$transport->setOptions($option);	
			$this->smtp=$transport;			
		}
		return $this->smtp;
	}
	public function indexAction(){
		if($this->getAuthService()->hasIdentity()){
			return $this->redirect()->toRoute('training/member');
		}else{
			$this->flashMessenger()->addMessage(' Vui lòng đăng nhập để truy cập vào hệ thống');
			return $this->redirect()->toRoute('training/verify',array('action'=>'login'));
		}
	}
	public function loginAction(){
		$sm=$this->getServiceLocator();
		$form=$sm->get('FormElementManager')->get('VerifyForm');
		$request=$this->getRequest();
		$userTable=$sm->get('UserTable');
		$error="";
		$flash=$this->flashMessenger()->getMessages();
		if($request->isPost()){
			$data=$request->getPost();
			$form->addInputFilterLogin();
			$form->setData($data);
			if($form->isValid()){
				$dataInput=$form->getData();

				$this->getAuthService()->getAdapter()
									   ->setIdentity($dataInput['username'])
									   ->setCredential($dataInput['password']);
				$result=$this->getAuthService()->authenticate();
				if($result->isValid()){
					if($dataInput['remember'] == 1){
						$this->getMyAuth()->setRememberMe(1);
						$this->getAuthService()->setStorage($this->getMyAuth());
					}
					/*$userData=$userTable->getUserByUsername($dataInput['username']);
					$store=array(
							'username' => $dataInput['username'],
							'level'    => $userData->level,
							'id'       => $userData->id
						);*/
					$store=(array) $this->getAuthService()->getAdapter()->getResultRowObject(array('id','username','level', 'access'));
					
					$this->getAuthService()->getStorage()->write($store);
					return $this->redirect()->toRoute('training/member');
				}else{
					$error="Wrong Username or password";
				}

			}
		}
		return new ViewModel(array('form'=>$form,'error'=>$error,'flash'=>$flash));
	}
	public function logoutAction(){
		$this->getAuthService()->clearIdentity();
		$this->getMyAuth()->forgetMe();
		$this->flashMessenger()->addMessage('Thoát khỏi hệ thống thành công');
		return $this->redirect()->toRoute('training/verify',array('action'=>'login'));
	}
	public function forgotAction(){
		$sm=$this->getServiceLocator();
		$form=$sm->get('FormElementManager')->get('VerifyForm');
		$request=$this->getRequest();
		$error=$mess="";
		$captcha= $this->getReCaptcha();
		if($request->isPost()){
			$data=$request->getPost();
			$form->addInputFilterForgot();
			$form->setValidationGroup('email');
			$form->setData($data);
			$result=$captcha->verify($data['recaptcha_challenge_field'],$data['recaptcha_response_field']);

			if($form->isValid() && $result->isValid()){
				$dataInput=$form->getData();
				$userTable=$sm->get('UserTable');
				$row=$userTable->getUserByEmail($dataInput['email']);
				if($row){
					$activeCode= md5($row->username."QHOnline".$row->password);
					$link=$this->url()->fromRoute('training/verify',array('action'=>'active'))."/getinfo/$row->username/$activeCode";
					$mail=$sm->get('MailManager');
					$mess=array(
						'username' => $row->username ,
						'link'     => "http://localhost$link"
					);
					$message=new \QHO\Mail\MailMessage;
					$message->forgotPasswordMessage($mess);
					$dataMailer=array(
						'mailFrom' => 'demo.smtp.qhonline@gmail.com',
						'nameFrom' => 'QHOnline Training',
						'emailTo'  => $row->email,
						'nameTo'   => $row->username,
						'subject'  => 'Xác nhận việc phục hồi mật khẩu',
						'message'  => $message->getMessageInfo()
					);						
					$mail->setDataMailer($dataMailer);
					$mail->getSmtpTransport()->send($mail->getDataMailer());

					$mess="Cảm ơn bạn, chúng tôi đã gởi email chứa liên kết phục hồi mật khẩu cho bạn theo địa chỉ email: $dataInput[email] ,  vui lòng kiểm tra và hoàn tất yêu cầu này";
				}else{
					$error="Email của bạn không tồn tại trong hệ thống";
				}
			}else{
				$error="Mã captcha nhập không chính xác";
			}
		}
		return new ViewModel(array('form'=>$form,'captcha'=>$captcha,'error'=>$error,'mess'=>$mess));
	}
	public function activeAction(){
		$name=$this->params()->fromRoute('name');
		$code=$this->params()->fromRoute('code');
		$sm=$this->getServiceLocator();
		$userTable=$sm->get('UserTable');
		$row=$userTable->getUserByUsername($name);
		if($row){
			$activeCode= md5($row->username."QHOnline".$row->password);
			if($code == $activeCode){
				$newpass=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
				$userTable->resetPassword($name,md5($newpass));
				$link=$this->url()->fromRoute('training/verify',array('action'=>'login'));
				$mail=$sm->get('MailManager');
				$mess=array(
						'username' => $row->username ,
						'link'     => "http://localhost$link",
						'newpass'  => $newpass
					);
				$message=new \QHO\Mail\MailMessage;
				$message->activeCodeMessage($mess);					
				$dataMailer=array(
						'mailFrom' => 'demo.smtp.qhonline@gmail.com',
						'nameFrom' => 'QHOnline Training',
						'emailTo'  => $row->email,
						'nameTo'   => $row->username,
						'subject'  => 'Thay đổi mật khẩu mới thành công',
						'message'  => $message->getMessageInfo()
					);						
				$mail->setDataMailer($dataMailer);
				$mail->getSmtpTransport()->send($mail->getDataMailer());

				$this->flashMessenger()->addMessage('Mật khẩu mới của bạn đã được gởi tới email');
				return $this->redirect()->toRoute('training/verify',array('action'=>'login'));				
			}
		}
		$form=$sm->get('VerifyForm');
		$model=new ViewModel(array(
				'form' => $form,
				'error' => 'Liên kết kích hoạt của bạn không chính xác',
				'mess' => '',
				'captcha' => $this->getReCaptcha(),

			));
		$model->setTemplate('training/verify/forgot');
		return $model;
	}
	
	public function deniedAction() {
		
		return new ViewModel();
	}
		
}