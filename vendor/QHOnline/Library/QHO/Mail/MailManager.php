<?php
namespace QHO\Mail;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\ServiceLocatorInterface;
class MailManager{
	protected $smtp;
	protected $sm;
	protected $mailData;
	public function __construct(ServiceLocatorInterface $sm){
		$this->sm=$sm;
	}
	public function getSmtpTransport(){
			if(!$this->smtp){
				$config=$this->sm->get('config');
				$transport= new SmtpTransport;

				$option= new SmtpOptions(array(
						'name' => 'smtp.gmail.com',
						'host' => 'smtp.gmail.com',
						'port'  => 465,
						'connection_class' => 'login',
						'connection_config' => $config['smtp_config']
								));
				$transport->setOptions($option);	
				$this->smtp=$transport;			
			}
			return $this->smtp;
	}	
	public function setDataMailer(array $data){
		if(!empty($data)){
			extract($data);
			$mail=new Mail\Message;
			$mail->setFrom($mailFrom,$nameFrom);
			$mail->addTo($emailTo,$nameTo);
			$mail->setSubject($subject);
			$mail->setBody($message);	
			$this->mailData=$mail;		
		}
	}
	public function getDataMailer(){
		if($this->mailData){
			return $this->mailData;
		}
	}
}