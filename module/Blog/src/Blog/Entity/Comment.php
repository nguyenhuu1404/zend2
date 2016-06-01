<?php
namespace Blog\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
* @ORM\Entity
* @ORM\Table(name="comments")
*/

class Comment {
	/**
	* @ORM\Id
	* @ORM\Column(name="id")
	* @ORM\GeneratedValue
	*/
	protected $id;
	/**
	* @ORM\Column(name="email")
	*/
	protected $email;	
	/**
	* @ORM\Column(name="content")
	*/
	protected $content;			
	/**
	* @ORM\Column(name="date_created")
	*/
	protected $dateCreated;
	/**
	*@ORM\ManyToOne(targetEntity="\Blog\Entity\Post", inversedBy="comments")
	*@ORM\JoinColumn(name="post_id", referencedColumnName="id")
	*/
	protected $post;
	public function __construct(){
		$this->post = new ArrayCollection();
	}
	/**	
	* set ID of this comments
	* @param int $id
	*/
	public function setId($id){
		$this->id=$id;
	}
	/**
	* Return ID of this comments
	* @return integer
	*/
	public function getId(){
		return $this->id;
	}
	/**
	* set Email of this comments
	* @param string $email
	*/
	public function setEmail($email){
		$this->email=$email;
	}
	/**
	* Return Email of this comments
	* @return string
	*/
	public function getEmail(){
		return $this->email;
	}
	/**
	* set content of this comments
	* @param string $info
	*/
	public function setContent($content){
		$this->content=$content;
	}
	/**
	* Return content of this comments
	* @return string
	*/
	public function getContent(){
		return $this->content;
	}			
	/**
	* set the date when this comment was created
	* @param string $dateCreated
	*/
	public function setDateCreated($dateCreated){
		$this->dateCreated=$dateCreated;
	}
	/**
	* Return the date when this comment was created
	* @return string
	*/
	public function getDateCreated(){
		return $this->dateCreated;
	}
	public function getPost() {
		return $this->post;
	}
	public function setPost($post) {
		$this->post = $post;
	}
}