<?php
namespace Training\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
class BookController extends AbstractActionController{
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
/*	public function indexAction(){
		$product = array(
		    'id'      => '1',
		    'qty'     => 1,
		    'price'   => 39.95,
		    'name'    => 'PHP Co Ban',
		    'options' => array('author' => 'Kenny')
		);
		$cart=$this->ZendCart()->cart();
		$isOld=FALSE;
		if($cart != ""){

			foreach($cart as $key =>$item){
				if($item['id'] == $product['id']){
					$product=array(
						'token' => $key,
						'qty' => $item['qty'] + 1,
					);
					$this->ZendCart()->update($product);
					$isOld=TRUE;
					break;
				}
			}
		}
		if($isOld == FALSE){
			$this->ZendCart()->insert($product);
		}			
		echo "Done";	
		return false;
	}
	public function index2Action(){
		$data=$this->ZendCart()->cart();
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		return false;
	}
	public function index3Action(){
		$this->ZendCart()->destroy();
		echo "Done";
		return false;
	}*/
	public function indexAction(){
		$sm=$this->getServiceLocator();
		$bookTable=$sm->get('BookTable');
		$books=$bookTable->fetchAll();
		$flash=$this->flashMessenger()->getMessages();
		$count=$this->ZendCart()->total_items();
		return new ViewModel(array('books'=>$books,'flash'=>$flash,'count'=>$count));
	}
	public function addItemAction(){
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$bookTable=$sm->get('BookTable');
		$book=$bookTable->getBookById($id);
		echo "<pre>";
		print_r($book);
		echo "</pre>";
		$product = array(
		    'id'      => $book->id,
		    'qty'     => 1,
		    'price'   => $book->price,
		    'name'    => $book->name,
		    'options' => array('author' => $book->author)
		);
		$cart=$this->ZendCart()->cart();
		$isOld=FALSE;
		if($cart != ""){

			foreach($cart as $key =>$item){
				if($item['id'] == $product['id']){
					$product=array(
						'token' => $key,
						'qty' => $item['qty'] + 1,
					);
					$this->ZendCart()->update($product);
					$isOld=TRUE;
					break;
				}
			}
		}
		if($isOld == FALSE){
			$this->ZendCart()->insert($product);
		}		
		$this->flashMessenger()->addMessage(' Thêm sản phẩm vào giỏ hàng thành công ');	
		return $this->redirect()->toRoute('training/book');
	}
	public function cartAction(){
		$cart=$this->ZendCart()->cart();
		$count=$this->ZendCart()->total_items();
		$total=$this->ZendCart()->total();
		/*echo "<pre>";
		print_r($cart);
		echo "</pre>";*/
		$form=new \Zend\Form\Form;
		$form->setName('sp');
		if($cart != ""){
			foreach($cart as $token =>$item){
				$element= new \Zend\Form\Element\Text($item['id']."[qty]"); 
				$element->setValue($item['qty']);
				$element2= new \Zend\Form\Element\Hidden($item['id']."[token]");
				$element2->setValue($token);

				$form->add($element)
					 ->add($element2);
			}
		}
		$form->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type' => 'submit',
						'value' => 'Cập nhật',
						'class' => 'btn btn-primary'
					),
			));
		$flash=$this->flashMessenger()->getMessages();
		return new ViewModel(array('books'=>$cart,'form'=>$form,'count'=>$count,'flash'=>$flash,'total'=>$total));
	}
	public function updateItemAction(){
		$request=$this->getRequest();
		if($request->isPost()){
			$data=$request->getPost()->toArray();
			foreach($data as $item){
				if(is_array($item)){
					if(is_numeric($item['qty'])){
						$this->ZendCart()->update($item);
					}else{
						$this->flashMessenger()->addMessage(" Số lượng nhập vào không hợp lệ");
						return $this->redirect()->toRoute('training/book',array('action'=>'cart'));						
						break;
					}
				}
			}
		}
		$this->flashMessenger()->addMessage("Cập nhật giỏ hàng thành công");
		return $this->redirect()->toRoute('training/book',array('action'=>'cart'));
	}
	public function removeAllAction(){
		$this->ZendCart()->destroy();
		$this->flashMessenger()->addMessage(" Xóa tất cả sản phẩm thành công");
		return $this->redirect()->toRoute('training/book',array('action'=>'cart'));		
	}
	public function removeItemAction(){
		$id=$this->params()->fromRoute('id');
		$cart=$this->ZendCart()->cart();
		foreach($cart as $token =>$item){
			if($item['id'] == $id){
				$name=$item['name'];
				$this->ZendCart()->remove(array('token'=>$token));
				$this->flashMessenger()->addMessage(" Xóa sản phẩm '$name' thành công");
				return $this->redirect()->toRoute('training/book',array('action'=>'cart'));				
				break;
			}
		}
	}
	public function checkoutAction(){
		$total=$this->ZendCart()->total();
		$sm=$this->getServiceLocator();

		$paypalRequest=$this->getPaypalRequest();

		$paymentDetails = new \SpeckPaypal\Element\PaymentDetails(array(
    							'amt' => $total['sub-total']
		));	
		$express = new \SpeckPaypal\Request\SetExpressCheckout(array('paymentDetails' => $paymentDetails));
		$urlConfirm=$this->url()->fromRoute('training/book',array('action'=>'paymentConfirm'));
		$urlCancel=$this->url()->fromRoute('training/book',array('action'=>'paymentCancel'));
		$express->setReturnUrl('http://localhost'.$urlConfirm);
		$express->setCancelUrl('http://localhost'.$urlCancel);

		$response = $paypalRequest->send($express);	
		$token = $response->getToken();
		$paypalSession=new \Zend\Session\Container('paypal');
		$paypalSession->tokenId=$token;
		return $this->redirect()->toUrl('https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token='.$token.'&useraction=commit');	
	}
	public function paymentConfirmAction(){
		$sm=$this->getServiceLocator();
		$total=$this->ZendCart()->total();		
		$paypalSession=new \Zend\Session\Container('paypal');
		$token=$paypalSession->tokenId;
		if(empty($token)){
			$this->flashMessenger()->addMessage(" Bạn không thể truy cập vào trang hoàn tất mua hàng ");			
			return $this->redirect()->toRoute('training/book',array('action'=>'cart'));
		}
		$paymentDetails = new \SpeckPaypal\Element\PaymentDetails(array(
    							'amt' => $total['sub-total']
		));			
		$details = new \SpeckPaypal\Request\GetExpressCheckoutDetails(array('token' => $token));
		$paypalRequest=$this->getPaypalRequest();
		$response = $paypalRequest->send($details);

		$payerId = $response->getPayerId();		
		$captureExpress = new \SpeckPaypal\Request\DoExpressCheckoutPayment(array(
		    'token'             => $token,
		    'payerId'           => $payerId,
		    'paymentDetails'    => $paymentDetails
		));
		$captureResponse = $paypalRequest->send($captureExpress);	
		$cart=$this->ZendCart()->cart();
		foreach($cart as $token=>$item){
			$id=$item['id'];
			$data[$id]=array(
				'name' => $item['name'],
				'qty'  => $item['qty'],
				'price' => $item['price'],
			);
		}
		$serialize=new \Zend\Serializer\Adapter\PhpSerialize;
		$detail=$serialize->serialize($data);
		$user=$this->getUserInfo();
		$order=new \Training\Model\Order;
		$dataPayment=array(
			'user_id' => $user['id'],
			'total'   => $total['sub-total'],
			'detail'  => $detail,
			'ship_name' => $response->getFirstName().' '.$response->getLastName(),
			'ship_address' => $response->getShipToStreet().', '.$response->getShipToCity().', '.$response->getShipToCountryName(),
		);
		$order->exchangeArray($dataPayment);
		$orderTable=$sm->get('OrderTable');
		$orderId=$orderTable->saveOrder($order);
		$dataPayment['detail'] = $data;
		$dataPayment['time'] = $orderTable->getOrderById($orderId)->stamp;
		$dataPayment['orderId'] = $orderId;
		$paypalSession->tokenId=NULL;
		$this->ZendCart()->destroy();
		return new ViewModel(array('payment'=>$dataPayment));	
	}
	public function historyPaymentAction(){
		$user=$this->getUserInfo();
		$sm=$this->getServiceLocator();
		$orderTable=$sm->get('OrderTable');
		$allOrder=$orderTable->getAllOrderByUserId($user['id']);
		$flash=$this->flashMessenger()->getMessages();
		return new ViewModel(array('orders'=>$allOrder,'flash'=>$flash));
	}
	public function viewOrderAction(){
		$user=$this->getUserInfo();
		$id=$this->params()->fromRoute('id');
		$sm=$this->getServiceLocator();
		$orderTable=$sm->get('OrderTable');
		$order=$orderTable->getOrderById($id);
		if($order->user_id == $user['id']){
			$serialize=new \Zend\Serializer\Adapter\PhpSerialize;
			$books=$serialize->unserialize($order->detail);
			return new ViewModel(array('order'=>$order,'books'=>$books));			
		}else{
			$this->flashMessenger()->addMessage(' Bạn không có quyền truy cập vào hóa đơn này ');
			return $this->redirect()->toRoute('training/book',array('action'=>'historyPayment'));
		}


	}
	public function getPaypalRequest(){
		$sm=$this->getServiceLocator();
		$config=$sm->get('config');
		$paypalConfig = new \SpeckPaypal\Element\Config($config['paypal-api']);
		$adapter= new \Zend\Http\Client\Adapter\Curl();
		$adapter->setOptions(array(
				'curloptions' => array(
						CURLOPT_SSL_VERIFYPEER => false,
					)
			));
		$client = new \Zend\Http\Client;
		$client->setMethod('POST');
		$client->setAdapter($adapter);		
		$paypalRequest = new \SpeckPaypal\Service\Request;
		$paypalRequest->setClient($client);
		$paypalRequest->setConfig($paypalConfig);	
		return $paypalRequest;		
	}
}