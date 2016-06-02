<?php
namespace Blog\Controller;

use Blog\Controller\MainController;
class IndexController extends MainController{
    public function indexAction()
    {
		echo "<h1>Blog Module - Index Controller - Index Action</h1>";
        $em=$this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $cates=$em->getRepository('\Blog\Entity\Categorie')->findOneBy(array('id'=>'1'));
        echo $cates->getName();
        $posts=$cates->getPosts();
        foreach($posts as $post){
            echo "<pre>";
            print_r($post->getTitle());
            echo "</pre>";
        }
        return false;
    }
	public function index2Action() {
		$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		$post = $em->getRepository('\Blog\Entity\Post')->findOneBy(array('id'=>1));
		//$vd = $post[0];
		echo $post->getTitle().'</br>';
		echo $post->getCate()->getName();
		return false;
	}
	//query
	public function index3Action() {
		$em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		$qb=$em->createQueryBuilder();
        $qb->select('o')->from('\Blog\Entity\Post','o')
                        ->where('o.id != :id')
                        ->orderBy('o.id','DESC')
                        ->setParameter('id',1);
        $posts= $qb->getQuery()->getResult();
		//getResult tra ve object, getResultArray tra ra array();
        foreach($posts as $post){
            echo "<pre>";
            print_r($post->getCate()->getName());
            echo "</pre>";           
        }

        return false;
	}
	//join
	public function index4Action(){
        $em=$this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $qb=$em->createQueryBuilder();
        $qb->select('o.title,o.id,c.name')->from('\Blog\Entity\Post','o')
                        ->join('o.cate','c')
                        ->where('o.id != :id')
                        ->orderBy('o.id','DESC')
                        ->setParameter('id',1);
        $posts= $qb->getQuery()->getResult();
		//du lieu tra ve mang do select chi lay mot so field, neu lau tat ca thi tra ve object
        foreach($posts as $post){
            echo "<pre>";
            print_r($post);
            echo "</pre>";           
        }

        return false;        
    }
	//add vao database
    public function index5Action(){
		//set tat ca cac thuoc tinh khai bao trong entity Post
        $em=$this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $cate=$em->getRepository('\Blog\Entity\Categorie')->findOneBy(array('id'=>2));
        $post=new \Blog\Entity\Post;
        $post->setTitle("Làm việc với doctrine trong Zf 2.x");
        $post->setInfo("Mô tả về cách làm việc doctrine trong ZF 2");
        $post->setContent("Chi tiết về cách làm việc trong ZF 2");
        $post->setStatus(1);
        $current=date('Y-m-d H:i:s');
        $post->setDateCreated($current);
        $post->setCate($cate);
		//add to database
        $em->persist($post);
        $em->flush();
        echo "Done";
        return false;
    }
	//edit database
    public function index6Action(){
        $em=$this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
		//cap nhat vao recode co id bang 3
        $post=$em->getRepository('\Blog\Entity\Post')->findOneBy(array('id'=>3));
		//set cac cap nhat
        $post->setTitle("Làm việc với doctrine trong Zend 2.x");
        $post->setInfo("Mô tả về cách làm việc doctrine trong Zend 2.x");
        $post->setContent("Chi tiết về cách làm việc trong Zend 2.x");
        $post->setStatus(2);
		//update vao database
        $em->persist($post);
        $em->flush();
        echo "Done";
        return false;        
    }
	//xoa 1 recode
    public function index7Action(){
        $em=$this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $post=$em->getRepository('\Blog\Entity\Post')->findOneBy(array('id'=>2));
        $em->remove($post);
        $em->flush();
        echo "Done";
        return false;                
    }
}
