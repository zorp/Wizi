<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/user.php" );
require_once("../util/overview.php");

session_start();
$dba = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

if (!$root) $root = $_POST["root"];
if (!$root) $root = $_GET["root"];
if (!$root) $root = 0;
$tree = new tree( $dba, session_id(), 'tree' );

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$action ) $action = $_POST["action"];
if( !$newNodeName ) $newNodeName = $_POST["newNodeName"];
if( !$movingNode ) $movingNode = $_POST["movingNode"];
if( !$toggle ) $toggle = $_POST["toggle"];
if( !$add ) $add = $_GET["add"];
if( !$remove ) $remove = $_GET["remove"];
if( !$remove ) $remove = $_POST["remove"];
if( !$duplicate ) $duplicate = $_GET["duplicate"];
if( !$move ) $move = $_GET["move"];
if( !$where ) $where = $_GET["where"];
if( !$rename ) $rename = $_POST["rename"];
if( !$newname ) $newname = $_POST["newname"];
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
if( !$selected_overview ) $selected_overview = $_POST["selected_overview"];
//if( !$selected_overview ) $selected_overview = "publish";

$tree->toggle( $toggle );
$tree->add( $add );
$tree->remove( $remove );
$tree->duplicate( $duplicate );
$move   = $tree->move( $move, $where );
$rename = $tree->rename( $rename, $newname );

$constrains = $user->getConstrains();

$overview = new overview( $dba );
$filter = $overview->getFilter( $selected_overview );

