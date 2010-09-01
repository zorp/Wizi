<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/mediaTree.php" );

session_start();
$dba = new dba();

if (!$root) $root = $_POST["root"];
if (!$root) $root = $_GET["root"];
if (!$root) $root = 0;
$toplevel = 1;
$tree = new mediaTree( $dba, session_id(), 'mediatree' );

//get parameters
if( !$id ) $id= $_GET["id"];
if( !$id ) $id= $_POST["id"];
if( !$action ) $action= $_GET["action"];
if( !$action ) $action= $_POST["action"];
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

$tree->toggle( $toggle );
$tree->add( $add );
$tree->remove( $remove );
$tree->duplicate( $duplicate );
$move   = $tree->move( $move, $where );
$rename = $tree->rename( $rename, $newname );

$nofuncs = array();
$nodeletemove = array();
$nodelete = array(13);
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
            <input type="hidden" name="rename" value="<?=$rename?>">
            <input type="hidden" name="move" value="<?=$move?>">
						<input type="hidden" name="root" value="<?php echo $root; ?>">
            <input type="hidden" name="remove">
            <?
                $nodes =  $tree->getNodeArray($root);
                $n = count( $nodes );
            ?>
						<?php
							if ($root != 0){
								echo '<p style="padding-left:5px;">Zoomed into: <strong>'.$tree->getDocName($root).'</strong> - <a href="javascript:zoomTo(0);">Reset zoom</a></p>';
							}
						?>
            <table cellpadding="2" cellspacing="2" border="0">
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
               </tr>
						</table>
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
                            <table cellpadding="1" cellspacing="0" border="0">
                                <tr>
                                    <? //==================SPACER CELL============================?>
                                    <td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>

                                    <? //==================DISCLOSURE TRIANGLE CELL============================?>
                                    <td valign="top">
                                        <a href="#" onClick="toggling(<?=$nodes[$i]["id"]?>)" title="Toggle" onFocus="if(this.blur)this.blur();"><img src="graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a>
                                    </td>

                                    <? //==================NODE ICON CELL============================?>
                                    <td valign="top">
																			<? if (in_array($nodes[$i]["id"],$nofuncs)): ?>
																				<img src="graphics/file_icons/<?=$format?>_gray.gif" alt="" border="0"/>
																			<? else: ?>
																				<? $format = $nodes[$i]["format"];?>
																				<? if( !in_array($format, $tree->formats ) ) $format = 'unknown';?>
																				<? if( !$nodes[$i]["format"] ) $format = 'general';?>
																				<? if( $nodes[$i]["moving"] ):?>
																						<a href="<?=$PHP_SELF?>" class="nodeName_gray" onFocus="if(this.blur)this.blur();"><img src="graphics/file_icons/<?=$format?>_gray.gif" alt="Edit" border="0"/></a>
																				<? elseif( $move ):?>
																						<a href="<?=$PHP_SELF?>?move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" class="nodeName" onFocus="if(this.blur)this.blur();"><img src="graphics/file_icons/<?=$format?>.gif" alt="Edit" border="0"/></a>
																				<? else:?>
																						<? if( $nodes[$i]["id"] == 1 ):?>
																							<a href="index.php" title="Import Media" class="nodeName" target="contentfrm" onFocus="if(this.blur)this.blur();" onClick="top.mainfrm.topfrm.changePage('none');"><img src="graphics/file_icons/<?=$format?>.gif" alt="Import Media" border="0"/></a>
																						<? else:?>
																							<a href="../media/index.php?id=<?=$nodes[$i]["id"]?>" title="Edit" class="nodeName" target="contentfrm" onFocus="if(this.blur)this.blur();" onClick="top.mainfrm.topfrm.changePage('none');"><img src="graphics/file_icons/<?=$format?>.gif" alt="Edit" border="0"/></a>
																						<? endif?>
																				<? endif?>
																			<? endif?>
                                    </td>

                                    <? //==================SPACER CELL============================?>
                                    <td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>

                                    <? //==================ITEM NAME CELL============================?>
                                    <td valign="top">
																			<? if (in_array($nodes[$i]["id"],$nofuncs)): ?>
																				<span class="nodeName"><a href="#" class="nodeName"><?=$nodes[$i]["name"] ?></a></span>
																			<? else:?>
                                        <? if( $nodes[$i]["id"] == $rename ):?>
                                            <input type="text" style="width:110px;" class="textfield" name="newname" value="<?=$nodes[$i]["name"] ?>" maxlength="250"/>
                                            <script language="javascript">
                                                document.tree.newname.focus();
                                            </script>
                                        <? elseif( $nodes[$i]["moving"] ):?>
                                            <a href="<?=$PHP_SELF?>" class="nodeName_gray" onFocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
                                        <? elseif( $move ):?>
                                            <a href="<?=$PHP_SELF?>?move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" class="nodeName" onFocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
																				<? else:?>
																						<a href="#" onClick="<?if (!$rename):?>renaming(<?=$nodes[$i]["id"]?>)<?else:?>alert('Please press enter in the rename field before renaming another item')<?endif?>;" class="nodeName" onFocus="if(this.blur)this.blur();" title="Rename"><?=$nodes[$i]["name"] ?></a>
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
                                    <? if( !$move && !$rename):?>
                                        <? if( !in_array($nodes[$i]["id"],$nofuncs) ):?>
																					<td <?=( $nodes[$i]["id"] == $root )?"colspan=\"3\"":""?>><a href="<?=$_SERVER["PHP_SELF"]?>?add=<?=$nodes[$i]["id"]?>&root=<?=$root?>" onMouseOver="lightup('add', <?=$nodes[$i]["id"]?>)" onMouseOut="grayout('add', <?=$nodes[$i]["id"]?>)" title="Add" onFocus="if(this.blur)this.blur();"><img src="graphics/add_off.gif" name="add<?=$nodes[$i]["id"]?>" border="0" alt="Add"></a></td>
																					<? if( $nodes[$i]["id"] != $root && $nodes[$i]["id"] != $toplevel ):?>
																						<td><a href="<?=$_SERVER["PHP_SELF"]?>?duplicate=<?=$nodes[$i]["id"]?>&root=<?=$root?>" onMouseOver="lightup('duplicate', <?=$nodes[$i]["id"]?>)" onMouseOut="grayout('duplicate', <?=$nodes[$i]["id"]?>)" title="Duplicate" onFocus="if(this.blur)this.blur();"><img src="graphics/duplicate_off.gif" name="duplicate<?=$nodes[$i]["id"]?>" border="0" alt="Duplicate"></a></td>
																						<? if( $nodes[$i]["id"] != $root && !in_array($nodes[$i]["id"],$nodeletemove) ):?>
																							<? if( !in_array($nodes[$i]["id"],$nodelete) ):?>
																								<td><a href="#" title="Delete" onClick="removingMedia( <?=$nodes[$i]["id"]?>, '<?=$nodes[$i]["name"]?>' )" onMouseOver="lightup('delete', <?=$nodes[$i]["id"]?>)" onMouseOut="grayout('delete', <?=$nodes[$i]["id"]?>)" onFocus="if(this.blur)this.blur();"><img src="graphics/delete_off.gif" name="delete<?=$nodes[$i]["id"]?>" border="0" alt="Delete"></a></td>
																							<? endif // nodelete ?>
																							<td><a href="<?=$_SERVER["PHP_SELF"]?>?move=<?=$nodes[$i]["id"]?>&root=<?=$root?>" onMouseOver="lightup('move', <?=$nodes[$i]["id"]?>)" onMouseOut="grayout('move', <?=$nodes[$i]["id"]?>)" title="Move" onFocus="if(this.blur)this.blur();"><img src="graphics/move_off.gif" name="move<?=$nodes[$i]["id"]?>" border="0" alt="Move"></a></td>
																						<? endif // nodeletemove ?>
																						<td>
																							<?php if($nodes[$i]["node"]): ?>
																								<a href="#" onClick="zoomTo(<?=$nodes[$i]["id"]?>);" onMouseOver="lightup('magnify', <?=$nodes[$i]["id"]?>);" onMouseOut="grayout('magnify', <?=$nodes[$i]["id"]?>);" onFocus="if(this.blur)this.blur();" title="Zoom"><img src="graphics/magnify_off.gif" name="magnify<?=$nodes[$i]["id"]?>" border="0" alt="Zoom"></a>
																							<?php else: ?>
																								&nbsp;
																							<?php endif ?>
																						</td>
																					<? endif // root?>
																				<? endif //nofuncs ?>
																		<? elseif ($nodes[$i]["id"] == $rename): ?>
																			<td><input type="submit" name="savenewname" value="save"></td>
                                    <? endif?>
                                    <td>&nbsp;&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                   </tr>
								</table>
                <? endfor?>
        </form>
    </body>
</html>
