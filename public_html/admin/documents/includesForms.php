<?php
  require_once("../util/forms.php");
  $forms = new forms( $dba );
  $formList  = $forms->getForms();
  $n = count( $formList );
?>
  <input type="hidden" name="docId">
  <script language="javascript">
    function selectNode( id )
    {
      document.tree.docId.value = id;
      document.tree.submit();
    }
  </script>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td><img src="../graphics/transp.gif" height="15"></td>
		</tr>
    <?if( !$n ):?>
    <tr class="color2">
			<td align="center" class="tabelText" style="padding:25px">No forms available</td>
    </tr>
    <?endif?>  
		<?for( $i = 0; $i< $n; $i++ ):?>
		<tr class="<?=($i%2==0)?"color2":"color3"?>" style="padding-top:3px;padding-bottom:3px;">
			<td><a href="#" onclick="selectNode(<?=$formList[$i]["id"]?>)" class="tabelText"><?=$formList[$i]["name"]?></a></td>
		</tr>
		<?endfor?>
		<tr>
			<td><img src="../graphics/transp.gif" height="15"></td>
		</tr>
	</table>
