<?php
//Validate if an email is correct
function validateEmail($email){
	if (!trim($email)) return false;
	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
		return true;
	}else{
		return false;
	}
}//function
?>