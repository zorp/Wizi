<?php
	if( !$referer ) $referer = $_POST["referer"];
	if( !$referer ) $referer = $_GET["referer"];
	
	if( $_POST["cancel"] ) die("<script type=\"text/javascript\">document.location.href='index.php?page=$referer'</script>");
	
	if( $_POST["registering"] ){
		if( !$name ) 					$name = $_POST["name"];
		if( !$full_name ) 		$full_name = $_POST["full_name"];
		if( !$password )			$password = $_POST["password"];
		if( !$password_two )	$password_two = $_POST["password_two"];
		if( !$mail )					$mail = $_POST["mail"];
		
		if( !$name )														$error["name"] = ' <span style="color:red;">*</span>';
		if( !$full_name )												$error["full_name"] = ' <span style="color:red;">*</span>';
		if( !$password )												$error["password"] = ' <span style="color:red;">*</span>';
		if( !$password_two )										$error["password_two"] = ' <span style="color:red;">*</span>';
		if( $password_two != $password )				$error["password"] = ' <span style="color:red;">*</span>';
		if( !$mail || !validateEmail($mail) )	$error["mail"] = ' <span style="color:red;">*</span>';
	
		if( !count( $error ) ){
				require_once("admin/util/endUsers.php");
				$users    = new endUsers( $dba );
				$editUser = new endUser( $dba, $users->addUser() );
				$editUser->setName( $name );
				$editUser->setFull_name( $full_name );
				$editUser->setPassword( $password );
				$editUser->setMail( $mail );
				$editUser->log( $name, $password ); //log the user
				$isSaved = true; //If no error show confirmation
		}//if
	}//if
?>
<?php if( !$isSaved ): ?>
	<h1>Register</h1>
	<p>Fill in the form to register your account.</p>
	<form name="register" action="index.php" method="post">		
		<input type="hidden" name="referer" value="<?php echo $referer; ?>">		
		<input type="hidden" name="action" value="register">
		<input type="hidden" name="registering" value="1">
		<?php if(count($error)) echo '<p style="color:red;">Fields marked with * are required</p>'; ?>
		<p><label>Username:<?php echo $error["name"]; ?></label> 
		<input type="text" name="name" value="<?php echo $name; ?>"></p>
		<p><label>Full name:<?php echo $error["full_name"]; ?></label>
		<input type="text" name="full_name" value="<?php echo $full_name; ?>"></p>
		<p><label>Password:<?php echo $error["password"]; ?></label>
		<input type="password" name="password" class="loginfield"></p>
		<p><label>Repeat password:<?php echo $error["password_two"]; ?></label>
		<input type="password" name="password_two" class="loginfield"></p>
		<p><label>E-mail:<?php echo $error["mail"]; ?></label>
		<input type="text" name="mail" value="<?php echo $mail; ?>"></p>
		<p><input name="cancel" onclick="document.location.href='index.php?page=<?php echo $referer; ?>';" type="button" value="Cancel">
		&nbsp;<input name="registering" type="submit" value="Register"></p>
	</form>
<?php else: ?>
	<h1>Your Account has been created.</h1>
	<p>Thank you for creating an account.</p>
	<p>We have automaticaly logged you in. <a href="index.php?page=<?php echo $referer; ?>">Click here to continue</a>.</p>
<?php endif ?>