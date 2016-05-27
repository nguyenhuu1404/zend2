<?php
namespace Blog\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Blog\Entity\Post;
/**
* @ORM\Entity
* @ORM\Table(name="categories")
*/
class Categorie{
	/**
	* @ORM\Id
	* @ORM\Column(name="id")
	* @ORM\GeneratedValue
	*/
	protected $id;
	/**
	* @ORM\Column(name="name")
	*/
	protected $name;
	/**
	* @ORM\OneToMany(targetEntity="\Blog\Entity\Post",mappedBy="cate")
	* @ORM\JoinColumn(name="id",referencedColumnName="cate_id")
	*/
	protected $posts;

	public function __construct(){
		$this->posts=new ArrayCollection();
	}

	/**
	* set ID of this categories
	* @param int $id
	*/
	public function setId($id){
		$this->id=$id;
	}
	/**
	* Return ID of this categorie
	* @return integer
	*/
	public function getId(){
		return $this->id;
	}
	/**
	* set Name of this categories
	* @param string $name
	*/
	public function setName($name){
		$this->name=$name;
	}
	/**
	* Return Name of this categorie
	* @return string
	*/
	public function getName(){
		return $this->name;
	}	
	/**
	* Return posts of this categorie
	* @return array
	*/	
	public function getPosts(){
		return $this->posts;
	}
}