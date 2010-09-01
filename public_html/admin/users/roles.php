<?php
    require_once("../util/roles.php");
    $roles     = new roles($dba);
    //if( !$delete ) $delete = $_GET["delete"];
    //$roles->deleteRole( $delete );
    $rolesList = $roles->getRoles();
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="20"></td>
		</tr>
		<tr>
			<td class="header">Group name</td>
			<td class="header">&nbsp;</td>
			<td class="header">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<?for( $i = 0; $i< count( $rolesList ); $i++ ):?>
		<tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
			<td><a href="editRole.php?id=<?=$rolesList[$i]["id"]?>" class="tabelText"><?=$rolesList[$i]["name"]?></a></td>
			<td><?if( $rolesList[$i]["id"] != 1 ):?><a href="deleteRole.php?id=<?=$rolesList[$i]["id"]?>&pane=roles&rolename=<?=$rolesList[$i]["name"]?>" class="redlink">Delete</a><?else:?>&nbsp;<?endif?></td>
			<td><a href="editRole.php?id=<?=$rolesList[$i]["id"]?>" class="greenlink">Edit</a></td>
		</tr>
		<?endfor?>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<tr>
			<form name="my_name" action="addRole.php" method="post">
				<td class="header" colspan="2">					
					<input type="submit" value="Add group" name="addRole" class="knapgreen" style="width:100px; ">
				</td>
			</form>
		</tr>
		<tr>
			<td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
	</table>