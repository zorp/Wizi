<?php
    require_once("../util/endUserRoles.php");
    $roles     = new endUserRoles($dba);
    $rolesList = $roles->getRoles();
?>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td bgcolor="#FFFFFF" colspan="4"><img src="../graphics/transp.gif" height="20"></td>
		</tr>
		<tr>
			<td class="header">Restriction name</td>
			<td class="header">Restriction Type</td>
			<td class="header">&nbsp;</td>
			<td class="header">&nbsp;</td>
		</tr>
		<tr>
			<td bgcolor="#FFFFFF" colspan="4"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<? if( !count( $rolesList ) ):?>
		<tr class="color1">
			<td colspan="4" align="center" class="tabelText" style="padding:10px">No restrictions available</td>
		</tr>
		<? endif?>
		<? for( $i = 0; $i< count( $rolesList ); $i++ ):?>
		<tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
			<td><a href="editRole.php?id=<?=$rolesList[$i]["id"]?>" class="tabelText"><?=$rolesList[$i]["name"]?></a></td>
			<td class="tabelText"><?=$roles->getConstrainName($rolesList[$i]["constrain"])?></td>
			<td><a href="deleteRole.php?id=<?=$rolesList[$i]["id"]?>" class="redlink">Delete</a></td>
			<td><a href="editRole.php?id=<?=$rolesList[$i]["id"]?>" class="greenlink">Edit</a></td>
		</tr>
		<? endfor?>
		<tr>
			<td bgcolor="#FFFFFF" colspan="4"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
		<tr>
			<form name="my_name" action="addRole.php" method="post">
				<td class="header" colspan="4">					
					<input type="submit" value="Add restriction" name="addRole" class="knapgreen" style="width:150px">
				</td>
			</form>
		</tr>
		<tr>
			<td bgcolor="#FFFFFF" colspan="4"><img src="../graphics/transp.gif" height="15"></td>
		</tr>
	</table>