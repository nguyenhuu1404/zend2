<?php
namespace Training\Model;
use Training\Model\Order;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class OrderTable{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway=$tableGateway;
	}
	public function saveOrder(Order $order){
		$data=array(
				'user_id' => $order->user_id,
				'total'   => $order->total,
				'detail'  => $order->detail,
				'ship_name' => $order->ship_name,
				'ship_address' => $order->ship_address,
			);
		$this->tableGateway->insert($data);
		return $this->tableGateway->lastInsertValue;
	}
	public function getOrderById($orderId){
		$rowSet=$this->tableGateway->select(array('id'=>$orderId));
		return $rowSet->current();
	}
	public function getAllOrderByUserId($userId){
		return $this->tableGateway->select(array('user_id'=>$userId));
	}
}