<?
	// MIME email
	function api_email($to_name, $to_email, $adrBcc, $from_name, $from_email, $subject, $body_simple, $body_plain, $body_html, $bounceemail) 
	{
		$boundary = api_password(16);
		$headers = "From: \"".$from_name."\" <".$from_email.">\n";
		$headers .= "To: \"".$to_name."\" <".$to_email.">\n";
		//$headers .= "BCC: ".$adrBcc."\n";
		$headers .= "Return-Path: <".$from_email.">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/alternative; boundary=\"".$boundary."\"\n\n";
		$headers .= $body_simple."\n";
		$headers .= "--".$boundary."\n";
		$headers .= "Content-Type: text/plain; charset=ISO-8859-1\n";
		$headers .= "Content-Transfer-Encoding: 8bit\n\n";
		$headers .= $body_plain."\n";
		$headers .= "--".$boundary."\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$headers .= "Content-Transfer-Encoding: 8bit\n\n";
		$headers .= $body_html."\n";
		$headers .= "--".$boundary."--\n";
		@mail('', $subject, '', $headers, "-f ".$bounceemail."");
	}
	// Generate random alphanumeric password 
	function api_password($length = 8) 
	{ 
		srand((double) microtime() * 1000000); 
		$alpha = array('a','b','c','d','e','f','g','h','i','j','k','l', 
		'm','n','o','p','q','r','s','t','u','v','w','x','y','z'); 
		$options = array('alpha','number'); 
		
		for ($i = 0; $i < $length; $i++) 
		{ 
			$char = array_rand($options,1); 
			if ($options[$char] == 'alpha') 
			{ 
				$random_letter = rand (0,25); 
				$password .= $alpha[$random_letter]; 
			} 
			else 
			{ 
				$random_number = rand (0,9); 
				$password .= $random_number; 
			} 
		} 
		return $password; 
	}
?>