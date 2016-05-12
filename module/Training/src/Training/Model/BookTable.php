<?php
namespace Training\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
class BookTable{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway=$tableGateway;
	}
	public function fetchAll(){
		return $this->tableGateway->select();
	}
	public function getBookById($id){
		$result=$this->tableGateway->select(array('id'=>$id));
		return $result->current();
	}
}