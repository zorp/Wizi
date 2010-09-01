<?php
	if (!$roleId) $roleId = $_GET["roleId"];
	if (!$roleName) $roleName = $_GET["roleName"];
?>
<h1>This page is password protected</h1>
<p>You are required to authenticate yourself with a password.</p>
<form name="rolelogin" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">		
	<input type="hidden" name="action" value="authenticate_role">		
	<input type="hidden" name="referer" value="<?php echo $page; ?>">		
	<input type="hidden" name="roleId" value="<?php echo ($roleId)?$roleId:$_POST["roleId"]; ?>">		
	<input type="hidden" name="roleName" value="<?php echo $roleName; ?>">
	<?php echo ($loginerror)?'<p style="color:red;">Wrong password, please try again</p>':''; ?>
	<p><label>Password:</label><input type="password" name="password"></p>
	<p><input type="submit" value="Enter" name="role_login"></p>
</form>