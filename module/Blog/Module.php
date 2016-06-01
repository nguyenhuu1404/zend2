<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
	
	//khai bao form
	public function getFormElementConfig(){
        return array(
            'factories' => array(
                'PostForm' => function($sm){
                    $form=new \Blog\Form\PostForm('Post_Form');
                    return $form;
                },
				'CommentForm' => function($sm){
                    $form=new \Blog\Form\CommentForm('CommentForm');
                    return $form;
                }
            )
        );
    }
	//khai bao view hepper
	public function getViewHelperConfig() {
		return array(
			'factories' => array(
				'Menu' => function($sm){
					$helper = new View\Helper\Menu($sm);
					return $helper;
				},
				'Unicode' => function($sm){
					$helper = new View\Helper\Unicode($sm);
					return $helper;
				}
			)
		);
	}
	//khai bao service
	public function getServiceConfig(){
        return array(
            'invokables' => array(
                'PostManager' => 'Blog\Service\PostManager',
            ),
        );
    }
}
