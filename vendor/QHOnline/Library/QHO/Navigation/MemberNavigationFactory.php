<?php
namespace QHO\Navigation;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
class MemberNavigationFactory extends DefaultNavigationFactory {
	protected function getName(){
		return 'member';
	}
	protected function getPages(ServiceLocatorInterface $sm) {
		if(null === $this->pages) {
			$auth = $sm->get('AuthService');
			$userData = $auth->getStorage()->read();
			if(!empty($userData)) {
				$label = "Logout (".$userData['username'].")";
			}else{
				$label = '';
			}
			$config = $sm->get('config');
			$config['navigation'][$this->getName()][] = array(
				'label' => $label,
				'route' => 'training/verify',
				'action' => 'logout'
			);
			$app = $sm->get('Application');
			$routeMatch = $app->getMvcEvent()->getRouteMatch();
			$router = $app->getMvcEvent()->getRouter();
			$pages = $this->getPagesFromConfig($config['navigation'][$this->getName()]);
			$this->pages = $this->injectComponents($pages, $routeMatch, $router);
		}
		return $this->pages;
	}
}