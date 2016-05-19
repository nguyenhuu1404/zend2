<?php
namespace QHO\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Mvc\MvcEvent;

class AclPlugin extends AbstractPlugin{
	protected $role;
	protected $access;
	
	public function getAuthService() {
		$sm = $this->getController()->getServiceLocator();
		$authService = $sm->get('AuthService');
		if($authService->hasIdentity()) {
			$user = $authService->getStorage()->read();
			if($user['level'] == 1) {
				$this->role = 'member';
				$this->access = $user['access'];
			}else {
				$this->role = 'admin';
			}
		}else {
			$this->role = 'guest';
		}
		return $this->role;
	}
	public function getAccess() {
		return $this->access;
	}
	public function configAcl() {
		$acl = new Acl;
		$acl->deny();//ban dau chan tat ca cac quyen
		//khoi tao cac nhom quyen
		$acl->addRole(new Role('guest'));
		$acl->addRole(new Role('member'), array('guest'));
		$acl->addRole(new Role('admin'), array('member'));
		//thiet lap tai nguyen
		$acl->addResource('training')
			->addResource('training:book', 'training')
			->addResource('training:chat', 'training')
			->addResource('training:file', 'training')
			->addResource('training:user', 'training')
			->addResource('training:verify', 'training')
		;
		
		//gan nhom quyen cho tai nguyen
		$acl->allow('guest', 'training:verify', array('index', 'login', 'forgot', 'active', 'denied'));
		
		$acl->allow('member', 'training:verify', array('logout'));
		//$acl->allow('member', 'training:book');
		//$acl->allow('member', 'training:file');
		//$acl->allow('member', 'training:chat');
		//$acl->allow('member', 'training:user');
		//quyen cua nguoi dung
		$role = $this->getAuthService();
		
		if($this->access != '') {
			$serialize=new \Zend\Serializer\Adapter\PhpSerialize();	
			$rule = $serialize->unserialize($this->access);
			/*$rule = array(
				'training' => array(
					'user' => array('index', 'edit')
				) 
			);*/

			if($role != "admin" && !empty($rule['training'])) {

				$module = 'training';
				foreach($rule['training'] as $controller => $action) {
					$acl->allow('member', "$module:$controller", $action);
				}

			}
		}
		
		$acl->allow('admin');
		
		return $acl;
	}
	public function roleAccess($e) {
		$role = $this->getAuthService();
		$acl = $this->configAcl();
		//lay ten controller, module, action
		
		$route = $e->getRouteMatch();
		$controller = $route->getParam('controller');
		$moduleName = strtolower(substr($controller, 0, strpos($controller, '\\')));
		//ham strpos lay vi tri cua ky tu trong chuoi
		$arr = explode('\\', $controller);
		$controllerName = strtolower(array_pop($arr));//array_pop lay phan tu cuoi cung cua mang
		$actionName = $route->getParam('action');
		
		
		if(!$acl->isAllowed($role, $moduleName.":".$controllerName, $actionName)) {
			$response = $e->getResponse();
			$response->setStatusCode(302)->setContent('Access deny');
			$response->sendHeaders();
			//chan cac xu ly bang ajax
			if(isset($_SEVER['HTTP_X_REQUESTED_WITH'])) {
				$e->stopPropagation();
			}
		}
	}
	
}

