<?php

	if( !$referer ) $referer = $_POST["referer"];
	if( !$referer ) $referer = $_GET["referer"];
	if( !$user->isLogged() ) die("<script type=\"text/javascript\">document.location.href='index.php?page=$referer'</script>");
	if( $_POST["cancel"] ) die("<script type=\"text/javascript\">document.location.href='index.php?page=$referer'</script>");

	if( $_POST["updatesettings"] ){
		
		if( !$name )					$name = $_POST["name"];
		if( !$full_name )			$full_name = $_POST["full_name"];
		if( !$password )			$password = $_POST["password"];
		if( !$password_two )	$password_two = $_POST["password_two"];
		if( !$mail )					$mail = $_POST["mail"];
		
		if( !$name )														$error["name"] = ' <span style="color:red;">*</span>';
		if( !$full_name )												$error["full_name"] = ' <span style="color:red;">*</span>';
		if ($password){
			if( $password_two != $password )			$error["password"] = ' <span style="color:red;">*</span>';
		}//if
		if( !$mail || !validateEmail($mail) )	$error["mail"] = ' <span style="color:red;">*</span>';
		
		if( !count( $error ) ){
				$editUser = new endUser( $dba, $users->id );
				$editUser->setName( $name );
				$editUser->setFull_name( $full_name );
				if ($password) $editUser->setPassword( $password );
				$editUser->setMail( $mail );
				$isSaved = true;//If no error show confirmation
		}//if

	}//if

	$name          = $user->name;
	$full_name     = $user->full_name;
	$password      = $user->password;
	$password_two  = $user->password;
	$mail          = $user->mail;
   
?>
<?php if( !$isSaved ): ?>
	<h1>Edit your account</h1>
	<p>Change your account settings below.</p>
<?php endif ?>
<?php if( !$isSaved ): ?>
	<form name="editaccount" action="index.php?page=<?php echo $referer; ?>&referer=<?php echo $referer; ?>" method="post">		
		<input type="hidden" name="referer" value="<?php echo $referer; ?>">
		<input type="hidden" name="action" value="editsettings">
		<?php if(count($error)) echo '<p style="color:red;">Fields marked with * are required</p>'; ?>
		<p><label>Username:<?php echo $error["name"]; ?></label> 
		<input type="text" name="name" value="<?php echo $name; ?>"></p>
		<p><label>Full name:<?php echo $error["full_name"]; ?></label>
		<input type="text" name="full_name" value="<?php echo $full_name; ?>"></p>
		<p><label>Password:<?php echo $error["password"]; ?></label>
		<input type="password" name="password" class="loginfield"></p>
		<p><label>Repeat password:<?php echo $error["password"]; ?></label>
		<input type="password" name="password_two" class="loginfield"></p>
		<p><label>E-mail:<?php echo $error["mail"]; ?></label>
		<input type="text" name="mail" value="<?php echo $mail; ?>"></p>
		<p><input name="cancel" type="submit" value="Cancel">
		&nbsp;<input name="updatesettings" type="submit" value="Save"></p>
	</form>
<?php else: ?>
	<h1>Your Account settings has been updated.</h1>
	<p><a href="index.php?page=<?php echo $referer; ?>">Click here to continue</a>.</p>
<?php endif ?>
