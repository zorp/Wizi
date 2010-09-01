        <script language="javascript" src="../scripts/double_list.js"></script>
        <script language="javascript">
            function submiting()
            {
                var list = document.my_form.listRight;
                document.my_form.users.value = getItems( list );
            }
        </script>
        <form name="my_form" action="<?=$PHP_SELF; ?>" method="post">
        <input type="hidden" name="id" value="<?=$id?>">
        <input type="hidden" name="users">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="../graphics/transp.gif" height="20"></td>
	</tr>
		<td class="header"><?=$title?></td>
	</tr>
	</tr>
		<td><img src="../graphics/transp.gif" height="15"></td>
	</tr>
	<tr>
		<td>
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
				<tr>
						<td class="tdpadtext">Name</td>
					</tr>
					<tr>
						<td class="tdpadtext"><input type="text" class="input" name="name" value="<?=$role->name?>"></td>
					</tr>
					<tr>
						<td class="tdpadtext">Description:</td>
					</tr>
					<tr>
						<td class="tdpadtext"><textarea name="description" class="input"><?=$role->description?></textarea><td>
					</tr>
					<tr>
						<td><td>
					</tr>
					<tr>
						<td>
								<table cellpadding="0" cellspacing="0" border="0">
    								<tr>
        								<td class="tdpadtext">Available users:<br/><select multiple name="listLeft" type="list" size="5" class="select_list">
                						<?for( $i = 0; $i < count( $itemList ); $i++ ):?>
                    					<option value="<?= $itemList[$i]["id"]?>"><?= $itemList[$i]["name"]?></option>
                						<?endfor?>
            							</select>
        								</td>
        								<td class="tdpadtext" valign="middle">
            							<input type="button" value="Add" class="knapgreen" onclick="addItem( this.form.listLeft, this.form.listRight )">
										<br>
										<input type="button" value="Remove" class="knapred" onclick="removeItem( this.form.listRight )">
        								</td>
        								<td class="tdpadtext">
												Members:<br/>
            							<select multiple name="listRight" type="list" size="5"  class="select_list">
                						<?for( $i = 0; $i < count( $itemList ); $i++ ):?>
                    					<?if( $itemList[$i]["selected"] ):?>
                        				<option value="<?= $itemList[$i]["id"]?>"><?= $itemList[$i]["name"]?></option>
                    					<?endif?>
                						<?endfor?>
            							</select>
        								</td>
   									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
								</table>
					</td>
				</tr>
		</table>
<table width="500" cellpadding="0" cellspacing="0" border="0">
  <tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="right">
			<input type="button" value="Cancel" class="knapred" onclick="document.location.href='index.php?pane=roles'">
			<input type="submit" name="submited" class="knapgreen" value="OK" onclick="submiting()">
			</form>
		</td>
	</tr>
</table>
