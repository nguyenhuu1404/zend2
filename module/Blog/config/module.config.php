<?php
namespace Blog;
return array(
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',
			'Blog\Controller\Post' => 'Blog\Controller\PostController',
			'Blog\Controller\Auth' => 'Blog\Controller\AuthController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/blog',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
					'post' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/post[/:action[/:id[/:title[.html]]][/key/:tag][/page/:page]]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                                'id'     => '[0-9]+',
								'tag'	=> '[a-zA-Z0-9_+-]+',
								'title' => '[^page][a-zA-Z0-9_-]+',//loai bo page khoi title
								'page' => '[0-9]+'
                            ),
                            'defaults' => array(
								// Change this value to reflect the namespace in which
								// the controllers for your module are found
								'__NAMESPACE__' => 'Blog\Controller',
								'controller'    => 'Post',
								'action'        => 'index',
							),
                        ),
                    ),
					'auth' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/auth[/:action]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]+',
                            ),
                            'defaults' => array(
								'__NAMESPACE__' => 'Blog\Controller',
								'controller'    => 'Auth',
								'action'        => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Blog' => __DIR__ . '/../view',
        ),
		//thiet lap layout
		'template_map' => array(
            'layout/blog' => __DIR__.'/../view/blog/layout/layout.phtml',
        ),
    ),
	'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
		//khai bao zend authentication voi doctrine
		'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Blog\Entity\User',//file user trog entity
                'identity_property' => 'username',//verify theo username
                'credential_property' => 'password',//verify theo password
                'credential_callable' => function(\Blog\Entity\User $user, $passwordGiven) {
                    return md5($passwordGiven) == $user->getPassword() && $user->getLevel() == 2; //nguoi dung nhap dung user name passwor va la admin
                },
            ),
        ),       
    ),
);
