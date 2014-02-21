<?php
/**
 * This file is part of the msc_lib library (https://github.com/microstudi/msc_lib)
 * Copyright: Ivan Vergés 2011 - 2014
 * License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Send Mail functions file
 * This functions uses the classes PHPMailer defined in the file classes/phpmailer/
 *
 * @category MSCLIB
 * @package Utilities/Mail
 * @author Ivan Vergés
 */

/**
 * Setups the mailer sender params
 *
 * params for gmail:
 *
 * m_mail_set_smtp("smtp.gmail.com", "username@gmail.com", "password", "ssl", 465);
 *
 * Example
 * <code>
 * m_mail_send("me@email.com", "My subject", "My TEXT body", "My <b>HTML</b> body", "you@email.com", "she@email.com");
 * </code>
 *
 * @param  string $host     smtp1.site.com;smtp2.site.com
 * @param  string $username [description]
 * @param  string $password [description]
 * @param  string $secure   '', 'ssl' or 'tls'
 * @param  string $port     [description]
 * @return [type]           [description]
 */
function m_mail_set_smtp($host = '', $username='', $password = '', $secure = '', $port='') {
	global $CONFIG;

	require_once(dirname(dirname(__FILE__)) . "/classes/phpmailer/PHPMailerAutoload.php");

	//reset the mailer if instantiated
	$CONFIG->mailer = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	try {
		$CONFIG->mailer->IsSMTP(); // telling the class to use SMTP
		$CONFIG->mailer->Host          = $host; // sets the SMTP server
		$CONFIG->mailer->SMTPKeepAlive = true;  // SMTP connection will not close after each email sent
		if($username) {
			$CONFIG->mailer->SMTPAuth = true;      // enable SMTP authentication
			$CONFIG->mailer->Username = $username; // SMTP account username
			$CONFIG->mailer->Password = $password; // SMTP account password
		}
		if(in_array($secure, array('', "ssl", "tls"))) $CONFIG->mailer->SMTPSecure = $secure;   // sets the security
		if($port) $CONFIG->mailer->Port = $port; // set the SMTP port

	} catch (phpmailerException $e) {
	  return $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  return $e->getMessage(); //Boring error messages from anything else!
	}
	return true;
}

/**
 * Sends a mail
 * @param $email destination recipient email or array of recipients
 * @param $subject subject of the email
 * @param $body text body
 * @param $html if exists the html body to send
 * @param $from from email
 * @param $replyTo reply-to email
 * @return the error or empty string if sent is ok
 */
function m_mail_send($email, $subject, $body, $html='', $from='', $replyto='') {
	global $CONFIG;

	require_once(dirname(dirname(__FILE__)) . "/classes/phpmailer/PHPMailerAutoload.php");

	if( !($CONFIG->mailer instanceOf PHPMailer) ) {
		$CONFIG->mailer = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
	}
	try {

		$CONFIG->mailer->Priority = 3;
		$CONFIG->mailer->Encoding = "8bit";
		$CONFIG->mailer->CharSet = "utf-8";
		$CONFIG->mailer->WordWrap = 0;

		//reset from
		$CONFIG->mailer->From = '';
		$CONFIG->mailer->FromName = '';
		$CONFIG->mailer->ClearAllRecipients();
		if($from) {
			if(strpos($from, "<") !== false) {
				$CONFIG->mailer->SetFrom(trim(str_replace(">", "", substr($from, strpos($from, "<") + 1))), trim(str_replace('"', '', substr($from, 0, strpos($from, "<")))), false);
			}
			else {
				$CONFIG->mailer->SetFrom($from, '', false);
			}
		}

		$CONFIG->mailer->Subject = $subject;

		if($html) {
			//is html email?
			$CONFIG->mailer->MsgHTML($html);
			$CONFIG->mailer->AltBody = $body;
		}
		else {
			$CONFIG->mailer->Body = $body;
		}
		if(is_array($email)) $emails = $email;
		else 				 $emails = array($email);
		foreach($emails as $email) {
			if(strpos($email, "<") !== false) {
				$e = trim(str_replace(">", "", substr($email, strpos($email, "<") + 1)));
				$n = trim(str_replace('"', '', substr($email, 0, strpos($email, "<"))));
				//echo "$email $n $e";
				$CONFIG->mailer->AddAddress($e, $n);
			}
			else {
				$CONFIG->mailer->AddAddress($email);
			}
		}

		if($replyto) {
			if(strpos($replyto, "<") !== false) {
				$CONFIG->mailer->AddReplyTo(trim(str_replace(">", "", substr($replyto, strpos($replyto, "<") + 1))), trim(str_replace('"', '', substr($replyto, 0, strpos($replyto, "<")))));
			}
			else {
				$CONFIG->mailer->AddReplyTo($replyto);
			}
		}
		//print_r($CONFIG->mailer);

		$result =  $CONFIG->mailer->Send();

	} catch (phpmailerException $e) {
	  return $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  return $e->getMessage(); //Boring error messages from anything else!
	}

	return true;
}

/**
 * validates a email
 * @param $email email to check
 * @param $check_dns if <b>true</b> searches for dns domain existence
 * */
function m_valid_email($email, $check_dns=true) {
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
