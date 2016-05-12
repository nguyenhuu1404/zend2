<?php
namespace QHO\Paginator;
class Paginator{
	public static function make($total,$option){
		$adapter= new \Zend\Paginator\Adapter\Null($total);
		$paging= new \Zend\Paginator\Paginator($adapter);
		$paging->setCurrentPageNumber($option['CurrentPageNumber']);
		$paging->setItemCountPerPage($option['ItemCountPerPage']);
		return $paging;
	}
}