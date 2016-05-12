<?php
namespace Training\Model;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Training\Model\File;
use Zend\Db\Sql\Select;
class FileTable{
	protected $tableGateway;
	protected $shareTableGateway;
	public function __construct(TableGateway $tableGateway, TableGateway $shareTableGateway){
		$this->tableGateway = $tableGateway;
		$this->shareTableGateway = $shareTableGateway;
	}
	public function saveFile(File $file){
		$data=array(
				'label' => $file->label,
				'filename'=> $file->filename,
				'user_id' => $file->user_id
			);
		if($file->id == 0){
			$this->tableGateway->insert($data);
		}else{
			$this->tableGateway->update($data,array('id'=>$file->id));
		}
		
	}
	public function getFileByUserId($userid){
		return $this->tableGateway->select(array('user_id'=>$userid));
	}
	public function getFileById($id,$option=null){
		if($option == 'withUser'){
			$rowSet=$this->tableGateway->select(function(Select $select) use($id){
				$select->columns(array('id','label','user_id'))
				       ->where(array('files.id'=>$id));
				$select->join('users','files.user_id=users.id',array('username','email'));
			});
		}else{
			$rowSet=$this->tableGateway->select(array('id'=>$id));
		}
		
		$row=$rowSet->current();
		return $row;
	}
	public function deleteFileById($id){
		return $this->tableGateway->delete(array('id'=>$id));
	}
	public function saveShare($fileId,$userId){
		$data=array(
				'file_id' => $fileId,
				'user_id' => $userId
			);
		$this->shareTableGateway->insert($data);
	}
	public function checkFileShared($fileId,$userId){
		$rowSet=$this->shareTableGateway->select(array('file_id'=>$fileId,'user_id'=>$userId));
		if($rowSet->current()){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function getUserSharedByFileId($fileId){
		$row=$this->shareTableGateway->select(function(Select $select) use ($fileId){
			$select->columns(array('file_id','id','stamp'))
				   ->where(array('sharings.file_id'=>$fileId))
				   ->join('users','sharings.user_id=users.id',array('username'));
		});
		return $row;
	}
	public function deleteShareById($id){
		return $this->shareTableGateway->delete(array('id'=>$id));
	}
	public function getSharingById($id){
		$rowSet= $this->shareTableGateway->select(array('id'=>$id));
		return $rowSet->current();
	}
	public function getAllSharedByUserId($userId){
		$row=$this->shareTableGateway->select(function(Select $select) use ($userId){
			$select->columns(array('stamp'))
				   ->where(array('sharings.user_id'=>$userId))
				   ->join('files','sharings.file_id=files.id',array('id','label'))
				   ->join('users','files.user_id=users.id',array('username'));
		});
		return $row;
	}
	public function getLastFileUploaded($userId,$number=10){
		$row=$this->tableGateway->select(function(Select $select) use($userId,$number){
			$select->columns(array('id','label','user_id'))
			       ->where->notEqualTo('user_id',$userId);
			$select->order('id desc')->limit($number);
			$select->join('users','files.user_id=users.id',array('username'));       
		});
		return $row;
	}
}