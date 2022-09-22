<?

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require  SOURCE.'/kernel/lib/PHPMailer/src/Exception.php';
require  SOURCE.'/kernel/lib/PHPMailer/src/PHPMailer.php';
require  SOURCE.'/kernel/lib/PHPMailer/src/SMTP.php';

class PHPEmail{
	private static $emails=array();
	private static $subject;
	private static $body;
	private static $EmbeddedImages=array();
	private static $Attachments=array();
	function mailTo($emails){
		$data=array();
		if(!is_array($emails)){
			$emails=explode(',',$emails);
		}
		if(count($emails)==1)$emails=$emails[0];
		if(!is_array($emails)){
			$emails=explode(';',$emails);
		}
		if(is_array($emails)){
			foreach($emails as $item){
				PHPEmail:: $emails[]=trim($item);
			}
		}else{
			PHPEmail:: $emails[]=trim($item);
		}
	}
	function Subject($subject){ 
		PHPEmail::$subject=$subject;
	}
	function Body($body){ 
		PHPEmail::$body=$body;
	}
	function AddEmbeddedImage($path, $name){ 
		PHPEmail::$EmbeddedImages[]=array('path'=>$path, 'name'=>$name);
	}
	function AddAttachment($path){ 
		PHPEmail::$Attachments[]=array('path'=>$path);
	}
	function send(){
		$mail = new PHPMailer(true);
		try {
			
			//Server settings
			$mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;	        //Enable verbose debug output
			$mail->isSMTP();                                            //Send using SMTP
			$mail->Host       = EMAIL_SMTP;                     //Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
			$mail->Username   = EMAIL_ADDR;                     //SMTP username
			$mail->Password   = EMAIL_PASS;                               //SMTP password
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		   // $mail->SMTPSecure = 'tls';
			$mail->Port       = EMAIL_PORT;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
			$mail->SMTPAutoTLS = false;
			//$mail->AuthType = 'NTLM';
			/*if(EMAIL_ADDR=='UZMFC'){
				$mail->SMTPOptions = array(
					'ssl' => [
						'verify_peer' => true,
						'verify_depth' => 3,
						'allow_self_signed' => true,
						'peer_name' => 'eps-relay01.hq.corp.mos.ru',
						'cafile' => '/usr/share/nginx/html/cert/email.pem',
					],
				);
			}else{*/
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);
			//}
			
			//Recipients
			$mail->setFrom(EMAIL_FROM);
			
			//$mail->addAddress('damir@inbody-ru.ru');     //Add a recipient
			foreach(PHPEmail:: $emails as $item){
				$mail->addAddress($item);
			}
			//Attachments
			//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

			//Content
			$mail->CharSet = 'UTF-8';
			$mail->isHTML(true); //Set email format to HTML
			$mail->Subject = PHPEmail::$subject;
			$mail->Body    = PHPEmail::$body;
			$mail->AltBody = strip_tags(PHPEmail::$body);
			foreach(PHPEmail::$EmbeddedImages as $item){
				$mail->AddEmbeddedImage($item['path'],$item['name']);
			}
			foreach(PHPEmail::$Attachments as $item){
				$mail->addAttachment($item['path']);
			}
			$mail->send();
			return array('success'=>'true','error'=>'false');
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	}
}
?>