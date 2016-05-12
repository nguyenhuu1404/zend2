<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Training;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

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
        $module= include __DIR__ . '/config/module.config.php';
        $router= include __DIR__ . '/config/router.config.php';
        return array_merge($module,$router);
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $shared=$eventManager->getSharedManager();
        $shared->attach(__NAMESPACE__,'dispatch',function($e){
            $controller=$e->getTarget();
            if($controller instanceof Controller\VerifyController){
                $controller->layout('layout/auth');
            }else{
                $auth=$e->getApplication()->getServiceManager()->get('AuthService');
                $viewModel=$e->getApplication()->getMvcEvent()->getViewModel();
                $userLogin=$auth->getStorage()->read();
                $viewModel->username_layout=$userLogin['username'];
                if(!$auth->hasIdentity()){
                    $controller->plugin('redirect')->toRoute('training/verify',array('action'=>'login'));
                }
            }
        });

    }
    public function getFormElementConfig(){
        return array(
            'factories' => array(
                        'UserForm' => function($sm){
                            $form=new \Training\Form\UserForm('User_Form');
                            $user=new \Training\Model\User;
                            $form->setInputFilter($user->getInputFilter());
                            return $form;
                        },
                        'VerifyForm' => function($sm){
                            $form= new \Training\Form\VerifyForm('Login_Form');
                            return $form;
                        },
                        'FileForm' => function($sm){
                            $form= new \Training\Form\FileForm('File_Form');
                            return $form;
                        }, 
                        'ShareForm' => function($sm){
                            $form= new \Training\Form\ShareForm('Share_Form');
                            return $form;
                        },                        
            )
        );
    }
    public function getServiceConfig(){
        return array(
                'factories' => array(
                        'UserTableGateway' => function($sm){
                            $db=$sm->get('Zend\Db\Adapter\Adapter');
                            $result=new \Zend\Db\ResultSet\ResultSet;
                            $result->setArrayObjectPrototype(new \Training\Model\User);
                            return new \Zend\Db\TableGateway\TableGateway('users',$db,null,$result);
                        },
                        'UserTable' => function($sm){
                            $tableGateway=$sm->get('UserTableGateway');
                            $userTable=new \Training\Model\UserTable($tableGateway);
                            return $userTable;
                        },

                        'AuthService' => function($sm){
                           $adapter=$sm->get('Zend\Db\Adapter\Adapter');
                           $authAdapter=new DbTableAuthAdapter($adapter,'users','username','password', 'MD5(?)');  
                           $auth=new AuthenticationService;   
                           $auth->setAdapter($authAdapter);
                           return $auth;
                        },
                        'MyAuth' => function($sm){
                            $auth= new \Training\Model\MyAuth;
                            return $auth;
                        },
                        'ChatsTableGateway' => function($sm){
                           $db=$sm->get('Zend\Db\Adapter\Adapter');
                           return new \Zend\Db\TableGateway\TableGateway('chats',$db); 
                        }, 

                        'FileTableGateway' => function($sm){
                            $db=$sm->get('Zend\Db\Adapter\Adapter');
                            $result=new \Zend\Db\ResultSet\ResultSet;
                            $result->setArrayObjectPrototype(new \Training\Model\File);
                            return new \Zend\Db\TableGateway\TableGateway('files',$db,null,$result);
                        }, 
                        'ShareTableGateway' => function($sm){
                            $db=$sm->get('Zend\Db\Adapter\Adapter');
                            return new \Zend\Db\TableGateway\TableGateway('sharings',$db);
                        },
                        'FileTable' => function($sm){
                            $tableGateway = $sm->get('FileTableGateway');
                            $shareTableGateway = $sm->get('ShareTableGateway');
                            return new \Training\Model\FileTable($tableGateway,$shareTableGateway);
                        },  
                        'MailManager' => function($sm){
                            $mail=new \QHO\Mail\MailManager($sm);
                            return $mail;
                        },
                        'DataPaging' => function($sm){
                            $paging=new \QHO\Paginator\Paginator;
                            return $paging;
                        },
                        'BookTable' => function($sm){
                            $tableGateway=$sm->get('BookTableGateway');
                            $table=new \Training\Model\BookTable($tableGateway);
                            return $table;
                        },
                        'BookTableGateway' => function($sm){
                            $db=$sm->get('Zend\Db\Adapter\Adapter');
                            $result=new \Zend\Db\ResultSet\ResultSet;
                            $result->setArrayObjectPrototype(new \Training\Model\Book);
                            return new \Zend\Db\TableGateway\TableGateway('books',$db,null,$result);
                        },
                        'OrderTable' => function($sm){
                            $tableGateway=$sm->get('OrderTableGateway');
                            $table=new \Training\Model\OrderTable($tableGateway);
                            return $table;
                        },
                        'OrderTableGateway' => function($sm){
                            $db=$sm->get('Zend\Db\Adapter\Adapter');
                            $result=new \Zend\Db\ResultSet\ResultSet;
                            $result->setArrayObjectPrototype(new \Training\Model\Order);
                            return new \Zend\Db\TableGateway\TableGateway('orders',$db,null,$result);
                        },                                                                 
                    )
            );
    }
}
