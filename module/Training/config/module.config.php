<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Training\Controller\User' => 'Training\Controller\UserController',
            'Training\Controller\Verify' => 'Training\Controller\VerifyController',
            'Training\Controller\Chat'  => 'Training\Controller\ChatController',
            'Training\Controller\File'  => 'Training\Controller\FileController',
            'Training\Controller\Book'  => 'Training\Controller\BookController',
			'Training\Controller\Acl'  => 'Training\Controller\AclController'
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'Training' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'layout/layout' => __DIR__.'/../view/training/layout/layout.phtml',
            'layout/auth'   => __DIR__.'/../view/training/layout/auth.phtml',
        )
    ),
    'recaptcha'=> array(
        'public' => '6LeXkvwSAAAAAGjexeQoV57PjKa6cSGQkpy3mx8T',
        'private' => '6LeXkvwSAAAAAP2c8YR7U_qshNUwBGL7wI8K0fjK'
    ),
    'smtp_config' => array(
        'username' => 'demo.smtp.qhonline@gmail.com',
        'password' => 'demoABC123',
        'ssl' => 'ssl'
        ),
    'upload_location' => dirname(__DIR__).'/../../data/uploads',
    'paypal-api' => array(
        'username'      => 'sell333_api1.qhonline.info',
        'password'      => '3MMLCSL8VD36P3SS',
        'signature'     => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AJFQ4dq5E5FtAuOpHZR7ogFLCVZ.',
        'endpoint'      => 'https://api-3t.sandbox.paypal.com/nvp'
    )
);
