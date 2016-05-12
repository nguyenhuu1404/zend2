<?php
namespace Training\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
class FileController extends AbstractActionController{
	protected $authService;	
	protected $smtp;		
	public function getAuthService(){
			if(!$this->authService){
				$this->authService = $this->getServiceLocator()->get('AuthService');
			}
			return $this->authService;
	}
	public function getUserInfo(){
		return $this->getAuthService()->getStorage()->read();
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
		$sm=$this->getServiceLocator();
		$view=$sm->get('Zend\View\Renderer\PhpRenderer');
		$view->headScript()->appendFile($view->basePath()."/js/script.js","text/javascript");		
		$fileTable=$sm->get('FileTable');
		$userInfo=$this->getUserInfo();
		$flash=$this->flashMessenger()->getMessages();
		$files=$fileTable->getFileByUserId($userInfo['id']);	
		$allShared=$fileTable->getAllSharedByUserId($userInfo['id']);
		$last=$fileTable->getLastFileUploaded($userInfo['id']);
		$lastFileNotShare=array();
		foreach($last as $lastFile){
			if($fileTable->checkFileShared($lastFile->id,$userInfo['id']) == FALSE){
				$lastFileNotShare[]=$lastFile;
			}
		}
		return new ViewModel(array('files'=>$files,'flash'=>$flash,'allshared'=>$allShared,'lastFile'=>$lastFileNotShare));
	}
	public function getFileLocation(){
		$config=$this->getServiceLocator()->get('config');
		return $config['upload_location'];
	}
	public function addAction(){
		$sm=$this->getServiceLocator();
		$form=$sm->get('FormElementManager')->get('FileForm');
		$request=$this->getRequest();
		if($request->isPost()){
			$nofile=$request->getPost()->toArray();
			$file=$request->getFiles()->toArray();
			$data=array_merge_recursive($nofile,$file);
			$form->setData($data);
			if($form->isValid()){
				$size=new \Zend\Validator\File\Size(array('min'=>'10KB','max'=>'2MB'));
				$mime=new \Zend\Validator\File\MimeType(array('application/zip','application/x-rar','application/pdf'));
				$adapter= new \Zend\File\Transfer\Adapter\Http();
				$adapter->setValidators(array($size,$mime),$data['filename']['name']);
				if($adapter->isValid()){
					$adapter->setDestination($this->getFileLocation());
					if($adapter->receive($data['filename']['name'])){
						$dataInput=$form->getData();
						$userInfo=$this->getUserInfo();
						$info=array(
								'label' => $dataInput['label'],
								'filename' => $dataInput['filename']['name'],
								'user_id' => $userInfo['id'],
							);
						$fileObj=new \Training\Model\File;
						$fileObj->exchangeArray($info);
						$fileTable=$sm->get('FileTable');
						$fileTable->saveFile($fileObj);
						$this->flashMessenger()->addMessage(' Thêm tập tin thành công ');
						return $this->redirect()->toRoute('training/file');						
					}
					
				}else{
					$dataError=$adapter->getMessages();
					foreach($dataError as $row){
						$err[]=$row;
					}
					$form->setMessages(array('filename'=>$err));
				}
				
			}
	
		}
		return new ViewModel(array('form'=>$form));
	}
	public function editAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$fileTable=$sm->get('FileTable');
		$data=$fileTable->getFileById($id);
		$form=$sm->get('FormElementManager')->get('FileForm');
		$form->bind($data);
		$request=$this->getRequest();
		$userInfo=$this->getUserInfo();
		$this->isOwner($userInfo['id'],$data->user_id);			
		if($request->isPost()){
			$dataInput=$request->getPost();
			$form->setValidationGroup('label');
			$form->setData($dataInput);
			if($form->isValid()){
				$dataUpdate=$form->getData();
				$fileTable->saveFile($dataUpdate);
				$this->flashMessenger()->addMessage(' Chỉnh sửa tập tin thành công');
				return $this->redirect()->toRoute('training/file');
			}
		}
		return new ViewModel(array('form'=>$form,'fileId'=>$id));
	}
	public function delAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$fileTable=$sm->get('FileTable');
		$fileData=$fileTable->getFileById($id);
		$fileName=$fileData->filename;
		$userInfo=$this->getUserInfo();
		$this->isOwner($userInfo['id'],$fileData->user_id);			
		$path=$this->getFileLocation();
		$fullURL=$path."/$fileName";
		unlink($fullURL);
		$fileTable->deleteFileById($id);
		$this->flashMessenger()->addMessage(' Xóa tập tin thành công ');
		return $this->redirect()->toRoute('training/file');
	}
	public function downloadAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$fileTable=$sm->get('FileTable');
		$userInfo=$this->getUserInfo();		
		$fileData=$fileTable->getFileById($id);
		if($fileTable->checkFileShared($id,$userInfo['id']) == TRUE){
			$path=$this->getFileLocation();
			$fullURL=$path."/$fileData->filename";
			$data=file_get_contents($fullURL);
			$response=$this->getEvent()->getResponse();
			$response->getHeaders()->addHeaders(array(
					'Content-Type' => 'application/octet-stream',
					'Content-Disposition' => 'attachment;filename="'.$fileData->filename.'"'
				));
			$response->setContent($data);
			return $response;			
		}else{
			$this->flashMessenger()->addMessage(' Không thể tải được tập tin này, vì bạn chưa được tác giả chia sẻ ');
			return $this->redirect()->toRoute('training/file');			
		}

	}
	public function shareAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$form=$sm->get('FormElementManager')->get('ShareForm');
		$fileTable=$sm->get('FileTable');
		$userTable=$sm->get('UserTable');
		$allUser=$userTable->fetchAll();
		$fileData=$fileTable->getFileById($id);		
		$opUser=array();
		$userInfo=$this->getUserInfo();
		$this->isOwner($userInfo['id'],$fileData->user_id);		
		$view=$sm->get('Zend\View\Renderer\PhpRenderer');
		$view->headScript()->appendFile($view->basePath()."/js/script.js","text/javascript");			
		$sharedUser=$fileTable->getUserSharedByFileId($id);
		$flash=$this->flashMessenger()->getMessages();
		foreach($allUser as $user){
			if($fileData->user_id != $user->id && $fileTable->checkFileShared($id,$user->id) == FALSE){
				$opUser[$user->id] = $user->username;				
			}
		}
		if(empty($opUser)){
			$opUser[0] = "Đã chia sẻ hết";
			$form->get('submit')->setAttribute('disabled','disabled');
		}
		$form->get('user_id')->setValueOptions($opUser);
		$token=$this->params()->fromRoute('token');
		if($token){
			$userData=explode('-',$token);
			if(md5('QHOnline'.$userData[0]) == $userData[1]){
				$form->get('user_id')->setAttributes(array('value'=>$userData[0],'selected'=>true));
			}
		}
		$request=$this->getRequest();

		if($request->isPost()){
			$dataInput=$request->getPost();
			$form->setData($dataInput);
			if($form->isValid()){
				$dataInsert=$form->getData();
				$fileTable->saveShare($id,$dataInsert['user_id']);
				$this->flashMessenger()->addMessage(' Chia sẻ tập tin thành công');
				return $this->redirect()->toRoute('training/file',array('action'=>'share','id'=>$id));
			}
		}

		return new ViewModel(array('form'=>$form,'fileId'=>$id,'fileData'=>$fileData,'shared'=>$sharedUser,'flash'=>$flash));
	}
	public function removeShareAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$fileTable=$sm->get('FileTable');
		$shareInfo=$fileTable->getSharingById($id);
		$fileInfo=$fileTable->getFileById($shareInfo->file_id);
		$userInfo=$this->getUserInfo();
		$this->isOwner($userInfo['id'],$fileInfo->user_id);
		$fileTable->deleteShareById($id);
		$this->flashMessenger()->addMessage(' Xóa chia sẻ thành công');
		return $this->redirect()->toRoute('training/file',array('action'=>'share','id'=>$shareInfo->file_id));			
	
	}
	public function isOwner($userId,$ownerId){
		if($userId != $ownerId){
			$this->flashMessenger()->addMessage(' Bạn không phải là tác giả của tập tin, nên không thể thực hiện chức năng này');
			return $this->redirect()->toRoute('training/file',array('action'=>'index'));			
		}else{
			return true;
		}
	}
	public function requestShareAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$fileTable=$sm->get('FileTable');
		$userInfo=$this->getUserInfo();
		$fileInfo=$fileTable->getFileById($id,'withUser');
		if($fileInfo){
			$token=md5('QHOnline'.$userInfo['id']);
			$link='http://localhost'.$this->url()->fromRoute('training/file',array('action'=>'share','id'=>$id,'token'=>$userInfo['id'].'-'.$token));
			$message="Chào bạn, $fileInfo->username.
Tôi là $userInfo[username], tôi rất mừng khi bạn đưa lên hệ thống tập tin: $fileInfo->label. Và với tôi, tập tin này rất cần thiết cho công việc hiện tại. Vì thế tôi gởi yêu cầu, mong muốn bạn có thể chia sẻ tập tin này cho tôi.
Để chia sẻ tập tin này, bạn vui lòng nhấp vào đường link bên dưới:
$link 
Cảm ơn bạn, và chúc bạn một ngày tốt lành.";
			$mail=new Mail\Message;
			$mail->setFrom('demo.smtp.qhonline@gmail.com','QHOnline Training');
			$mail->addTo($fileInfo->email,$fileInfo->username);
			$mail->setSubject(' Yêu cầu tải tập tin của bạn ');
			$mail->setBody($message);	
			$this->getSmtpTransport()->send($mail);
			$this->flashMessenger()->addMessage(' Gửi yêu cầu tới tác giả thành công ');
			return $this->redirect()->toRoute('training/file');		
		}

		$this->flashMessenger()->addMessage(' Liên kết không hợp lệ ');
		return $this->redirect()->toRoute('training/file');	
	}
}