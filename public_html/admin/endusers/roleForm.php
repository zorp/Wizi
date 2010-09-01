        <? if( !$constrain ) $constrain = 1; ?>
        <script language="javascript" src="../scripts/double_list.js"></script>
        <script language="javascript">
        function submiting()
        {
            <?if( $constrain == 3 ):?>
                var list = document.my_form.listRight;
                document.my_form.users.value = getItems( list );
            <?endif?>
        }
	    function isPasswordConstrain()
	    {
	    	var c = document.my_form.constrain.value;
	    	if(  c == 1 || c == 3 ) document.my_form.submit();
		<? if( $constrain ): ?>
			if( <?=$constrain?> == 1 || <?=$constrain?> == 3 ) document.my_form.submit();
		<? endif ?>
	    }
        </script>
        <style>
            .selectList{ width:200px }
        </style>
        <form name="my_form" action="<?=$PHP_SELF; ?>" method="post">
        <input type="hidden" name="id" value="<?=$id?>">
        <input type="hidden" name="users">
    
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><img src="../graphics/transp.gif" height="20"></td>
			</tr>
			<tr>
				<td class="header"><?=$title?></td>
			</tr>
			<tr>
				<td><img src="../graphics/transp.gif" height="15"></td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
			<tr>
				<td width="1"><img src="graphics/transp.gif" border="0" width="0" height="250"></td>
				<td valign="top">
			
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="tdpadtext">Name</td>
					</tr>
					<tr>
						<td class="tdpadtext"><input type="text" class="input" name="name" value="<?=$role->name?>"></td>
					</tr>
					<tr>
						<td class="tdpadtext">Select constrain for this restriction</td>
					</tr>
					<tr>
						<td class="tdpadtext">
							<select name="constrain" class="input" onchange="isPasswordConstrain( )">
							    <? for( $i = 0; $i < count( $constrains ); $i++ ):?>
								<option value="<?=$constrains[$i]["id"]?>" <? if( intval( $constrains[$i]["id"] ) == intval( $constrain ) ) echo "selected"?>><?=$constrains[$i]["name"]?></option>
							    <? endfor?>
							</select>
						</td>
						</tr>

						<? if( $constrain == 2 ):?>
							<tr>
								<td class="tdpadtext"><input type="checkbox" name="showLogin" <?=( $role->showLogin !='n' )?"checked":""?> >&nbsp;Show log-in box</td>
							</tr>
						<? endif?>
						<? if( $constrain == 1 ):?>
							<tr>
								<td class="tdpadtext">Password</td>
							</tr>
							<tr>
								<td class="tdpadtext"><input type="text" name="password" class="input" value="<?=$role->password?>"></td>
							</tr>
						<? endif?>
						<tr>
							<td class="tdpadtext">Description:</td>
						</tr>
						<tr>
							<td class="tdpadtext"><textarea name="description" class="input"><?=$role->description?></textarea></td>
						</tr>
						<tr>
							<td class="tdpadtext">&nbsp;</td>
						</tr>

						<? if( $constrain == 3 ):?>
						<tr>
							<td>
									
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="210" class="tdpadtext">
									<select multiple name="listLeft" type="list" size="5" class="select_list">
									<? for( $i = 0; $i < count( $itemList ); $i++ ): ?>
			  						 <option value="<?= $itemList[$i]["id"]?>"><?= $itemList[$i]["name"]?></option>
									<? endfor ?>
									</select>
								</td>
								<td width="80">
								<input type="button" value="Add" class="knap" onclick="addItem( this.form.listLeft, this.form.listRight )">
								<br>
								<input type="button" value="Remove" class="knap" onclick="removeItem( this.form.listRight )">
					</td>
					<td class="tdpadtext">
								<select multiple name="listRight" type="list" size="5" class="select_list">
									<? for( $i = 0; $i < count( $itemList ); $i++ ): ?>
								  		<? if( $itemList[$i]["selected"] ): ?>
								  		<option value="<?= $itemList[$i]["id"]?>"><?= $itemList[$i]["name"]?></option>
								  	<? endif ?>
								<? endfor ?>
								</select>
								</td>
						</tr>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>
						</table>
						</td>
					</tr>
					<? endif ?>
				</table>
		</td>
	</tr>
</table>
<table width="298" cellpadding="0" cellspacing="0" border="0">
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
