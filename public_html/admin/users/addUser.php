<?php
/*********************************************************************/
/*   addUser.php                                                    */
/*********************************************************************/
require_once("../util/dba.php");
require_once("../util/user.php");
require_once("../util/users.php");

session_start();

if( $submited || $_POST["submited"] )
{
  if( !$name ) $name = $_POST["name"];
  if( !$full_name ) $full_name = $_POST["full_name"];
  if( !$mail ) $mail = $_POST["mail"];
  if( !$password ) $password = $_POST["password"];
  if( !$confirm_password ) $confirm_password = $_POST["confirm_password"];

  if( $password == $confirm_password )
  {
    $users  = new users( new dba() );
		$User = new user( new dba() );
		if ($User->checkName( $name ))
		{
			$message = "The username is allready in use, please choose a different one.";
		}
		else
		{
    	$editUser = new user( new dba(), $users->addUser() );
    	$editUser->setFull_name( $full_name );
    	$editUser->setPassword( $password );
    	$editUser->setMail( $mail );
    	$editUser->setName( $name );
			Header("Location:index.php" );
		}
  }
  else
  {
    $editUser->mail = $mail;
    $editUser->name = $name;
    $editUser->full_name = $full_name;
    $message = "The passwords you typed didn't match";  
  } 
}
$title   = "Add user ";
?>
<?require_once("userForm.php");?>
