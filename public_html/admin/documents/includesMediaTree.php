<?php
require_once("../util/tree.php");
include_once( "../util/mediaTree.php" );
$tree = new mediaTree( $dba, session_id(), 'mediatree' );

if( !$toggle ) $toggle = $_POST["toggle"];
$tree->toggle( $toggle );
$nodes =  $tree->getNodeArray();
$n = count( $nodes );

$includeformats = array("gif","jpg","pdf","png","swf","htm","html");
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
<input type="hidden" name="toggle">
<input type="hidden" name="docId">
            <table cellpadding="0" cellspacing="0" border="0">
                <? for( $i = 0; $i < $n; $i++ ):?>
                    <tr>
                        <? //==================NODE TABLE CELL============================?>
                        <td>
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <? //==================SPACER CELL============================?>
                                    <td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="../mediatree/graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>

                                    <? //==================DISCLOSURE TRIANGLE CELL============================?>
                                    <td>
                                        <a href="#" onclick="toggling(<?=$nodes[$i]["id"]?>)" title="Toggle"><img src="../mediatree/graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a>
                                    </td>

                                    <? //==================NODE ICON CELL============================?>
                                    <td>
                                        <? $format = $nodes[$i]["format"];?>
																				<? if( !in_array($format, $tree->formats ) ) $format = 'unknown';?>
																				<? if( !$nodes[$i]["format"] ) $format = 'general';?>
                                        <? if ($format != 'general'):?>
																					<a href="#" onclick="selectNode(<?=$nodes[$i]["id"]?>);return false;" title="Select" class="nodeName"><img src="../mediatree/graphics/file_icons/<?=$format?>.gif" alt="Select" border="0"/></a>
																				<? else: ?>
																					<img src="../mediatree/graphics/file_icons/<?=$format?>_gray.gif" alt="" border="0"/>
																				<? endif ?>
                                    </td>

                                    <? //==================SPACER CELL============================?>
                                    <td><img src="../mediatree/graphics/space.gif" width="5" height="10" alt="space"\></td>

                                    <? //==================ITEM NAME CELL============================?>
                                    <td class="nodeName">
                                        <? if ($format != 'general'):?>
																					<a href="#" onclick="selectNode(<?=$nodes[$i]["id"]?>);return false;" class="nodeName"><?=$nodes[$i]["name"] ?></a>
																				<? else: ?>
																					<span style="color:#666666;"><?=$nodes[$i]["name"] ?></span>
																				<? endif ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                   </tr>
                <? endfor?>
            </table>
