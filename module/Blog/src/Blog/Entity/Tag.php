<?php
namespace Blog\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
* @ORM\Entity
* @ORM\Table(name="tags")
*/
class Tag{
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
	* @ORM\ManytoMany(targetEntity="Blog\Entity\Post",mappedBy="tags")
	*/
	protected $posts;
	public function __construct(){
		$this->posts= new ArrayCollection();
	}
	/**
	* set ID of this tags
	* @param int $id
	*/
	public function setId($id){
		$this->id=$id;
	}
	/**
	* Return ID of this tags
	* @return integer
	*/
	public function getId(){
		return $this->id;
	}
	/**
	* set Name of this tags
	* @param string $name
	*/
	public function setName($name){
		$this->name=$name;
	}
	/**
	* Return Name of this tags
	* @return string
	*/
	public function getName(){
		return $this->name;
	}	
	public function getPosts(){
		return $this->posts;
	}
	public function addPost($post){
		$this->posts[]=$post;
	}
}