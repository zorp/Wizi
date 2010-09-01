<?php
require_once("../util/tree.php");
$tree = new tree( $dba, session_id(), 'tree' );

if( !$toggle ) $toggle = $_POST["toggle"];
//get parameters
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
<input type="hidden" name="toggle">
<input type="hidden" name="docId">
                <table cellpadding="0" cellspacing="0" border="0">
                    <?for( $i = 0; $i < $n; $i++ ):?>
                        <tr>
                            <?//==================NODE TABLE CELL============================?>
                            <td align="left">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <?//==================SPACER CELL============================?>
                                        <td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="../tree/graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>

                                        <?//==================DISCLOSURE TRIANGLE CELL============================?>
                                        <td>
                                            <a href="#" onclick="toggling( <?=$nodes[$i]["id"]?> )" title="Toggle"><img src="../tree/graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a>
                                        </td>

                                        <?//==================NODE ICON CELL============================?>
                                        <td>
					    <?if( $nodes[$i]["id"] == $document->id ):?>
						<span class="nodeName"><img src="../tree/graphics/doc_gray.gif" alt="Icon" border="0"/></span>
					    <?else:?>
						<a href="#" onclick="selectNode( <?=$nodes[$i]["id"]?> )" title="Link" class="nodeName"><img src="../tree/graphics/doc.gif" alt="Toggle" border="0"/></a>
					    <?endif?>
                                        </td>

                                        <?//==================SPACER CELL============================?>
                                        <td><img src="../tree/graphics/space.gif" width="5" height="10" alt="space"\></td>

                                        <?//==================ITEM NAME CELL============================?>
                                        <td>
					    <?if( $nodes[$i]["id"] == $document->id ):?>
						<span class="nodeName"><?=$nodes[$i]["name"]?></span>
					    <?else:?>
                                            <a href="#" onclick="selectNode( <?=$nodes[$i]["id"]?> )" title="Link" class="nodeName"><?=$nodes[$i]["name"] ?></a>
					    <?endif?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                       </tr>
                    <?endfor?>
                </table>
