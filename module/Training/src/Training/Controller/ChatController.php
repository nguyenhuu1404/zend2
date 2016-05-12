<?php
namespace Training\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
class ChatController extends AbstractActionController{
	protected $authService;	
	public function getAuthService(){
			if(!$this->authService){
				$this->authService = $this->getServiceLocator()->get('AuthService');
			}
			return $this->authService;
	}
	public function getUserInfo(){
		return $this->getAuthService()->getStorage()->read();
	}	
	public function indexAction(){
		$form=new \Training\Form\ChatForm('Chat_Form');
		$request=$this->getRequest();
		$sm=$this->getServiceLocator();
		if($request->isPost()){
			$data=$request->getPost();
			$form->setData($data);
			if($form->isValid()){
				$dataInput=$form->getData();
				$userData=$this->getUserInfo();
				$dataInsert=array(
					'message' => $dataInput['mess'],
					'user_id' => $userData['id']
				);
				$sm->get('ChatsTableGateway')->insert($dataInsert);
				return $this->redirect()->toRoute('training/chat');
			}
		}
		return new ViewModel(array('form'=>$form));
	}
	public function listMessageAction(){
		$sm=$this->getServiceLocator();
		$userTable=$sm->get('UserTable');
		$chatTable=$sm->get('ChatsTableGateway');
		/*$chats=$chatTable->select();
		foreach($chats as $chat){
			$mess['name'] = $userTable->getUserById($chat->user_id)->username;
			$mess['mess'] = $chat->message;
			$mess['time'] = $chat->stamp;
			$data[]=$mess;
		}
		$view= new ViewModel(array('mess'=>$data));*/
		$query=$chatTable->getSql()->select();
		$query->join('users','users.id=chats.user_id',array('username'));
		$data=$chatTable->selectWith($query);
		$view= new ViewModel(array('mess'=>$data));
		$view->setTerminal(true);
		return $view;
	}
}