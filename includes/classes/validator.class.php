<?php

class validator
{
	function contact_me_validator($message_info)
	{
		$errors = array();

		if($message_info['name'] == '')
		{
			$errors['name'] = 'Please enter your name';
		}
		
		if(!$this->valid_email($message_info['email']))
		{
			$errors['email'] = 'Please a vaild email address';
		}

		if($message_info['phone'] != '' AND strlen($message_info['phone']) < 8)
		{
			$errors['phone'] = 'Please enter a vaild phone number or leave it blank';
		}

		if($message_info['message'] == '')
		{
			$errors['message'] = 'Please enter a vaild message';
		}

		return $errors;
	}

	private function valid_email($email)
	{
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex)
		{
		$isValid = false;
		}
		else
		{
		$domain = substr($email, $atIndex+1);
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64)
		{
			// local part length exceeded
			$isValid = false;
		}
		else if ($domainLen < 1 || $domainLen > 255)
		{
			// domain part length exceeded
			$isValid = false;
		}
		else if ($local[0] == '.' || $local[$localLen-1] == '.')
		{
			// local part starts or ends with '.'
			$isValid = false;
		}
		else if (preg_match('/\\.\\./', $local))
		{
			// local part has two consecutive dots
			$isValid = false;
		}
		else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		{
			// character not valid in domain part
			$isValid = false;
		}
		else if (preg_match('/\\.\\./', $domain))
		{
			// domain part has two consecutive dots
			$isValid = false;
		}
		else if
		(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
				str_replace("\\\\","",$local)))
		{
			// character not valid in local part unless 
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/',
			str_replace("\\\\","",$local)))
			{
			$isValid = false;
			}
		}
		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
		{
			// domain not found in DNS
			$isValid = false;
		}
		}
		return $isValid;
	}
}

?>