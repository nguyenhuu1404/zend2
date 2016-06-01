<?php
namespace Blog\View\Helper;
use Zend\View\Helper\AbstractHelper;
class Menu extends AbstractHelper{
	protected $sm;
	public function __construct($sm) {
		$this->sm = $sm;
	}
	public function callMenu() {
		$em = $this->sm->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		$menus = $em->getRepository('\Blog\Entity\Categorie')->findAll();
		$str='<div class="panel panel-default">';
		$str.='<div class="panel-heading">';
		$str.='		<h3 class="panel-title">Chuyên mục</h3>';
		$str.='	</div>';
		$str.='	<div class="panel-body">';
		$str.='<ul class="nav nav-pills nav-stacked">';
		foreach($menus as $menu){
			$url=$this->view->url('blog/post',array('action'=>'cate','id'=>$menu->getId()));
			$str.='<li><a href="'.$url.'">'.$menu->getName().'</a></li>';
		}
		$str.='</ul>';
		$str.='	</div>';
		$str.='</div>';
		return $str;
	}
}