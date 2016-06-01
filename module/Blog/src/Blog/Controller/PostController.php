<?php
namespace Blog\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//phan trang voi doc trine
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
class PostController extends AbstractActionController{
	public function __construct(){
		session_start();
		//cau hinh session cho kcfinder la false thi moi co quyen truy cap vao de upload file
		$_SESSION['KCFINDER']['disabled'] = false;
	}
	//return entitymanager
	public function getEm(){
		return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	}
	public function indexAction(){
		//goi doctrine entitymanager
		$em = $this->getEm();
		//phan trang thuong
		//lay page hien tai
		$page = (int) $this->params()->fromRoute('page',1);
		$pagingConfig=array(
				'ItemCountPerPage' => 1,
				'CurrentPageNumber' => $page
			);
		$repository=$em->getRepository('\Blog\Entity\Post');	
		//ham get all viet trong PostRepository
		$posts = $repository->getAll($pagingConfig);
		//xu li phan trang
		//phan trang cho bang nao
		$ormPaging = new ORMPaginator($repository->createQueryBuilder('posts'));
		$adapter = new DoctrineAdapter($ormPaging);
		
		$paging = new Paginator($adapter);
		//so bang ghi tren 1 trang
		$paging->setDefaultItemCountPerPage($pagingConfig['ItemCountPerPage']);
		//khai bao trang hien hanh
		$paging->setCurrentPageNumber($pagingConfig['CurrentPageNumber']);
		/*
		$repository=$em->getRepository('\Blog\Entity\Post');
		$posts = $repository->findBy(array(), array('id'=>'desc'));
		//xu li phan trang
		//phan trang cho bang nao
		$ormPaging = new ORMPaginator($repository->createQueryBuilder('posts'));
		$adapter = new DoctrineAdapter($ormPaging);
		
		$paging = new Paginator($adapter);
		//so bang ghi tren 1 trang
		$paging->setDefaultItemCountPerPage(1);
		//khai bao trang hien hanh
		$paging->setCurrentPageNumber($page);
		*/
		
		return new ViewModel(array('posts'=>$posts, 'paging'=>$paging));
	}
	public function readAction() {
		$id = $this->params()->fromRoute('id');
		$em = $this->getEm();
		$post = $em->getRepository('\Blog\Entity\Post')->findOneBy(array('id'=>$id));
		$sm=$this->getServiceLocator();
		$form=$sm->get('FormElementManager')->get('CommentForm');
		
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			$form->setData($data);
			if($form->isValid()) {
				$dataInput = $form->getData();
				/*
				$cmt = new \Blog\Entity\Comment;
				$cmt->setEmail($dataInput['email']);
				$cmt->setContent($dataInput['content']);
				$current = date('Y-m-d H:i:s');
				$cmt->setDateCreated($current);
				$cmt->setPost($post);
				$em->persist($cmt);
				$em->flush();
				*/
				//them comment vao bai viet
				$sm->get('PostManager')->addComment($post,$dataInput);
				//goi view helper tu controller
				//xu li dung dan than thien
				$urlTitle=$sm->get('ViewHelperManager')->get('Unicode')->make($post->getTitle());
				$this->flashMessenger()->addMessage(" Ý kiến của bạn đã được gửi ");
				return $this->redirect()->toRoute('blog/post', array('action'=>'read', 'id'=>$id, 'title'=>$urlTitle));
				
			}
		}
		$flash=$this->flashMessenger()->getMessages();
		return new ViewModel(array('post'=>$post, 'form'=>$form, 'postId'=>$id, 'flash'=>$flash));
	}
	public function tagAction(){
		$tag=$this->params()->fromRoute('tag');
		$tagEncode=str_replace("+"," ",$tag);
		$em=$this->getEm();
		
		$page=(int) $this->params()->fromRoute('page',1); 
		$pagingConfig=array(
				'ItemCountPerPage' => 1,
				'CurrentPageNumber' => $page
			);
		$repository=$em->getRepository('\Blog\Entity\Post');
		$posts=$repository->getPostByTag($tagEncode,$pagingConfig);	
		
		$ormPaging=new ORMPaginator($posts);
		$adapter=new DoctrineAdapter($ormPaging);
		
		$paging=new Paginator($adapter);
		$paging->setDefaultItemCountPerPage($pagingConfig['ItemCountPerPage']);
		$paging->setCurrentPageNumber($pagingConfig['CurrentPageNumber']);
		
		return new ViewModel(array('paging'=>$paging,'tagEncode'=>$tagEncode,'tag'=>$tag));			
	}
	public function cateAction(){
		$id=$this->params()->fromRoute('id');
		$em=$this->getEm();
		$page=(int) $this->params()->fromRoute('page',1); 
		$pagingConfig=array(
				'ItemCountPerPage' => 1,
				'CurrentPageNumber' => $page
			);
			
		$repository=$em->getRepository('\Blog\Entity\Post');
		$cate=$em->getRepository('\Blog\Entity\Categorie')->findOneBy(array('id'=>$id));
		$posts=$repository->getPostByCateId($id,$pagingConfig);	
		
		$ormPaging=new ORMPaginator($posts);
		$adapter=new DoctrineAdapter($ormPaging);
		
		$paging=new Paginator($adapter);
		$paging->setDefaultItemCountPerPage($pagingConfig['ItemCountPerPage']);
		$paging->setCurrentPageNumber($pagingConfig['CurrentPageNumber']);
		
		return new ViewModel(array('paging'=>$paging,'cate'=>$cate));			
	}
	public function addAction(){
		$sm = $this->getServiceLocator();
		//get form
		$form = $sm->get('FormElementManager')->get('PostForm');
		//goi entitymanager de thao tac voi database
		$em = $this->getEm();
		//get all recode in table categorie
		$cates=$em->getRepository('\Blog\Entity\Categorie')->findAll();
		//tao mang giu lieu cho phan tu select(cate_id)
		$options=array();
		foreach($cates as $cate){
			$options[$cate->getId()] = $cate->getName();
		}
		//set value cho phan tu select box voi name cate_id trong form
		$form->get('cate_id')->setValueOptions($options);
		//get cac request gui len server
		$request=$this->getRequest();
		//kiem tra xem request co phai la post khong
		if($request->isPost()){
			//lay cac du lieu post len tu form
			$data=$request->getPost();
			//validate form
			$form->setData($data);
			if($form->isValid()){
				//lay du lieu tu form da duoc validate
				$data = $form->getData();
				/*
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
				
				//set cac tag cho bai viet
				$tags = explode(",",$data['tags']);
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
					//them post_tag
					$post->addTag($tag);
				}
				//save data
				$em->flush();
				*/
				$sm->get('PostManager')->addPost($data);
				$this->flashMessenger()->addMessage(" Thêm bài viết thành công ");
				return $this->redirect()->toRoute('blog/post',array('action'=>'list'));				
			}
		}
		return new ViewModel(array('form'=>$form));
	}
	public function editAction(){
		//lay id bai viet
		$id = $this->params()->fromRoute('id');
		//goi entitymanager thao tao voi doctrine
		$em = $this->getEm();
		$sm = $this->getServiceLocator();
		$form = $sm->get('FormElementManager')->get('PostForm');
		//lay thong tin bai viet theo id
		$post = $em->getRepository('\Blog\Entity\Post')->findOneBy(array('id'=>$id));
		//lay tat ca cac danh muc trong bang categories
		$cates = $em->getRepository('\Blog\Entity\Categorie')->findAll();
		$options=array();
		foreach($cates as $cate){
			$options[$cate->getId()] = $cate->getName();
		}
		//day du lieu cho select box cate_id
		$form->get('cate_id')->setValueOptions($options);
		//set selected	
		$form->get('cate_id')->setAttributes(array('value'=>$post->getCate()->getId(),'selected'=>'true'));	
		
		//xu li khi nguoi dung cap nhat sua
		$request=$this->getRequest();
		if($request->isPost()){
			//validate
			$data=$request->getPost();
			$form->setData($data);
			if($form->isValid()){
				//du lieu tu form sau khi validate
				$dataInput=$form->getData();
				/*
				//bien luu cateId
				$cateId=$em->getRepository('\Blog\Entity\Categorie')->findOneBy(array('id'=>$dataInput['cate_id']));
				//set cac gia tri cho bang post
				$post->setTitle($dataInput['title']);
				$post->setInfo($dataInput['info']);
				$post->setContent($dataInput['content']);
				$post->setStatus($dataInput['status']);
				$post->setCate($cateId);
				
				$em->persist($post);
				//xu li add tags
				//xoa het cac tags di
				$tags=$post->getTags();
				foreach($tags as $tag){
					$post->removeTag($tag);
				}
				//add cac tag moi
				$tags=explode(",",$data['tags']);
				foreach($tags as $tagName){
					$tagName=trim($tagName);
					$tag=$em->getRepository('\Blog\Entity\Tag')->findOneBy(array('name'=>$tagName));
					if($tag == null){
						$tag=new \Blog\Entity\Tag;
					}
					$tag->setName($tagName);
					$tag->addPost($post);
					$em->persist($tag);
					$post->addTag($tag);
				}	
				$em->flush();
				*/
				$sm->get('PostManager')->editPost($post,$dataInput);
				//add flash messenger
				$this->flashMessenger()->addMessage(" Sửa bài viết thành công ");
				return $this->redirect()->toRoute('blog/post',array('action'=>'list'));												
			}
		}
		/*
		//luu cac tag
		$tagStr="";
		//goi cac tac cho bai viet
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
		}*/
		//set cac gia tri cho form edit	
		$data=array(
				'title' => $post->getTitle(),
				'info'  => $post->getInfo(),
				'content' => $post->getContent(),
				'status' => $post->getStatus(),
				'tags'   => $sm->get('PostManager')->convertTagToString($post),
			);
		$form->setData($data);

		return new ViewModel(array('form'=>$form,'postId'=>$id));

	}
	public function delAction(){
		$id=$this->params()->fromRoute('id');
		$em=$this->getEm();
		$post=$em->getRepository('\Blog\Entity\Post')->findOneBy(array('id'=>$id));
		//xoa tat cac cac tu khoa trong bai viet
		$tags=$post->getTags();
		foreach($tags as $tag){
			$post->removeTag($tag);
		}
		//xoa het comment di
		$cmts=$post->getComments();
		foreach($cmts as $cmt){
			$em->remove($cmt);
		}
		//xoa bai viet nay di
		$em->remove($post);
		
		$em->flush();
		$this->flashMessenger()->addMessage(" Xóa bài viết thành công ");
		return $this->redirect()->toRoute('blog/post',array('action'=>'list'));				
	}
	//hien thi cac bai viet
	public function listAction(){
		$em=$this->getEm();
		$posts=$em->getRepository("\Blog\Entity\Post")->findAll();
		$flash=$this->flashMessenger()->getMessages();
		return new ViewModel(array('posts'=>$posts,'flash'=>$flash));
	}
}