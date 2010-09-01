<?php if ( $action == "login_required" && (!$user->isLogged()) ): ?>

	<form name="loginform" action="index.php" method="post">
		<input type="hidden" name="action" value="authenticate">
		<input type="hidden" name="constrainId" value="<?php echo $constrainId; ?>">
		<input type="hidden" name="referer" value="<?=$page?>">
		<?php echo ($_GET["loginerror"])?'<p style="color:red;">Wrong password, please try again</p>':''; ?>
		<p><label>Username:</label>
		<input type="text" name="name"></p>
		<p><label>Password:</label>
		<input type="password" name="password"></p>
		<p><input type="submit" value="Enter" name="login"></p>
		<?php
			if( $myPage->role["constrainId"] != 3 ){
				echo '<a href="index.php?action=register&referer='.$page.'&page='.$page.'">Create user account</a>';
			}
		?>
	</form>

<? endif?>