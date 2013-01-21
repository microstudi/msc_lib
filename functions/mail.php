<?php
/**
* @file functions/mail.php
* @author Ivan Vergés
* @brief Send Mail functions file\n
* This functions uses the classes PHPMailer defined in the file classes/phpmailer.php
*
* @section usage Usage
* m_mail_send("me@email.com","My subject","My TEXT body","My <b>HTML</b> body","you@email.com","she@email.com");\n
*/

/**
 * Sends a mail
 * @param $email destination recipient email
 * @param $subject subject of the email
 * @param $body text body
 * @param $html if exists the html body to send
 * @param $from from email
 * @param $replyTo reply-to email
 * @return the error or empty string if sent is ok
 */
function m_mail_send($email,$subject,$body,$html='',$from='',$replyto='') {
	global $CONFIG;

	//reset the mailer if instantiated
	$CONFIG->mailer = new PHPMailer();

	$CONFIG->mailer->Priority = 3;
	$CONFIG->mailer->Encoding = "8bit";
	$CONFIG->mailer->CharSet = "utf-8";

	if($from) {
		if(strpos($from,"<") !== false) {
			$CONFIG->mailer->FromName = trim(str_replace('"','',substr($from,0,strpos($from,"<"))));
			$CONFIG->mailer->From = trim(str_replace(">","",substr($from,strpos($from,"<")+1)));
		}
		else {
			$CONFIG->mailer->From = $from;
			$CONFIG->mailer->FromName = '';
		}
	}

	$CONFIG->mailer->WordWrap = 0;
	$CONFIG->mailer->Subject = $subject;

	if($html) {
		//is html email?
		$CONFIG->mailer->Body = $html;
		$CONFIG->mailer->isHTML(true);
		$CONFIG->mailer->AltBody = $body;
	}
	else {
		$CONFIG->mailer->Body = $body;
	}
	if(strpos($email,"<") !== false) {
		$e = trim(str_replace(">","",substr($email,strpos($email,"<")+1)));
		$n = trim(str_replace('"','',substr($email,0,strpos($email,"<"))));
		//echo "$email $n $e";
		$CONFIG->mailer->AddAddress($e,$n);
	}
	else {
		$CONFIG->mailer->AddAddress($email);
	}

	if($replyto) {
		$CONFIG->mailer->AddReplyTo($replyto);
	}
	//print_r($CONFIG->mailer);

	$result =  $CONFIG->mailer->Send();

	if($CONFIG->mailer->IsError()) return $CONFIG->mailer->ErrorInfo;
	return '';
}

/**
 * validates a email
 * @param $email email to check
 * @param $check_dns if \b true searches for dns domain existence
 * */
function m_valid_email($email,$check_dns=true) {
	//comprovacio de caracters
	if(!preg_match("/^([-_\.]?[a-z0-9])+@[a-z0-9]+([-_\.]?[a-z0-9])+\.[a-z]{2,4}/i", $email)) return false;

	//aixo només funciona en linux
	if(function_exists('checkdnsrr') && $check_dns) {
		// take a given email address and split it into the username and domain.
		list($userName, $mailDomain) = explode("@", $email);
		if (checkdnsrr($mailDomain, "MX")) {
			// this is a valid email domain!
			return true;
		}
		else {
 			// this email domain doesn't exist! bad dog! no biscuit!
			return false;
		}
	}
	else return true;
}



/**
* Returns a array with all emails in a text
* @param $text text where to find emails
* @return array of emails
*/
function m_get_emails_from_text($text) {
	$emails = array();
	$pattern = "/([\s]*)([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*([ ]+|)@([ ]+|)([a-zA-Z0-9-]+\.)+([a-zA-Z]{2,}))([\s]*)/i";
	preg_match_all($pattern, $text, $matches);
	return array_map('trim', array_unique($matches[0]));
}

/**
 * Returns the real ip of the requester browser
 * */
function m_get_real_ip_addr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>
