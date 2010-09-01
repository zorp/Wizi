<?php
    require_once("../util/template.php");
    require_once("../util/tree.php");
    require_once("../util/templateTree.php");

    $template = new template( $dba );
    $template->getTemplates();

    if( !$docId ) $docId = $_POST["docId"];
    if( !$selected_template ) $selected_template = $_POST["selected_template"];
    if( !$selected_template ) $selected_template = "default";

    if( $docId ) $template->select( $docId, $selected_template );

    $tree = new templateTree( $dba, session_id(), 'tree' );

    if( !$toggle ) $toggle = $_POST["toggle"];

    $tree->toggle( $toggle );
    $nodes =  $tree->getNodeArray();
    $n = count( $nodes );
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

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="../graphics/transp.gif" width="15" height="20"></td>
	</tr>	
	<tr>	
		<td class="header">Choose the template documents should use<br><br></td>			
	</tr>
</table>
<form name="tree" action="<?=$PHP_SELF?>" method="post">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
	<tr>
 		<td width="1" valign="top"><img src="../graphics/transp.gif" border="0" width="1" height="250"></td>
 		<td valign="top">
			<table cellpadding="0" cellspacing="0"  border="0">
				<tr>
					<td colspan="2" height="5"><img src="../graphics/transp.gif" border="0" width="10" height="10"></td>
				</tr>
				<tr>
					<td valign="top" class="tdpadtext">
						Choose a template:<br/><br/>
						<select name="selected_template" class="select_list_small" onchange="document.tree.submit()">
							<?for( $i = 0; $i < count( $template->templates ); $i++ ):?>
								<option value="<?=$template->templates[$i]?>" <?=( $selected_template == $template->templates[$i] )?"selected":""?>><?=$template->templates[$i]?></option>
							<?endfor?>
						</select>
						<br/><br/>
						<?if( file_exists( "../../templates/". $selected_template .".gif" ) ):?>
							<img src="../../templates/<?=$selected_template?>.gif">
						<?else:?>
							<img src="../../templates/noIcon.gif">
						<?endif?>
					</td>
					<td valign="top" class="tdpadtext" style="padding-left:25px;">
						<input type="hidden" name="toggle">
						<input type="hidden" name="docId">
						<!--Start Tree-->
						Choose documents template belong to:<br/><br/>
						<table cellpadding="0" cellspacing="0" border="0">															
							<?for( $i = 0; $i < $n; $i++ ):?>
							<tr>
								<?//==================NODE TABLE CELL============================?>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<?//==================SPACER CELL============================?>
											<td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="../tree/graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>
											<?//==================DISCLOSURE TRIANGLE CELL============================?>
											<td><a href="#" onclick="toggling( <?=$nodes[$i]["id"]?> )" title="Toggle" onfocus="if(this.blur)this.blur();"><img src="../tree/graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a></td>
											<?//==================NODE ICON CELL============================?>
											<td>
												<?if( ( $selected_template == "default" && !$nodes[$i]["template"] ) || $nodes[$i]["template"] == $selected_template ):?>
												<span class="nodeName"><img src="../tree/graphics/doc.gif" alt="Icon" border="0"/></span>
												<?else:?>
												<a href="#" onclick="selectNode( <?=$nodes[$i]["id"]?> )" title="Select" class="nodeName" onfocus="if(this.blur)this.blur();"><img src="../tree/graphics/doc_gray.gif" alt="Select" border="0"/></a>
												<?endif?>
											</td>
											<?//==================SPACER CELL============================?>
											<td><img src="../tree/graphics/space.gif" width="5" height="10" alt="space"\></td>
											<?//==================ITEM NAME CELL============================?>
											<td>
												<?if( ( $selected_template == "default" && !$nodes[$i]["template"] ) || $nodes[$i]["template"] == $selected_template ):?>
												<span class="nodeName"><?=$nodes[$i]["name"]?></span>
												<?else:?>
												<a href="#" onclick="selectNode( <?=$nodes[$i]["id"]?> )" title="Select" class="nodeName" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
												<?endif?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<?endfor?>
						</table>
						<!--End Tree-->
					</td>
				</tr>
			</table>
		</td>
	</tr>										
</table>
</form>