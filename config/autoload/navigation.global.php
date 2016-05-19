<?php
return array(
	'navigation' => array(
		'default' => array(
			array(
				'label' => 'Home',
				'route' => 'application'
			),
			array(
				'label' => 'Login',
				'route' => 'training/verify'
			)
		),
		'member' => array(
			array(
				'label' => 'Home',
				'route' => 'application'
			),
			array(
				'label' => 'Member Manager',
				'route' => 'training/member',
				'resource' => 'training:user',//khai bao cho phan tich hop zend acl voi navigation
				'privilege' => 'index',
				'pages' => array(
					array(
						'label' => 'List Member',
						'route' => 'training/member',
						'action' => 'index'
					),
					array(
						'label' => 'Add Member',
						'route' => 'training/member',
						'action' => 'add'
					),
					array(
						'label' => 'Edit Member',
						'route' => 'training/member',
						'action' => 'edit'
					),
					array(
						'label' => 'Access Member',
						'route' => 'training/member',
						'action' => 'access'
					)
				)
			),
			array(
				'label' => 'Book store',
				'route' => 'training/book',
				'resource' => 'training:book',
				'privilege' => 'index'
			),
			array(
				'label' => 'File Manager',
				'route' => 'training/file',
				'resource' => 'training:file',
				'privilege' => 'index'
			),
			array(
				'label' => 'Chat System',
				'route' => 'training/chat',
				'resource' => 'training:chat',
				'privilege' => 'index'
			)
		)
	)
);