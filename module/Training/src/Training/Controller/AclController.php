<?php
namespace Training\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\ViewModel;
use Zend\Mvc\MvcEvent;

use Zend\Permissions\Acl\Role\GenericRole as Role;

class AclController extends AbstractActionController {
	
	public function onDisPatch(MvcEvent $e) {
		
		$acl = new \Zend\Permissions\Acl\Acl;
		$acl->deny();//ban dau chan tat ca cac quyen
		//khoi tao cac nhom quyen
		$acl->addRole(new Role('guest'));
		$acl->addRole(new Role('member'), array('guest'));
		$acl->addRole(new Role('admin'), array('member'));
		//thiet lap tai nguyen
		$acl->addResource('training') //module
			->addResource('training:acl', 'training');//controller the kieu module:controller
		//gan nhom quyen vao tai nguyen	
		
		$acl->allow('guest', 'training:acl', array('index', 'deny')); //khach co tat ca cac quyen tren controller verify
		$acl->allow('member', 'training:acl', array('add', 'edit'));
		$acl->allow('admin');// co tat ca cac quyen
		
		//lay ten controller, module, action
		
		$route = $e->getRouteMatch();
		$controller = $route->getParam('controller');
		$moduleName = strtolower(substr($controller, 0, strpos($controller, '\\')));
		//ham strpos lay vi tri cua ky tu trong chuoi
		$arr = explode('\\', $controller);
		$controllerName = strtolower(array_pop($arr));//array_pop lay phan tu cuoi cung cua mang
		$actionName = $route->getParam('action');
	
		
		//kiem tra tinh hop le
		if(!$acl->isAllowed('member', $moduleName.":".$controllerName, $actionName)) {
			return $this->redirect()->toRoute(null, array('controller'=>'acl', 'action'=> 'deny'));
		}
		
		return parent::onDisPatch($e);//chu y parent khong viet hoa
	}
	
	public function indexAction() {
		echo "index action";
		return false;
	}
	public function addAction() {
		echo "add action";
		return false;
	}
	public function editAction() {
		echo "edit action";
		return false;
	}
	public function delAction() {
		echo "del action";
		return false;
	}
	public function denyAction() {
		echo "deny action";
		return false;
	}
}