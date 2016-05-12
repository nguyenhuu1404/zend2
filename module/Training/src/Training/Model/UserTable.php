<?php
namespace Training\Model;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;
class UserTable{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway){
		$this->tableGateway= $tableGateway;
	}
	public function countAllUser(){
		return $this->tableGateway->select()->count();
	}
	public function listUserWithPaging(array $data){
		$rows=$this->tableGateway->select(function(Select $select) use ($data){
			$select->columns(array('id','username','level'))
			       ->limit($data['ItemCountPerPage'])
			       ->offset(($data['CurrentPageNumber'] - 1)*$data['ItemCountPerPage']);
		});
		return $rows;
	}
	public function fetchAll($paging=false){
		if($paging == true){
			$select=new Select('users');
			$result= new ResultSet;
			$result->setArrayObjectPrototype(new \Training\Model\User);
			$config= new DbSelect(
					$select,
					$this->tableGateway->getAdapter(),
					$result
				);
			$resultData=new Paginator($config);			
		}else{
			$resultData= $this->tableGateway->select();
		}

		return $resultData;
	}
	public function saveUser(User $user){
		$data=array(
				'username' => $user->username,
				'level'    => $user->level,
				'email'    => $user->email,
				'name'     => $user->name,
			);
		if($user->password != ""){
			$data['password'] = $user->password;
		}
		if($user->id == 0){
			$this->tableGateway->insert($data);
		}else{
			$this->tableGateway->update($data,array('id'=>$user->id));
		}
		
	}
	public function getUserById($id){
		$rowset=$this->tableGateway->select(array('id'=>$id));
		$row=$rowset->current();
		return $row;
	}
	public function deleteUserById($id){
		$this->tableGateway->delete(array('id'=>$id));
	}
	public function getUserByUsername($username){
		$rowset=$this->tableGateway->select(array('username'=>$username));
		$row=$rowset->current();
		return $row;
	}
	public function getUserByEmail($email){
		$rowset=$this->tableGateway->select(array('email'=>$email));
		$row=$rowset->current();
		return $row;
	}	
	public function resetPassword($user,$pass){
		$data=array();
		$data['password'] = $pass;
		$this->tableGateway->update($data,array('username'=>$user));
	}
}