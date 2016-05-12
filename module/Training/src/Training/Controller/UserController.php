<?php
namespace Training\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Training\Form\AddUserForm;
class UserController extends AbstractActionController{
	public function demomailAction(){
		$sm=$this->getServiceLocator();
		$mail=$sm->get('MailManager');
		$mess=array(
			'username' => 'Kenny',
			'link'     => 'http://www.qhonline.edu.vn'
		);
		$message=new \QHO\Mail\MailMessage;
		$message->forgotPasswordMessage($mess);
		$data=array(
			'mailFrom' => 'demo.smtp.qhonline@gmail.com',
			'nameFrom' => 'QHOnline Training',
			'emailTo'  => 'kenny@qhonline.info',
			'nameTo'   => 'Kenny',
			'subject'  => 'Kiem tra gui mail thử 2',
			'message'  => $message->getMessageInfo()
		);
		$mail->setDataMailer($data);
		$mail->getSmtpTransport()->send($mail->getDataMailer());
		echo "Done";
		return false;
	}
	public function indexAction(){

		$sm=$this->getServiceLocator();
		$userTable=$sm->get('UserTable');
		$paging=$userTable->fetchAll(true);
		$page=$this->params()->fromRoute('page',1);
		$paging->setCurrentPageNumber($page);
		$paging->setItemCountPerPage(3);

		$view=$sm->get('Zend\View\Renderer\PhpRenderer');
		$view->headScript()->appendFile($view->basePath()."/js/script.js","text/javascript");

		$flash=$this->flashMessenger()->getMessages();
		return new ViewModel(array('data'=>$paging,'flash'=>$flash));
	}
	public function index2Action(){
		$sm=$this->getServiceLocator();
		$userTable=$sm->get('UserTable');
		$totalRecord= $userTable->countAllUser();
		$page=$this->params()->fromRoute('page',1);
		$option=array(
				'ItemCountPerPage' => 3,
				'CurrentPageNumber' => $page,
			);
		$dataUser=$userTable->listUserWithPaging($option);
		/*$adapter=new \Zend\Paginator\Adapter\Null($totalRecord);
		$paging=new \Zend\Paginator\Paginator($adapter);
		$paging->setCurrentPageNumber($page);
		$paging->setItemCountPerPage(3);*/

		$paginator=$sm->get('DataPaging');
		$paging=$paginator->make($totalRecord,$option);

		$view=$sm->get('Zend\View\Renderer\PhpRenderer');
		$view->headScript()->appendFile($view->basePath()."/js/script.js","text/javascript");

		$flash=$this->flashMessenger()->getMessages();		
		return new ViewModel(array('data'=>$dataUser,'paging'=>$paging,'flash'=>$flash));

	}
	public function addAction(){
		$sm=$this->getServiceLocator();
		$userTable=$sm->get('UserTable');		
		$form=$sm->get('FormElementManager')->get('UserForm');
		$request=$this->getRequest();
		if($request->isPost()){
			$data=$request->getPost();
			$form->setData($data);
			if($form->isValid()){
				$data=$form->getData();
				$user=new \Training\Model\User;
				$user->exchangeArray($data);
				$userTable->saveUser($user);
				$this->flashMessenger()->addMessage('Thêm thành viên thành công');
				return $this->redirect()->toRoute('training/member',array('action'=>'index'));
			}
		}
		return new ViewModel(array('form'=> $form));
	}
	public function editAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$userTable=$sm->get('UserTable');
		$form=$sm->get('FormElementManager')->get('UserForm');
		$request=$this->getRequest();
		$dataUser=$userTable->getUserById($id);
		$form->bind($dataUser);
		if($request->isPost()){
			$data=$request->getPost();
			if($data->password == "" && $data->repassword == ""){
				$form->getInputFilter()->remove('password');
				$form->getInputFilter()->remove('repassword');
				unset($dataUser->password);
			}
				
			$form->setData($data);
			if($form->isValid()){
				$dataOk=$form->getData();
				$userTable->saveUser($dataOk);
				$this->flashMessenger()->addMessage("Cập nhật thành viên thành công");
				return $this->redirect()->toRoute('training/member');			
			}


		}

		return new ViewModel(array('form'=>$form,'userid'=>$id));
	}
	public function delAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$userTable=$sm->get('UserTable');
		$userTable->deleteUserById($id);
		$this->flashMessenger()->addMessage("Xóa thành viên thành công");
		return $this->redirect()->toRoute('training/member');
	}
}