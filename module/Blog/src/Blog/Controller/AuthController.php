<?php
namespace Blog\Controller;
use Zend\View\Model\ViewModel;
use Blog\Controller\MainController;
class AuthController extends MainController{
		public function indexAction(){
			$sm = $this->getServiceLocator();
			//get service trong file module.php
			$authService = $sm->get('ZendAuth');
			//neu dang nhap thanh cong
			if($authService->hasIdentity()){
				//lay thong tin nguoi dung da dc luu
				$authInfo = $authService->getStorage()->read();
				echo "<pre>";
				print_r($authInfo);
				echo "</pre>";
				echo $authInfo->getUsername();
			}else{
				$this->flashMessenger()->addMessage("Vui lòng đăng nhập để truy cập vào hệ thống");
				return $this->redirect()->toRoute('blog/auth',array('action'=>'login'));
			}
			return false;
		}	
		public function loginAction(){
			$sm=$this->getServiceLocator();
			$form=$sm->get('FormElementManager')->get('AuthForm');
			$request=$this->getRequest();
			$error="";
			$flash=$this->flashMessenger()->getMessages();
			if($request->isPost()){
				$data=$request->getPost();
				$form->setData($data);
				if($form->isValid()){
					$dataInput=$form->getData();
					//goi service
					$authService = $sm->get('ZendAuth');
					//goi adapter
					$adapter = $authService->getAdapter();
					//tra ve cac method cua class
					//$class_methods = get_class_methods($adapter);
					//echo "<pre>";print_r($class_methods);die();	
					//set user name
					$adapter->setIdentity($data['username']);
					//set pass
					$adapter->setCredential($data['password']);
					//lay ket qua tra ve
					$result = $authService->authenticate();
					//kien tra dang nhap co thanh cong hay khong
					if($result->isValid()){
						//lay du lieu nguoi dang nhap
						$store =  $result->getIdentity();
						//luu vao session
						$authService->getStorage()->write($store);
						return $this->redirect()->toRoute('blog/auth');
					}else{
						$error=" Tên truy cập hoặc mật khẩu không chính xác ";
					}
				}
			}
			return new ViewModel(array('form'=>$form,'error'=>$error,'flash'=>$flash));
		}
		public function logoutAction(){
			$sm=$this->getServiceLocator();
			$authService=$sm->get('ZendAuth');
			//xoa thong tin nguoi dung
			$authService->clearIdentity();
			$this->flashMessenger()->addMessage(" Thoát khỏi hệ thống thành công");
			return $this->redirect()->toRoute('blog/auth',array('action'=>'login'));						
		}		
}