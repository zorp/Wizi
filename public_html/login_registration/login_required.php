<h1>This page is password protected</h1>
<p>You are required to be authenticated by username and password.</p>
<?php if( $myPage->role["constrainId"] == 3 ): ?>
	<p>And to be a member of the "<?php echo $myPage->role["roleName"]; ?>" security area.</p>
<?php endif ?>
<?php require_once("login_registration/loginform.php"); ?>