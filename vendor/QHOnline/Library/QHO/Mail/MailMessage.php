<?php
namespace QHO\Mail;
class MailMessage{
	protected $message;
	public function forgotPasswordMessage(array $data){
		if(!empty($data)){
			extract($data);
			$this->message="Chào bạn, $username .
Chúng tôi nhận được yêu cầu phục hồi mật khẩu cho tài khoản này. Để xác nhận điều này, vui lòng nhấp chuột vào liên kết bên dưới.
$link
Trân trọng";
		}
	}
	public function activeCodeMessage(array $data){
		if(!empty($data)){
			extract($data);
			$this->message="Chào bạn, $username .
Mật khẩu mới của bạn là: $newpass
Xin vui lòng thay đổi mật khẩu và xóa bỏ email này để đảm bảo tính an toàn cho bạn.
Click vào đây để đăng nhập trở lại hệ thống của chúng tôi;
$link
Trân trọng";
		}
	}
	public function getMessageInfo(){
		if($this->message){
			return $this->message;
		}
	}
}