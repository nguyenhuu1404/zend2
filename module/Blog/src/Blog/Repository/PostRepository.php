<?php
namespace Blog\Repository;
use Doctrine\ORM\EntityRepository;
class PostRepository extends EntityRepository{
	public function getAll(array $data){
		$em=$this->getEntityManager();
		$qb=$em->createQueryBuilder();
		$qb->select('o')->from('\Blog\Entity\Post','o')
						->orderBy('o.id','DESC')
						//tuong duong voi limit bao nhieu recode tren 1 trang
						->setMaxResults($data['ItemCountPerPage'])
						//trang hien hanh - 1 nhan voi tong so recode
						->setFirstResult(($data['CurrentPageNumber'] - 1)*$data['ItemCountPerPage']);
		$post=$qb->getQuery();
		return $post;
	}
	public function getPostByTag($tag,$data){
		$em=$this->getEntityManager();
		$qb=$em->createQueryBuilder();
		$qb->select('o')->from('Blog\Entity\Post','o')
						->join('o.tags','t')
						->where('t.name = :name')
						->orderBy('o.id','DESC')
						->setParameter('name',$tag)
						->setMaxResults($data['ItemCountPerPage'])
						->setFirstResult(($data['CurrentPageNumber'] - 1)*$data['ItemCountPerPage']);						
		$post=$qb->getQuery();
		return $post;
	}
	public function getPostByCateId($id,$data){
		$em=$this->getEntityManager();
		$qb=$em->createQueryBuilder();
		$qb->select('o')->from('Blog\Entity\Post','o')
						->join('o.cate','c')
						->where('c.id = :id')
						->orderBy('o.id','DESC')
						->setParameter('id',$id)
						->setMaxResults($data['ItemCountPerPage'])
						->setFirstResult(($data['CurrentPageNumber'] - 1)*$data['ItemCountPerPage']);	
		$post=$qb->getQuery();
		return $post;														
	}
}