<?php
namespace Blog\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;
class MainController extends AbstractActionController{
	//muốn gọi hàm onDispatch thì biến đầu vào phải là lớp MvcEvent
	public function onDispatch(MvcEvent $e){
		//tránh ghi dè
		$response = parent::onDispatch($e);
		//xu lí lấy tên module
		$route = $e->getRouteMatch();
		$controller = $route->getParam('controller');
		$moduleName = strtolower(substr($controller, 0, strpos($controller,'\\')));
		//xet layout theo ten module
		$this->layout()->setTemplate('layout/'.$moduleName);
		return $response;
	}
}