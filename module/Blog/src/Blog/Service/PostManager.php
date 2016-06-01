<?php
namespace Blog\Service;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
class PostManager implements ServiceManagerAwareInterface{
	private $sm;
	//tao service manager
	public function setServiceManager(ServiceManager $sm){
		$this->sm=$sm;
	}
	//goi service manager
	public function getServiceLocator(){
		return $this->sm;
	}
	//goi doctrine entitymanager de thao tac voi co so du lieu
	public function getEm(){
		return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	}
	//tao phuong thu addPost de xu dung khi add du lieu vao bang post
	public function addPost($data){
		$em=$this->getEm();
		//du lieu de set vao truong cate trong bang post, cate la khoa ngoai lien ket bang categories
		$cateId = $em->getRepository('\Blog\Entity\Categorie')->findOneBy(array('id'=>$data['cate_id']));
		//goi entity post
		$post = new \Blog\Entity\Post;
		//set cac du lieu cho bang post
		$post->setTitle($data['title']);
		$post->setInfo($data['info']);
		$post->setContent($data['content']);
		$post->setStatus($data['status']);
		$current=date('Y-m-d H:i:s');
		$post->setDateCreated($current);
		//setCate khoa ngoai
		$post->setCate($cateId);
		//dua vao bo quan li he thong, goi flush de save data
		$em->persist($post);
		//xu li add tag
		$this->addTagsToPost($data['tags'],$post);
		$em->flush();	
	}
	//xu li add tag cho post
	public function addTagsToPost($tagStr,$post){
		$em=$this->getEm();
		//set cac tag cho bai viet
		$tags = explode(",", $tagStr);
		foreach($tags as $tagName){
			$tagName=trim($tagName);
			//goi thuc the tag tim xem trong co so du lieu co tu khoa nay chua
			$tag = $em->getRepository('\Blog\Entity\Tag')->findOneBy(array('name'=>$tagName));
			//neu chua co tu kho nay
			if($tag == null){
				$tag = new \Blog\Entity\Tag;
			}
			//set cac gia tri cho bang tags
			$tag->setName($tagName);
			//them tag vao bang post_tag
			$tag->addPost($post);
			//goi persist de lay id cua bang tags va luu no vao bang post_tag
			$em->persist($tag);
			//them tag vao bang post_tag
			$post->addTag($tag);
		}		
	}
	//edit post
	public function editPost($post,$data){
		$em=$this->getEm();
		$cateId=$em->getRepository('\Blog\Entity\Categorie')->findOneBy(array('id'=>$data['cate_id']));
		$post->setTitle($data['title']);
		$post->setInfo($data['info']);
		$post->setContent($data['content']);
		$post->setStatus($data['status']);
		$post->setCate($cateId);
		$em->persist($post);	
		//remove tag
		$tags=$post->getTags();
		foreach($tags as $tag){
			$post->removeTag($tag);
		}
		$this->addTagsToPost($data['tags'],$post);
		$em->flush();			
	}
	public function convertTagToString($post){
		$tagStr="";
		$tags=$post->getTags();
		if(count($tags)){
			$i=0;
			foreach($tags as $tag){
				$i++;
				$tagStr.= $tag->getName();
				if($i < count($tags)){
					$tagStr.=", ";
				}
			}			
		}
		return $tagStr;		
	}
	//add comment
	public function addComment($post,$data){
		$em=$this->getEm();		
		$cmt=new \Blog\Entity\Comment;
		$cmt->setEmail($data['email']);
		$cmt->setContent($data['content']);
		$current=date('Y-m-d H:i:s');
		$cmt->setDateCreated($current);
		$cmt->setPost($post);
		$em->persist($cmt);
		$em->flush();		
	}	
}