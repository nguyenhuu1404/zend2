<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\MvcEvent;

class IndexController extends AbstractActionController
{
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
	public function indexAction()
    {
        return new ViewModel();
    }
}