$nofuncs = array();
$nodeletemove = array(1);
$nodelete = array();
?>
<html>
	<head>
		<title>Site tree</title>
		<link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
		<script language="javascript" src="../scripts/global_funcs.js"></script>
	</head>
	<body bgcolor="#FFFFFF">
		<form type="submit" name="tree" action="<?=$PHP_SELF?>" method="POST">
			<input type="hidden" name="toggle">
			<input type="hidden" name="remove">
			<input type="hidden" name="rename" value="<?=$rename?>">
			<input type="hidden" name="move" value="<?=$move?>">
			<input type="hidden" name="root" value="<?php echo $root; ?>">
			<?php
				$nodes =  $tree->getNodeArray($root);
				$n = count( $nodes );
			?>
			<table cellpadding="2" cellspacing="2" height="98%">
				<tr>
				<td valign="top" style="padding-top:20px;">
				<?php
					if ($root != 0){
						echo '<p style="padding-left:5px;">Zoomed into: <strong>'.$tree->getDocName($root).'</strong> - <a href="javascript:zoomTo(0);">Reset zoom</a></p>';
					}
				?>
			<? for( $i = 0; $i < $n; $i++ ):?>
			<?php
				if ($nodes[$i]["id"]!=$root && $root != 0){
					if ($nodes[$i]["level"]==0){
						$nodes[$i]["level"] = 1;
					}else{
						$nodes[$i]["level"]++;
					}
				}
			?>
			<table cellpadding="1" cellspacing="1" border="0">
				<tr>
					<? //==================NODE TABLE CELL============================?>
					<td align="left" onMouseOver="shownode( <?=$nodes[$i]["id"]?> )"  onmouseout="hidenode( <?=$nodes[$i]["id"]?> )">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<? //==================SPACER CELL============================?>
								<td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>
								<? //==================DISCLOSURE TRIANGLE CELL============================?>
								<td valign="top">
									<a href="#" onClick="toggling( <?=$nodes[$i]["id"]?> )" title="Toggle" onFocus="if(this.blur)this.blur();"><img src="graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a>
								</td>
								<? //==================NODE ICON CELL============================?>
								<td valign="top">
									<?
	                  $iconPostFix='';
	                  $alt='';
										
										if (!$filter) $iconPostFix='_normal';
	
	                  if( $filter[ $nodes[$i]["id"] ]["state"] )
	                  {
	                    if( $selected_overview != 'docs' ) $iconPostFix.="_gray";
	                  }
	                  else
	                  {
	                    if( $selected_overview == 'docs' ) $iconPostFix.="_gray";
	                  }
	                  
	                  if( $filter[ $nodes[$i]["id"] ]["unpublish"] )
	                  {
	                    $iconPostFix.="_unpub";
	                    $alt = " Unpublish scheduled to: ".$filter[ $nodes[$i]["id"]]["unpublish"];
	                  }
	
	                  if( $filter[ $nodes[$i]["id"] ]["publish"] )
	                  {
	                    $iconPostFix.="_pub";
	                    $alt.= " Publish scheduled to:". $filter[ $nodes[$i]["id"]]["publish"];
	                  }
                  ?>
									
									<? if (in_array($nodes[$i]["id"],$nofuncs)): ?>
										<span class="nodeName"><img src="graphics/doc<?=$iconPostFix?>.gif" border="0"/></span>
									<? else: ?>
										<? if( $nodes[$i]["moving"] ):?>
											<a href="<?=$PHP_SELF?>" class="nodeName_gray" onFocus="if(this.blur)this.blur();"><img src="graphics/doc_gray.gif" border="0"/></a>
										<? elseif( $move ):?>
											<a href="<?=$PHP_SELF?>?move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" class="nodeName" onFocus="if(this.blur)this.blur();"><img src="graphics/doc.gif"border="0"/></a>
										<? else:?>
											<? if (in_array($nodes[$i]["id"],$nofuncs)): ?>
												<span class="nodeName"><img src="graphics/doc<?=$iconPostFix?>.gif" border="0"/></span>
											<? else: ?>
												<a href="../documents/index.php?id=<?=$nodes[$i]["id"]?>" title="Edit" class="nodeName" target="contentfrm" onFocus="if(this.blur)this.blur();" onClick="top.mainfrm.topfrm.changePage('none');"><img src="graphics/doc<?=$iconPostFix?>.gif" alt="Edit" border="0"/></a>
											<? endif ?>
										<? endif?>
									<? endif?>
								</td>
								<? //==================SPACER CELL============================?>
								<td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>
								<? //==================ITEM NAME CELL============================?>
								<td valign="top">
									<? if (in_array($nodes[$i]["id"],$nofuncs)): ?>
										<span class="nodeName"><a href="#" class="nodeName"><?=$nodes[$i]["name"] ?></a></span>
									<? else: ?>
										<? if( $nodes[$i]["id"] == $rename ):?>
											<input type="text" style="width:110px;" class="textfield" name="newname" value="<?=$nodes[$i]["name"] ?>" maxlength="250"/>
											<script language="javascript">
												document.tree.newname.focus();
											</script>
										<? elseif( $nodes[$i]["moving"] ):?>
											<a href="<?=$PHP_SELF?>?root=<?=$root?>" class="nodeName_gray" onFocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
										<? elseif( $move ):?>
											<a href="<?=$PHP_SELF?>?move=<?=$move?>&where=<?=$nodes[$i]["id"]?>&root=<?=$root?>" class="nodeName" onFocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
										<? else:?>
											<? if( !$constrains["Rename"][ $nodes[$i]["id"] ]):?>
												<span class="nodeName"><?=$nodes[$i]["name"] ?></span>
											<? else:?>
												<a href="#" onClick="<? if (!$rename):?>renaming(<?=$nodes[$i]["id"]?>)<? else:?>alert('Please press enter in the rename field before renaming another item')<? endif?>;" class="<?=( !$iconPostFix || !stristr('_gray',$iconPostFix) )?"nodeName":"nodeName_gray"?>" onFocus="if(this.blur)this.blur();" title="Rename"><?=$nodes[$i]["name"] ?></a>
											<? endif?>
										<? endif?>
									<? endif?>
								</td>
							</tr>
						</table>
					</td>
					<? //==================ACTIONS TABLE CELL============================?>
					<td onMouseOver="shownode( <?=$nodes[$i]["id"]?> )"  onmouseout="hidenode( <?=$nodes[$i]["id"]?> )">
						<table border="0" cellpadding="0" cellspacing="0" class="hide" id="<?=$nodes[$i]["id"]?>">
							<tr>
								<td>&nbsp;&nbsp;</td>
								<? if( !$move && !$rename ):?>
									<? if( !in_array($nodes[$i]["id"],$nofuncs) ):?>
									<td <?=( $nodes[$i]["id"] == $root )?"colspan=\"3\"":""?>>
										<? if( $constrains["Create"][ $nodes[$i]["id"] ]):?>
											<a href="<?=$PHP_SELF?>?add=<?=$nodes[$i]["id"]?>&root=<?=$root?>" onMouseOver="lightup('add', <?=$nodes[$i]["id"]?>)" onMouseOut="grayout('add', <?=$nodes[$i]["id"]?>)" title="Add" onFocus="if(this.blur)this.blur();"><img src="graphics/add_off.gif" name="add<?=$nodes[$i]["id"]?>" border="0" alt="Add"></a>
										<? endif?>
									</td>
									<? endif ?>
									<? if( $nodes[$i]["id"] != $root && !in_array($nodes[$i]["id"],$nodeletemove) ):?>
										<td>
											<? if( $constrains["Duplicate"][ $nodes[$i]["id"] ]):?>
												<a href="<?=$PHP_SELF?>?duplicate=<?=$nodes[$i]["id"]?>&root=<?=$root?>" onMouseOver="lightup('duplicate', <?=$nodes[$i]["id"]?>)" onMouseOut="grayout('duplicate', <?=$nodes[$i]["id"]?>)" title="Duplicate" onFocus="if(this.blur)this.blur();"><img src="graphics/duplicate_off.gif" name="duplicate<?=$nodes[$i]["id"]?>" border="0" alt="Duplicate"></a>
											<? endif?>
										</td>
										<? if( !in_array($nodes[$i]["id"],$nodelete) ):?>
										<td>
											<? if( $constrains["Delete"][ $nodes[$i]["id"] ]):?>
												<a href="#" title="Delete" onClick="removingDoc( <?=$nodes[$i]["id"]?>, '<?=ereg_replace ("'", "\'", $nodes[$i]["name"])?>' )" onMouseOver="lightup('delete', <?=$nodes[$i]["id"]?>)" onMouseOut="grayout('delete', <?=$nodes[$i]["id"]?>)" onFocus="if(this.blur)this.blur();"><img src="graphics/delete_off.gif" name="delete<?=$nodes[$i]["id"]?>" border="0" alt="Delete"></a>
											<? endif?>
										</td>
										<? endif ?>
										<td>
											<? if( $constrains["Move"][ $nodes[$i]["id"] ]):?>
												<a href="<?=$PHP_SELF?>?move=<?=$nodes[$i]["id"]?>&root=<?=$root?>" onMouseOver="lightup('move', <?=$nodes[$i]["id"]?>)" onMouseOut="grayout('move', <?=$nodes[$i]["id"]?>)" title="Move" onFocus="if(this.blur)this.blur();"><img src="graphics/move_off.gif" name="move<?=$nodes[$i]["id"]?>" border="0" alt="Move"></a>
											<? endif?>
										</td>
									<? endif?>
									<td>
										<?php if($nodes[$i]["node"]): ?>
											<a href="#" onClick="zoomTo(<?=$nodes[$i]["id"]?>);" onMouseOver="lightup('magnify', <?=$nodes[$i]["id"]?>);" onMouseOut="grayout('magnify', <?=$nodes[$i]["id"]?>);" onFocus="if(this.blur)this.blur();" title="Zoom"><img src="graphics/magnify_off.gif" name="magnify<?=$nodes[$i]["id"]?>" border="0" alt="Zoom"></a>
										<?php else: ?>
											&nbsp;
										<?php endif ?>
									</td>
								<? elseif ($nodes[$i]["id"] == $rename): ?>
									<td><input type="submit" name="savenewname" value="save"></td>
								<? endif?>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<? endfor?>
			</td>
			</tr>
			<tr>
				<td valign="bottom" style="padding-bottom:20px;">
			<br /><br /><br /><br /><br />
			<span style="padding-left:10px;"><select name="selected_overview" style="width:170px;" class="select_list" onChange="document.tree.submit()">
			<option style="background-color:#8B9EA6; color:#FFFFFF;" value="">Fast website overview:</option>
			<option value="">--------------------</option>
			<option value="">Reset filter</option>
			<option value="">--------------------</option>
			<option value="draft" <?=( $selected_overview =='draft')?"selected":""?>>Document in draft</option>
			<option value="publish" <?=( $selected_overview =='publish')?"selected":""?>>Published - unpublished</option>
			<option value="nav" <?=( $selected_overview =='nav')?"selected":""?>>Visible on navigation</option>
   		<option value="title" <?=( $selected_overview =='title')?"selected":""?>>Without title</option>
			</select></span>
			</td>
			</tr>
			</table>
		</form>
	</body>
</html>