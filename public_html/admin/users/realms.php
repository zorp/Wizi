<?
    require_once( "../util/realms.php");
    require_once( "../util/role.php");
    require_once("../util/roles.php");
    require_once( "../util/tree.php");
    
    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];
    if( !$id ) $id = 1;
    $role   = new role( $dba, $id );
    $roles  = new roles($dba);
    $rolesList = $roles->getRoles();

    if( !$realmselect ) $realmselect = $_POST["realmselect"];
    if( !$realmselect ) $realmselect = "1";
    if( !$docId ) $docId = $_POST["docId"];
    if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
    
    $realm  = new realm( $dba, $role->id, $realmselect );
    $realms = $realm->getRealms(); 

    if( $docId )
    {
	$realm->toogleConstrainsForDoc( $docId );
	if( $user->rolesById[ $role->id ] ) $reloadDocumentTree = true;
    }

    $realms4role = $realm->getDocConstrainsForRoleAndRealm();

    $tree = new tree( $dba, session_id(), 'tree' );
    if( !$toggle ) $toggle = $_GET["toggle"];
    if( !$toggle ) $toggle = $_POST["toggle"];
    $tree->toggle( $toggle );
?>
<script language="javascript">
	function toggling( id )
	{
		document.tree.toggle.value = id;
		document.tree.submit();
	}
	function selectNode( id )
	{
		document.tree.docId.value = id;
		document.tree.submit();
	}

</script>
<form name="tree" action="<?=$PHP_SELF?>" method="post">
	<input type="hidden" name="id" value="<?=$role->id?>">
  <input type="hidden" name="pane" value="realms">
  <input type="hidden" name="toggle">
  <input type="hidden" name="docId">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><img src="../graphics/transp.gif" border="0" height="20">	</td>
		</tr>
		<tr>
			<td class="header">Group areas</td>
		</tr>
		<tr>
			<td><img src="../graphics/transp.gif" border="0" height="15">	</td>
		</tr>
	</table>

	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
		<tr>
			<td width="1"><img src="../graphics/transp.gif" border="0" height="250" width="1"></td>
			<td valign="top">
	    	<table cellpadding="0" cellspacing="0" border="0">
	    		<tr>
	      		<td valign="top" class="tdpadtext" style="padding-right:15px;">
							Select group:<br/>
							<select name="id" class="select_list" onchange="document.tree.submit()">
		    				<?for( $i = 0; $i< count( $rolesList ); $i++ ):?>
	              	<option value="<?=$rolesList[$i]["id"]?>" <?=( $rolesList[$i]["id"] == $id )?"selected":""?>><?=$rolesList[$i]["name"]?></option>
	              <?endfor?>
							</select>
							<br/><br/>
							Select restriction:
							<br/>
							<select name="realmselect" class="select_list" onchange="document.tree.submit()">
								<?for( $i = 0; $i < count( $realms ); $i++ ):?>
									<option value="<?=$realms[$i]["id"]?>" <?=( $realms[$i]["id"] == $realmselect )?"selected":""?>><?=$realms[$i]["name"]?></option>
								<?endfor?>
							</select>
						</td>
	          <td valign="top" class="tdpadtext">
							Select documents<br/>
							<!-- Her starter tabellen for træet-->
							<?
							$nodes =  $tree->getNodeArray();
							$n = count( $nodes );
							?>
							<table width="250" cellpadding="0" cellspacing="0" border="0" class="tdpadtext">
								<?for( $i = 0; $i < $n; $i++ ):?>
								<tr>
									<?//==================NODE TABLE CELL============================?>
									<td>
								    <table cellpadding="0" cellspacing="0" border="0">
											<tr>
								      	<?//==================SPACER CELL============================?>
								        <td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="../tree/graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>
								        <?//==================DISCLOSURE TRIANGLE CELL============================?>
								        <td>
													<a href="#" onclick="toggling( <?=$nodes[$i]["id"]?> )" title="Toggle" onfocus="if(this.blur)this.blur();"><img src="../tree/graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a>
								        </td>
							          <?//==================NODE ICON CELL============================?>
							          <td>
													<a href="#" onclick="selectNode( <?=$nodes[$i]["id"]?> )" title="Link" class="nodeName" onfocus="if(this.blur)this.blur();"><img src="../tree/graphics/doc<?=( $realms4role[ $nodes[$i]["id"] ] )?"":"_gray"?>.gif" alt="Toggle" border="0"/></a>
												</td>
												<?//==================SPACER CELL============================?>
												<td><img src="../tree/graphics/space.gif" width="5" height="10" alt="space"\></td>
												<?//==================ITEM NAME CELL============================?>
								        <td>
													<a href="#" onclick="selectNode( <?=$nodes[$i]["id"]?> )" title="Link" class="nodeName<?=( $realms4role[ $nodes[$i]["id"] ] )?"":"_gray"?>" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?endfor?>
							</table>
							<!-- HER SLUTTER TRÆET-->
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<?if( $reloadDocumentTree ):?>
    <script language="javascript">top.treefrm.document.location.href='../tree/tree.php';</script>
<?endif?>
